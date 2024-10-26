<?php

namespace App\Actions\Customers;

use App\Exceptions\Customers\CustomerNotFoundException;
use App\Models\Customer;

final class CustomerFinder
{
    /**
     * @throws CustomerNotFoundException
     */
    public function execute(int $customerId)
    {
        $Customer = Customer::find($customerId);

        if (is_null($Customer)) {
            throw new CustomerNotFoundException();
        }

        return $Customer;
    }
}
