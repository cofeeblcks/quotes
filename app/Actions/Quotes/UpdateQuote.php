<?php

namespace App\Actions\Quotes;

use App\Actions\Quotes\Details\CreateQuoteDetail;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class UpdateQuote
{
    public function execute(int $quoteId, array $data): array
    {
        try {
            DB::beginTransaction();
            $quote = (new QuoteFinder())->execute($quoteId);

            $this->fillData($quote, $data['quote']);
            $quote->save();

            $total = 0;
            foreach ($data['details'] as $detail) {
                $detail['unitCost'] = Str::replace('.', '', $detail['unitCost']);

                $subTotal = $detail['quantity'] * $detail['unitCost'];
                $total += $subTotal;

                $quote->quoteDetails()->where('id', $detail['id'])->update(
                    [
                        'description' => $detail['description'],
                        'quantity' => $detail['quantity'],
                        'unit_cost' => $detail['unitCost'],
                    ]
                );
            }

            $quote->total = $quote->with_total ? $total : 0;
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
