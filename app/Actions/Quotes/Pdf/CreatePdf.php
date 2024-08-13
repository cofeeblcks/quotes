<?php

namespace App\Actions\Quotes\Pdf;

use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

final class CreatePdf
{
    public function execute(Quote $quote): bool
    {
        $path = storage_path('app/public/documents/quotes/' . $quote->consecutive);

        if( !File::isDirectory($path) ){
            File::makeDirectory($path, 0755, true);
        }

        $namePdf = $quote->document_name ?? uniqid() . '.pdf';
        try {
            $pdf = Pdf::loadView('pdf.quote', [
                'customer' => [
                    'code' => $quote->prefix.$quote->sufix,
                    'schoolName' => $quote->academicSchool->name,
                    'titleModalityStage' => 'PROPUESTA DE INVESTIGACIÓN TRABAJOS DE GRADO'
                ],
                'quote' => [
                    'title' => $quote->title,
                    'details' => [],
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
