<?php

namespace App\Actions\Customers;

use Illuminate\Support\Facades\Log;

final class DeleteCustomer
{
    public function execute(int $customerId): array
    {
        try {
            $customer = (new CustomerFinder())->execute($customerId);

            $customer->delete();

            return [
                'success' => true,
                'message' => 'Cliente eliminado exitosamente.'
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
