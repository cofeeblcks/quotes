<?php

namespace App\Actions\Quotes;

use Illuminate\Support\Facades\Log;

final class DeleteQuote
{
    public function execute(int $quoteId): array
    {
        try {
            $quote = (new QuoteFinder())->execute($quoteId);

            $quote->delete();

            return [
                'success' => true,
                'message' => 'Cotización eliminada exitosamente.'
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
