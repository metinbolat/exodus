<?php

declare(strict_types=1);

namespace Metin\Exodus\Core;

use Exception;
use Metin\Exodus\Contracts\ExportStrategy;
use Metin\Exodus\Dto\ExportOptions;
use Metin\Exodus\Dto\ExportResult;
use Metin\Exodus\Exceptions\{
    DirectoryNotFoundException,
    DirectoryNotWritableException,
    EmptyDataException,
    ExodusException,
    InvalidDataStructureException
};
use Metin\Exodus\Enums\ExportFormat;

abstract class BaseExporter implements ExportStrategy
{
    abstract protected function handleExport(array $data, ExportOptions $options): ExportResult;

    abstract protected function getFormat(): ExportFormat;

    protected function validateFormatOptions(array $options): void
    {
        $format = $this->getFormat();

        $invalidOptions = array_diff(
            array_keys($options),
            $format->getAllowedOptions()
        );

        if (!empty($invalidOptions)) {
            throw new ExodusException(
                'Invalid export options: ' . implode(', ', $invalidOptions)
                . '. Allowed options: ' . implode(', ', $format->getAllowedOptions())
            );
        }
    }

    protected function getFormatOptions(array $options): array
    {
        return array_merge(
            $this->getFormat()->getDefaultOptions(),
            $options
        );
    }
    /**
     * Executes the export process, validating the export options and data before
     * delegating to the concrete implementation.
     *
     * @param array $data The data to be exported.
     * @param ExportOptions $options The export options.
     * @return ExportResult The export result.
     */
    public function export(array $data, ExportOptions $options): ExportResult
    {
        try {
            $this->validateDirectory($options->path);
            $this->validateDirectoryIsWritable($options->path);
            $this->validateDataIsNotEmpty($data);
            $this->validateDataStructure($data);

            return $this->handleExport($data, $options);
        } catch (ExodusException | Exception $e) {
            return new ExportResult(
                success: false,
                error: $e->getMessage()
            );
        }
    }

    protected function validateDirectory(string $path): void
    {
        if (!is_dir($path)) {
            throw new DirectoryNotFoundException($path);
        }
    }

    protected function validateDirectoryIsWritable(string $path): void
    {
        if (!is_writable($path)) {
            throw new DirectoryNotWritableException($path);
        }
    }

    protected function validateDataIsNotEmpty(array $data): void
    {
        if (empty($data)) {
            throw new EmptyDataException();
        }
    }

    protected function validateDataStructure(array $data): void
    {
        if (empty($data[0] || !is_array($data[0]))) {
            throw new InvalidDataStructureException('First row must be an array.');
        }

        $columns = array_keys($data[0]);

        foreach ($data as $index => $row) {
            if (!is_array($row)) {
                throw new InvalidDataStructureException(
                    'Row must be an array',
                    $index
                );
            }

            if (array_keys($row) !== $columns) {
                throw new InvalidDataStructureException(
                    'Row has different columns than the first row',
                    $index
                );
            }
        }
    }

    protected function buildFilePath(string $directory, string $filename): string
    {
        $extension = $this->getFormat()->getFileExtension();
        $filename = $this->generateFileName($filename, $extension);
        return rtrim($directory, '/') . '/' . $filename;
    }

    protected function generateFileName(?string $filename, string $extension): string
    {
        if (empty($filename)) {
            return 'export_' . time() . '.' . $extension;
        }

        return str_ends_with(strtolower($filename), '.' . $extension)
            ? $filename
            : $filename . '.' . $extension;
    }
}
