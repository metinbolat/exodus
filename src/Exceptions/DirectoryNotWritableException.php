<?php

declare(strict_types=1);

namespace Metin\Exodus\Exceptions;

use Throwable;

class DirectoryNotWritableException extends ExodusException
{
    /**
     * Creates a new DirectoryNotWritableException instance.
     *
     * @param string $directory The path to the directory that is not writable.
     * @param int $code The error code.
     * @param Throwable|null $previous The previous exception used for the exception chaining.
     */

    public function __construct(string $directory, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('Directory "%s" is not writable.', $directory);
        parent::__construct($message, $code, $previous);
    }
}
