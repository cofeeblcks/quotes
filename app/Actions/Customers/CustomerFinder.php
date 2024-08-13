<?php

namespace App\Actions\Customers;

use App\Exceptions\Customers\CustomerNotFoundException;
use App\Models\Customer;

final class CustomerFinder
{
    /**
     * @throws CustomerNotFoundException
     */
    public function execute(int $CustomerId)
    {
        $Customer = Customer::find($CustomerId);

        if (is_null($Customer)) {
            throw new CustomerNotFoundException();
        }

        return $Customer;
    }
}
