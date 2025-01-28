<?php

declare(strict_types=1);

namespace Metin\Exodus\Tests\Dto;

use Metin\Exodus\Dto\ExportResult;
use Metin\Exodus\Tests\TestCase;

class ExportResultTest extends TestCase
{
    public function test_it_can_represent_success_result(): void
    {
        $result = new ExportResult(
            success: true,
            path: '/path/to/file.csv',
            size: 1024
        );

        $this->assertTrue($result->success);
        $this->assertEquals('/path/to/file.csv', $result->path);
        $this->assertEquals(1024, $result->size);
        $this->assertNull($result->error);
    }

    public function test_it_can_represent_error_result(): void
    {
        $result = new ExportResult(
            success: false,
            error: 'Something went wrong'
        );

        $this->assertFalse($result->success);
        $this->assertNull($result->path);
        $this->assertNull($result->size);
        $this->assertEquals('Something went wrong', $result->error);
    }
}
