<?php

declare(strict_types=1);

namespace Metin\Exodus\Contracts;

use Metin\Exodus\Dto\ExportOptions;
use Metin\Exodus\Dto\ExportResult;

interface ExportStrategy
{
    public function export(array $data, ExportOptions $options): ExportResult;
}
