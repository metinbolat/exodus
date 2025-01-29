<?php

declare(strict_types=1);

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
                'includeHeaders',
            ],
            self::JSON => [
                'prettyPrint',
                'unescapeUnicode',
            ],
            self::XML => [
                'rootElement',
                'itemElement',
                'prettyPrint',
                'version',
                'encoding',
            ],
        };
    }

    public function getDefaultOptions(): array
    {
        return match ($this) {
            self::CSV => [
                'delimiter' => ',',
                'includeHeaders' => true,
            ],
            self::JSON => [
                'prettyPrint' => true,
                'unescapeUnicode' => true,
            ],
            self::XML => [
                'rootElement' => 'root',
                'itemElement' => 'item',
                'prettyPrint' => true,
                'version' => '1.0',
                'encoding' => 'UTF-8',
            ],
        };
    }

    public function getFileExtension(): string
    {
        return $this->value;
    }
}
