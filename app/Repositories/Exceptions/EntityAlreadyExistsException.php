<?php

declare(strict_types=1);

namespace App\Repositories\Exceptions;

use RuntimeException;
use Throwable;

class EntityAlreadyExistsException extends RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function isInDatabases(string $message): self
    {
        return new self(sprintf('Entity: %s already exists in database.', $message));
    }
}
