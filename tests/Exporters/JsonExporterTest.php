<?php

declare(strict_types=1);

namespace Metin\Exodus\Tests\Exporters;

use Metin\Exodus\Core\ExportManager;
use Metin\Exodus\Dto\ExportOptions;
use Metin\Exodus\Exporters\JsonExporter;
use Metin\Exodus\Tests\TestCase;

class JsonExporterTest extends TestCase
{
    private ExportManager $manager;
    private string $outputPath;
    private array $testData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new ExportManager(new JsonExporter());
        $this->outputPath = $this->getTestOutputPath();
        $this->testData = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Doe', 'email' => 'jane@example.com'],
        ];
    }

    public function test_it_can_export_json_file(): void
    {
        $options = new ExportOptions(
            path: $this->outputPath,
            filename: 'test.json',
            formatOptions: [
                'prettyPrint' => true,
                'unescapeUnicode' => true,
            ]
        );

        $result = $this->manager->process($this->testData, $options);

        $this->assertTrue($result->success);
        $this->assertFileExists($result->path);
        $this->assertGreaterThan(0, $result->size);

        $content = file_get_contents($result->path);
        $decodedContent = json_decode($content, true);

        $this->assertIsArray($decodedContent);
        $this->assertCount(2, $decodedContent);
        $this->assertEquals('John Doe', $decodedContent[0]['name']);
        $this->assertEquals('jane@example.com', $decodedContent[1]['email']);
    }

    public function test_it_can_export_without_pretty_print(): void
    {
        $options = new ExportOptions(
            path: $this->outputPath,
            filename: 'compact.json',
            formatOptions: [
                'prettyPrint' => false,
                'unescapeUnicode' => true,
            ]
        );

        $result = $this->manager->process($this->testData, $options);

        $this->assertTrue($result->success);

        $content = file_get_contents($result->path);
        $this->assertStringNotContainsString("\n", $content);
        $this->assertStringNotContainsString('    ', $content);

        $decodedContent = json_decode($content, true);
        $this->assertIsArray($decodedContent);
    }

    public function test_it_can_handle_unicode_characters(): void
    {
        $unicodeData = [
            ['name' => 'José González', 'email' => 'jose@example.com'],
        ];

        $options = new ExportOptions(
            path: $this->outputPath,
            filename: 'unicode.json',
            formatOptions: [
                'prettyPrint' => true,
                'unescapeUnicode' => true,
            ]
        );

        $result = $this->manager->process($unicodeData, $options);

        $this->assertTrue($result->success);

        $content = file_get_contents($result->path);
        $this->assertStringContainsString('José González', $content);
        $this->assertStringNotContainsString('\u', $content);
    }

    public function test_it_generates_filename_if_not_provided(): void
    {
        $options = new ExportOptions(
            path: $this->outputPath,
            formatOptions: [
                'prettyPrint' => true,
                'unescapeUnicode' => true,
            ]
        );

        $result = $this->manager->process($this->testData, $options);

        $this->assertTrue($result->success);
        $this->assertMatchesRegularExpression('/export_\d+\.json$/', $result->path);
    }

    public function test_it_throws_exception_for_empty_data(): void
    {
        $options = new ExportOptions(
            path: $this->outputPath,
        );

        $result = $this->manager->process([], $options);

        $this->assertFalse($result->success);
        $this->assertStringContainsString('Data cannot be empty', $result->error);
    }

    public function test_it_throws_exception_for_invalid_data_structure(): void
    {
        $data = [
            ['name' => 'John'],
            ['different' => 'structure'],
        ];

        $options = new ExportOptions(
            path: $this->outputPath,
        );

        $result = $this->manager->process($data, $options);

        $this->assertFalse($result->success);
        $this->assertStringContainsString('Invalid data structure', $result->error);
    }

    public function test_it_throws_exception_for_invalid_directory(): void
    {
        $options = new ExportOptions(path: '/invalid/path');

        $result = $this->manager->process($this->testData, $options);

        $this->assertFalse($result->success);
        $this->assertStringContainsString('Directory "/invalid/path" does not exist', $result->error);
    }
}
