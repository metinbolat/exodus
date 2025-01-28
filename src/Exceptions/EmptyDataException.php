<?php

declare(strict_types=1);

namespace Metin\Exodus\Exceptions;

use Throwable;

class EmptyDataException extends ExodusException
{
    /**
     * Initializes the exception with an optional code and previous exception.
     *
     * @param int $code The exception code.
     * @param Throwable|null $previous The previous throwable used for exception chaining.
     */
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Data cannot be empty', $code, $previous);
    }
}
