<?php

namespace App\Exceptions;

use Exception;

class CompanyNotFoundException extends Exception {
    public function __construct(mixed $uuid) {
        parent::__construct(sprintf("Company with uuid %s not found", $uuid));
    }
}
