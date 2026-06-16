<?php

declare(strict_types=1);

namespace App\Exceptions;

use LogicException;

class InvalidAmountException  extends LogicException
{
    public function __construct() {
        parent::__construct();
    }
}
