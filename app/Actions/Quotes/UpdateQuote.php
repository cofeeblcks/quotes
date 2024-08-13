<?php

namespace App\Actions\Quotes;

use App\Models\Quote;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class UpdateQuote
{
    public function execute(int $quoteId, array $dataBasic, array $dataInformations = [], array $dataAims = []): array
    {
        DB::beginTransaction();
        try {
            $quote = (new QuoteFinder())->execute($quoteId);

            $this->fillData($quote, $dataBasic);
            $quote->save();

            DB::commit();
            return [
                'success' => true,
                'message' => 'Cotización actualizada exitosamente.',
                'quote' => $quote
            ];
        } catch (\Exception $e) {
            Log::channel('QuoteError')->error("Message: {$e->getMessage()}, File: {$e->getFile()}, Line: {$e->getLine()}");

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function fillData(Quote $quote, array $data): void
    {
        foreach ($data as $key => $value) {
            $quote->{Str::snake($key)} = $value;
        }
    }
}
