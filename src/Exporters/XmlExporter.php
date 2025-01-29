<?php

declare(strict_types=1);

namespace Metin\Exodus\Exporters;

use DOMDocument;
use Exception;
use Metin\Exodus\Core\BaseExporter;
use Metin\Exodus\Dto\ExportOptions;
use Metin\Exodus\Dto\ExportResult;
use Metin\Exodus\Enums\ExportFormat;
use Metin\Exodus\Exceptions\ExodusException;

class XmlExporter extends BaseExporter
{
    protected function handleExport(array $data, ExportOptions $options): ExportResult
    {
        try {
            $this->validateFormatOptions($options->formatOptions);
            $formatOptions = $this->getFormatOptions($options->formatOptions);

            $dom = new DOMDocument($formatOptions['version'], $formatOptions['encoding']);
            $dom->formatOutput = $formatOptions['prettyPrint'];

            $root = $dom->createElement($formatOptions['rootElement']);
            $dom->appendChild($root);

            foreach ($data as $row) {
                $item = $dom->createElement($formatOptions['itemElement']);

                foreach ($row as $key => $value) {
                    $element = $dom->createElement($key);
                    $element->appendChild($dom->createTextNode((string) $value));
                    $item->appendChild($element);
                }
                $root->appendChild($item);
            }

            $path = $this->buildFilePath(
                directory: $options->path,
                filename: $options->filename
            );

            if ($dom->save($path) === false) {
                throw new ExodusException('Failed to write file to disk');
            }

            return new ExportResult(
                success: true,
                path: $path,
                size: filesize($path)
            );
        } catch (ExodusException|Exception $e) {
            return new ExportResult(
                success: false,
                error: $e->getMessage()
            );
        }
    }

    protected function getFormat(): ExportFormat
    {
        return ExportFormat::XML;
    }
}
