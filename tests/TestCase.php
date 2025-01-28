<?php

declare(strict_types=1);


namespace Metin\Exodus\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getTestDataPath(): string
    {
        return __DIR__ . '/data';
    }

    protected function getTestOutputPath(): string
    {
        return __DIR__ . '/output';
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (!is_dir($this->getTestDataPath())) {
            mkdir($this->getTestDataPath(), 0777, true);
        }

        if (!is_dir($this->getTestOutputPath())) {
            mkdir($this->getTestOutputPath(), 0777, true);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cleanDirectory($this->getTestOutputPath());
    }

    private function cleanDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $files = glob($directory . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
