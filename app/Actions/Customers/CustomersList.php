<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use App\Traits\WithActionListBasicStart;
use Illuminate\Support\Facades\Log;

final class CustomersList
{
    use WithActionListBasicStart;

    /**
     * Listado de proyectos
     * @author Hadik Chavez (ChivoDev) -  CofeeBlcks <cofeeblcks@gmail.com, chavezhadik@gmail.com>
     * @param string|null $filter
     * @return array
     */
    public function execute(?string $filter = null): array
    {
        try {
            $customers = Customer::query()
                ->search($filter);

            return [
                'success' => true,
                'customers' => $this->run($customers)
            ];
        } catch (\Exception $e) {
            Log::channel('CustomerError')->error("Message: {$e->getMessage()}, File: {$e->getFile()}, Line: {$e->getLine()}");

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
