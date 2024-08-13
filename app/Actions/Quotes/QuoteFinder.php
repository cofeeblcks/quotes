<?php

namespace App\Actions\Quotes;

use App\Exceptions\Quotes\QuoteNotFoundException;
use App\Models\Quote;

final class QuoteFinder
{
    /**
     * @throws QuoteNotFoundException
     */
    public function execute(int $quoteId)
    {
        $quote = Quote::find($quoteId);

        if (is_null($quote)) {
            throw new QuoteNotFoundException();
        }

        return $quote;
    }
}
