<?php

declare(strict_types=1);

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvoiceNotFoundException extends NotFoundHttpException
{
    public function __construct(
        public readonly string $id
    ) {
        parent::__construct();
    }
}
