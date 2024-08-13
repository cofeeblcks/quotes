<?php

namespace App\Traits;

use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendQuoteEmail;
use App\Models\Company;
use App\Models\Quote;

trait NotificationQuote
{
    public function notificationEmailQuote(Quote $quote)
    {
        $consecutive = Str::padLeft($quote->consecutive, 6, '0');
        $subject = "Cotización Nº $consecutive - " . $quote->customer->name;

        $company = Company::find(1)->first();

        $dataEmail = [
            'customer' => $quote->customer->name,
            'company' => [
                'name' => $company->name,
                'phone' => $company->phone,
                'email' => $company->email,
            ]
        ];

        try {
            Mail::to($quote->customer)
            ->send(
                new SendQuoteEmail(
                    $subject,
                    $dataEmail,
                    [
                        Attachment::fromPath(public_path($quote->document_path))
                        ->as($quote->document_name . '.pdf')
                        ->withMime('application/pdf')
                    ]
                )
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
