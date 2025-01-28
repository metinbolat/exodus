<?php

declare(strict_types=1);

namespace Metin\Exodus\Dto;

readonly class ExportOptions
{
    /**
     * @param string $path Export edilecek dizin
     * @param string $filename Dosya adı (Boş ise otomatik oluşturulur)
     * @param array<string, mixed> $formatOptions Format'a özel opsiyonlar
     */
    public function __construct(
        public readonly string $path,
        public readonly string $filename = '',
        public readonly array  $formatOptions = []
    )
    {
    }
}
