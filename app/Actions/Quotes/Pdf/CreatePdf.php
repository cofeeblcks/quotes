<?php

namespace App\Actions\Quotes\Pdf;

use App\Models\Company;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class CreatePdf
{
    public function execute(Quote $quote): bool
    {
        $company = Company::find(1)->first();

        $path = storage_path('app/public/documents/quotes/' . $quote->consecutive);

        if( !File::isDirectory($path) ){
            File::makeDirectory($path, 0755, true);
        }

        $namePdf = $quote->document_name ?? uniqid() . '.pdf';
        try {
            $pdf = Pdf::loadView('pdf.quote', [
                'customer' => [
                    'name' => $quote->customer->name,
                    'phone' => $quote->customer->phone ?? '-',
                    'email' => $quote->customer->email ?? '-',
                    'address' => $quote->customer->address ?? '-',
                ],
                'quote' => [
                    'consecutive' => Str::padLeft($quote->consecutive, 6, '0'),
                    'description' => $quote->description,
                    'dateQuote' => $quote->date_quote,
                    'total' => $quote->with_total ? $quote->total : null,
                    'details' => $quote->quoteDetails->map(function($detail){
                        return [
                            'description' => $detail->description,
                            'quantity' => $detail->quantity,
                            'unitCost' => $detail->unit_cost,
                        ];
                    })->toArray(),
                ],
                'company' => [
                    'name' => $company->name,
                    'phone' => $company->phone,
                    'email' => $company->email,
                    'address' => $company->address,
                    'signature' => $company->signature_path,
                ]
            ])
            ->setPaper('A4');

            $pdf->save($path . '/' . $namePdf);

            $quote->document_path = 'documents/quotes/' . $quote->consecutive . '/' . $namePdf;
            $quote->document_name = $namePdf;
            $quote->save();

            return true;
        } catch (\Exception $e) {
            Log::channel('PdfError')->error("Message: {$e->getMessage()}, File: {$e->getFile()}, Line: {$e->getLine()}");
            return false;
        }
    }
}
