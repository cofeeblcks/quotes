<?php

namespace App\Livewire\Components;

use App\Actions\Quotes\Pdf\CreatePdf;
use App\Actions\Quotes\QuoteFinder;
use App\Traits\NotificationQuote;
use App\Traits\WithToastNotifications;
use Livewire\Component;

class DocumentPdfComponent extends Component
{
    use WithToastNotifications;
    use NotificationQuote;

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

        $this->notificationEmailQuote($quote);

        $this->showModalViewPdf = !$this->showModalViewPdf;
        $this->showSuccess('Cotización enviada', 'Se ha enviado la cotización exitosamente al cliente.', 5000);
    }
}
