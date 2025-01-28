<?php

declare(strict_types=1);

namespace Metin\Exodus\Exporters;

use Exception;
use Metin\Exodus\Core\BaseExporter;
use Metin\Exodus\Dto\ExportOptions;
use Metin\Exodus\Dto\ExportResult;
use Metin\Exodus\Enums\ExportFormat;
use Metin\Exodus\Exceptions\ExodusException;

class JsonExporter extends BaseExporter
{
    protected function handleExport(array $data, ExportOptions $options): ExportResult
    {
        try {
            $this->validateFormatOptions($options->formatOptions);
            $formatOptions = $this->getFormatOptions($options->formatOptions);

            $flags = 0;
            if ($formatOptions['prettyPrint']) {
                $flags |= JSON_PRETTY_PRINT;
            }
            if ($formatOptions['unescapeUnicode']) {
                $flags |= JSON_UNESCAPED_UNICODE;
            }

            $content = json_encode($data, $flags);
            if ($content === false) {
                throw new ExodusException('Failed to encode data to JSON: ' . json_last_error_msg());
            }

            $path = $this->buildFilePath($options->path, $options->filename);
            if (file_put_contents($path, $content) === false) {
                throw new ExodusException('Failed to write file to disk');
            }

            return new ExportResult(
                success: true,
                path: $path,
                size: filesize($path)
            );
        } catch (ExodusException | Exception $e) {
            return new ExportResult(
                success: false,
                error: $e->getMessage()
            );
        }
    }

    protected function getFormat(): ExportFormat
    {
        return ExportFormat::JSON;
    }
}
