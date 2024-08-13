<?php

namespace App\Exceptions\Quotes;

use Exception;

final class QuoteNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Quote not found.');
    }
}
