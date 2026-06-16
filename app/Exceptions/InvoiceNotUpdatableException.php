<?php

declare(strict_types=1);

namespace App\Exceptions;

use LogicException;

class InvoiceNotUpdatableException extends LogicException
{
    public function __construct(
        public readonly string $id
    ) {
        parent::__construct();
    }
}
