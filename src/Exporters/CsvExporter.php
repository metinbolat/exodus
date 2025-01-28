<?php

declare(strict_types=1);

namespace Metin\Exodus\Exporters;

use Exception;
use Metin\Exodus\Core\BaseExporter;
use Metin\Exodus\Dto\ExportOptions;
use Metin\Exodus\Dto\ExportResult;
use Metin\Exodus\Enums\ExportFormat;
use Metin\Exodus\Exceptions\ExodusException;

class CsvExporter extends BaseExporter
{
    /**
     * Exports the given data to a CSV file.
     *
     * @param array $data The data to be exported.
     * @param ExportOptions $options The export options.
     *
     * @return ExportResult The export result.
     *
     * @throws ExodusException
     */
    protected function handleExport(array $data, ExportOptions $options): ExportResult
    {
        try {
            $this->validateFormatOptions($options->formatOptions);
            $formatOptions = $this->getFormatOptions($options->formatOptions);

            $handle = fopen('php://temp', 'w+');
            if ($handle === false) {
                throw new ExodusException('Failed to create temporary file');
            }

            try {
                if ($formatOptions['includeHeaders'] && !empty($data)) {
                    if (fputcsv($handle, array_keys($data[0]), $formatOptions['delimiter']) === false) {
                        throw new ExodusException('Failed to write headers');
                    }
                }

                foreach ($data as $row) {
                    if (fputcsv($handle, $row, $formatOptions['delimiter']) === false) {
                        throw new ExodusException('Failed to write data row');
                    }
                }

                rewind($handle);
                $content = stream_get_contents($handle);
                if ($content === false) {
                    throw new ExodusException('Failed to read temporary file');
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
            } finally {
                fclose($handle);
            }
        } catch (ExodusException|Exception $e) {
            return new ExportResult(
                success: false,
                error: $e->getMessage()
            );
        }
    }

    protected function getFormat(): ExportFormat
    {
        return ExportFormat::CSV;
    }
}
