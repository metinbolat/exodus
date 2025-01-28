<?php

declare(strict_types=1);

namespace Metin\Exodus\Tests\Exporters;

use Metin\Exodus\Core\ExportManager;
use Metin\Exodus\Dto\ExportOptions;
use Metin\Exodus\Exporters\CsvExporter;
use Metin\Exodus\Tests\TestCase;

class CsvExporterTest extends TestCase
{
    private ExportManager $manager;
    private string $outputPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new ExportManager(new CsvExporter());
        $this->outputPath = $this->getTestOutputPath();
    }

    public function test_it_can_export_csv_file(): void
    {
        $data = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Doe', 'email' => 'jane@example.com'],
        ];

        $options = new ExportOptions(
            path: $this->outputPath,
            filename: 'test.csv',
            formatOptions: [
                'delimiter' => ',',
                'includeHeaders' => true,
            ]
        );

        $result = $this->manager->process($data, $options);

        $this->assertTrue($result->success);
        $this->assertFileExists($result->path);
        $this->assertGreaterThan(0, $result->size);

        $content = file_get_contents($result->path);
        $expectedContent = "name,email\n\"John Doe\",john@example.com\n\"Jane Doe\",jane@example.com\n";
        $this->assertEquals($expectedContent, $content);
    }

    public function test_it_can_export_without_headers(): void
    {
        $data = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
        ];

        $options = new ExportOptions(
            path: $this->outputPath,
            filename: 'no-headers.csv',
            formatOptions: [
                'delimiter' => ',',
                'includeHeaders' => false,
            ]
        );

        $result = $this->manager->process($data, $options);

        $this->assertTrue($result->success);

        $content = file_get_contents($result->path);
        $this->assertStringNotContainsString('name,email', $content);
        $this->assertStringContainsString('"John Doe",john@example.com', $content);
    }

    public function test_it_can_use_custom_delimiter(): void
    {
        $data = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
        ];

        $options = new ExportOptions(
            path: $this->outputPath,
            filename: 'semicolon.csv',
            formatOptions: [
                'delimiter' => ';',
                'includeHeaders' => true,
            ]
        );

        $result = $this->manager->process($data, $options);

        $this->assertTrue($result->success);

        $content = file_get_contents($result->path);
        $this->assertStringContainsString('name;email', $content);
        $this->assertStringContainsString('"John Doe";john@example.com', $content);
    }

    public function test_it_generates_filename_if_not_provided(): void
    {
        $data = [['name' => 'John']];
        $options = new ExportOptions(path: $this->outputPath);

        $result = $this->manager->process($data, $options);

        $this->assertTrue($result->success);
        $this->assertMatchesRegularExpression('/export_\d+\.csv$/', $result->path);
    }

    public function test_it_throws_exception_for_invalid_directory(): void
    {
        $data = [['name' => 'John']];
        $options = new ExportOptions(path: '/invalid/path');

        $result = $this->manager->process($data, $options);

        $this->assertFalse($result->success);
        $this->assertStringContainsString('Directory "/invalid/path" does not exist', $result->error);
    }

    public function test_it_throws_exception_for_empty_data(): void
    {
        $options = new ExportOptions(path: $this->outputPath);

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

        $options = new ExportOptions(path: $this->outputPath);

        $result = $this->manager->process($data, $options);

        $this->assertFalse($result->success);
        $this->assertStringContainsString('Invalid data structure', $result->error);
    }
}
