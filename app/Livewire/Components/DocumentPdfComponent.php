<?php

namespace App\Livewire\Components;

use App\Actions\Quotes\Pdf\CreatePdf;
use App\Actions\Quotes\QuoteFinder;
use App\Traits\WithToastNotifications;
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
}
