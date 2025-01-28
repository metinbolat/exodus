<?php

declare(strict_types=1);

namespace Metin\Exodus\Tests\Dto;

use Metin\Exodus\Dto\ExportOptions;
use Metin\Exodus\Tests\TestCase;

class ExportOptionsTest extends TestCase
{
    public function test_it_can_be_instantiated_with_minimum_parameters(): void
    {
        $options = new ExportOptions(path: '/tmp');

        $this->assertEquals('/tmp', $options->path);
        $this->assertEquals('', $options->filename);
        $this->assertEmpty($options->formatOptions);
    }

    public function test_it_can_be_instantiated_with_all_parameters(): void
    {
        $customFormatOptions = [
            'delimiter' => ';',
            'includeHeaders' => false
        ];

        $options = new ExportOptions(
            path: '/tmp',
            filename: 'test.csv',
            formatOptions: $customFormatOptions
        );

        $this->assertEquals('/tmp', $options->path);
        $this->assertEquals('test.csv', $options->filename);
        $this->assertEquals($customFormatOptions, $options->formatOptions);
    }
}
