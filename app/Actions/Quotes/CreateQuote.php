<?php

namespace App\Actions\Quotes;

use App\Actions\Customers\CreateCustomer;
use App\Actions\Quotes\Details\CreateQuoteDetail;
use App\Actions\Quotes\Pdf\CreatePdf;
use App\Enums\StatusEnum;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class CreateQuote
{
    public function execute(array $data): array
    {
        DB::beginTransaction();
        try {
            $quote = new Quote();

            if( is_string($data['quote']['customerId']) ){
                $response = (new CreateCustomer())->execute($data['customer']);

                if( !$response['success'] ){
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Error creando los datos del cliente',
                    ];
                }else{
                    $data['quote']['customerId'] = $response['customer']->id;
                }
            }
            $data['quote']['consecutive'] = $quote->nextConsecutive();
            $data['quote']['userId'] = Auth::user()->id;
            $data['quote']['statusId'] = StatusEnum::REGISTER;

            $this->fillData($quote, $data['quote']);
            $quote->save();

            $total = 0;
            foreach ($data['details'] as $detail) {
                $detail['quoteId'] = $quote->id;
                $detail['unitCost'] = Str::replace('.', '', $detail['unitCost']);

                $subTotal = $detail['quantity'] * $detail['unitCost'];
                $total += $subTotal;

                (new CreateQuoteDetail)->execute($detail);
            }

            $quote->total = $total;
            $quote->save();

            (new CreatePdf())->execute($quote);

            DB::commit();
            return [
                'success' => true,
                'message' => 'Cotización creada exitosamente.',
                'quote' => $quote
            ];
        } catch (\Exception $e) {
            DB::rollBack();
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
            $quote->{Str::snake($key)} = is_String($value) ? trim($value) : $value;
        }
    }
}
