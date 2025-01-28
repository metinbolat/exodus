<?php

declare(strict_types=1);

namespace Metin\Exodus\Core;

use Metin\Exodus\Contracts\ExportStrategy;
use Metin\Exodus\Dto\ExportOptions;
use Metin\Exodus\Dto\ExportResult;

class ExportManager
{
    /**
     * Initializes the export manager with the given export strategy.
     *
     * @param  ExportStrategy  $strategy
     */
    public function __construct(
        private readonly ExportStrategy $strategy
    ) {}
    /**
     * Process the given data array and export it using the configured strategy.
     *
     * @param  array  $data
     * @param  ExportOptions  $options
     * @return ExportResult
     */
    public function process(array $data, ExportOptions $options): ExportResult
    {
        return $this->strategy->export($data, $options);
    }
}
