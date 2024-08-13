<?php

namespace App\Exceptions\Customers;

use Exception;

final class CustomerNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Customer not found.');
    }
}
