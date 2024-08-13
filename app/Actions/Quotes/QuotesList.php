<?php

namespace App\Actions\Quotes;

use App\Models\Quote;
use App\Traits\WithActionListBasicStart;
use Illuminate\Support\Facades\Log;

final class QuotesList
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
            $quotes = Quote::query()
                ->search($filter)
                ->orderBy('created_at', 'desc');

            return [
                'success' => true,
                'quotes' => $this->run($quotes)
            ];
        } catch (\Exception $e) {
            Log::channel('QuoteError')->error("Message: {$e->getMessage()}, File: {$e->getFile()}, Line: {$e->getLine()}");

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
