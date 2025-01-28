<?php

declare(strict_types=1);

namespace Metin\Exodus\Exceptions;

use Throwable;

class DirectoryNotFoundException extends ExodusException
{
    /**
     * Initializes the exception with the given directory.
     *
     * @param string $directory The directory path that does not exist.
     * @param int $code The exception code.
     * @param Throwable|null $previous The previous exception used for chaining.
     */
    public function __construct(string $directory, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('Directory "%s" does not exist.', $directory);
        parent::__construct($message, $code, $previous);
    }
}
