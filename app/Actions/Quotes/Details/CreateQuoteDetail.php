<?php

namespace App\Actions\Quotes\Details;

use App\Models\QuoteDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class CreateQuoteDetail
{
    public function execute(array $data): array
    {
        try {
            $quoteDetail = new QuoteDetail();

            $this->fillData($quoteDetail, $data);
            $quoteDetail->save();

            return [
                'success' => true,
                'message' => 'Detalle cotización creada exitosamente.',
                'quoteDetail' => $quoteDetail
            ];
        } catch (\Exception $e) {
            Log::channel('QuoteDetailError')->error("Message: {$e->getMessage()}, File: {$e->getFile()}, Line: {$e->getLine()}");
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function fillData(QuoteDetail $quoteDetail, array $data): void
    {
        foreach ($data as $key => $value) {
            $quoteDetail->{Str::snake($key)} = is_String($value) ? trim($value) : $value;
        }
    }
}
