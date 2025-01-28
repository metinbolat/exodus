<?php

declare(strict_types=1);

namespace Metin\Exodus\Dto;

readonly class ExportResult
{
    /**
     * @param bool $success Indicates whether the export was successful.
     * @param string|null $path The path to the exported file, if the export was successful.
     * @param string|null $error The error message, if the export failed.
     * @param int|null $size The size of the exported file in bytes, if the export was successful.
     */
    public function __construct(
        public readonly bool    $success,
        public readonly ?string $path = null,
        public readonly ?string $error = null,
        public readonly ?int    $size = null
    ) {
    }
}
