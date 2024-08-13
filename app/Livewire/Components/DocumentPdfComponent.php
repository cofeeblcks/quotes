<?php

namespace App\Livewire\Components;

use App\Actions\Quotes\Pdf\CreatePdf;
use App\Actions\Quotes\QuoteFinder;
use App\Mail\SendQuoteEmail;
use App\Models\Company;
use App\Traits\WithToastNotifications;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class DocumentPdfComponent extends Component
{
    use WithToastNotifications;

    public bool $showModalViewPdf = false;
    public int $quoteConsecutive;
    public int $quoteId;
    public string $routeName;
    public string $urlPdf;

    public function mount(int $quoteId, string $routeName)
    {
        $this->quoteId = $quoteId;
        $this->routeName = $routeName;

        $quote = (new QuoteFinder())->execute($quoteId);

        (new CreatePdf())->execute($quote);

        $this->quoteConsecutive = $quote->consecutive;
        $this->urlPdf = asset($quote->document_path);

        $this->showModalViewPdf = !$this->showModalViewPdf;
    }

    public function render()
    {
        return view('livewire.components.document-pdf-component');
    }

    public function sendNotification()
    {
        $quote = (new QuoteFinder())->execute($this->quoteId);

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

        $this->showModalViewPdf = !$this->showModalViewPdf;
        $this->showSuccess('Cotización enviada', 'Se ha enviado la cotización exitosamente al cliente.', 5000);
    }
}
