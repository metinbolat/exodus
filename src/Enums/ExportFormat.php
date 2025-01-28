<?php

namespace Metin\Exodus\Enums;

enum ExportFormat: string
{
    case CSV = 'csv';
    case JSON = 'json';
    case XML = 'xml';

    public function getAllowedOptions(): array
    {
        return match ($this) {
            self::CSV => [
                'delimiter',
                'includeHeaders'
            ],

            // TODO: Add JSON and XML options
        };
    }

    public function getDefaultOptions(): array
    {
        return match ($this) {
            self::CSV => [
                'delimiter' => ',',
                'includeHeaders' => true
            ],
        };
    }

    public function getFileExtension(): string
    {
        return $this->value;
    }
}
