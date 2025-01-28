<?php

declare(strict_types=1);

namespace Metin\Exodus\Tests\Exporters;

use Metin\Exodus\Core\ExportManager;
use Metin\Exodus\Dto\ExportOptions;
use Metin\Exodus\Exporters\XmlExporter;
use Metin\Exodus\Tests\TestCase;

class XmlExporterTest extends TestCase
{
    private ExportManager $manager;
    private string $outputPath;
    private array $testData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = new ExportManager(new XmlExporter());
        $this->outputPath = $this->getTestOutputPath();
        $this->testData = [
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Doe', 'email' => 'jane@example.com'],
        ];
    }

    public function test_it_can_export_xml_file(): void
    {
        $options = new ExportOptions(
            path: $this->outputPath,
            filename: 'test.xml',
            formatOptions: [
                'rootElement' => 'root',
                'itemElement' => 'item',
                'prettyPrint' => true,
            ]
        );

        $result = $this->manager->process($this->testData, $options);

        $this->assertTrue($result->success);
        $this->assertFileExists($result->path);
        $this->assertGreaterThan(0, $result->size);

        $xml = simplexml_load_file($result->path);

        $this->assertNotFalse($xml);
        $this->assertCount(2, $xml->item);
        $this->assertEquals('John Doe', (string)$xml->item[0]->name);
        $this->assertEquals('jane@example.com', (string)$xml->item[1]->email);
    }

    public function test_it_can_use_custom_elements(): void
    {
        $options = new ExportOptions(
            path: $this->outputPath,
            filename: 'custom.xml',
            formatOptions: [
                'rootElement' => 'users',
                'itemElement' => 'user',
            ]  
        );

        $result = $this->manager->process($this->testData, $options);

        $this->assertTrue($result->success);

        $xml = simplexml_load_file($result->path);
        $this->assertEquals('users', $xml->getName());
        $this->assertCount(2, $xml->user);
    }

    public function test_it_generates_filename_if_not_provided(): void
    {
        $options = new ExportOptions(
          path: $this->outputPath,  
        );

        $result = $this->manager->process($this->testData, $options);

        $this->assertTrue($result->success);
        $this->assertMatchesRegularExpression('/export_\d+\.xml$/', $result->path);

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
}
