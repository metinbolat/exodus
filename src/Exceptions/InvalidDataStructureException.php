<?php

declare(strict_types=1);

namespace Metin\Exodus\Exceptions;

use Throwable;

class InvalidDataStructureException extends ExodusException
{
    /**
     * Creates a new InvalidDataStructureException instance.
     *
     * @param string      $reason   A brief description of the invalid data structure.
     * @param int|null    $rowIndex The index of the row that contains the invalid data structure, if known.
     * @param int         $code     The error code.
     * @param Throwable|null $previous The previous exception.
     */
    public function __construct(string $reason, ?int $rowIndex = null, int $code = 0, ?Throwable $previous = null)
    {
        $message = $rowIndex !== null
            ? sprintf('Invalid data structure at row %d: %s', $rowIndex + 1, $reason)
            : sprintf('Invalid data structure: %s', $reason);
        parent::__construct($message, $code, $previous);
    }
}
