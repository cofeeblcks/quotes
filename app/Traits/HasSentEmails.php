<?php

namespace App\Traits;

use App\Enums\SentMailTypesEnum;
use App\Models\SentEmail;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasSentEmails
{
    public function sentEmails(): MorphMany
    {
        return $this->morphMany(SentEmail::class, 'model');
    }

    public function logSentEmail(string $recipientTo, string $subject, array $body, string $recipientCc = null, SentMailTypesEnum $type = SentMailTypesEnum::NORMAL)
    {
        return $this->sentEmails()->create([
            'recipient_to'=> $recipientTo,
            'recipient_cc_to' => $recipientCc,
            'subject' => $subject,
            'body' => $body,
            'type' => $type,
        ]);
    }
}
