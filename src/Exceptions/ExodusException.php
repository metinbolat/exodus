<?php

declare(strict_types=1);

namespace Metin\Exodus\Exceptions;

use Exception;
use Throwable;

class ExodusException extends Exception
{
    /**
     * Constructs a new ExodusException instance.
     *
     * @param string $message The exception message. Defaults to an empty string.
     * @param int $code The exception code. Defaults to 0.
     * @param Throwable|null $previous The previous throwable used for exception chaining. Defaults to null.
     */
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
