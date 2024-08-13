<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class CreateCustomer
{
    public function execute(array $data): array
    {
        DB::beginTransaction();
        try {
            $customer = new Customer();

            $this->fillData($customer, $data);
            $customer->save();

            DB::commit();
            return [
                'success' => true,
                'message' => 'Cotización creada exitosamente.',
                'customer' => $customer
            ];
        } catch (\Exception $e) {
            Log::channel('CustomerError')->error("Message: {$e->getMessage()}, File: {$e->getFile()}, Line: {$e->getLine()}");

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function fillData(Customer $customer, array $data): void
    {
        foreach ($data as $key => $value) {
            $customer->{Str::snake($key)} = is_String($value) ? trim($value) : $value;
        }
    }
}
