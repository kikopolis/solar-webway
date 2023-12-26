<?php

namespace App\Exceptions;

use Exception;

class PriceListExpiredException extends Exception {
    public function __construct(string $uuid) {
        parent::__construct(sprintf("Price list not found or expired. UUID: %s.", $uuid));
    }
}
