<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'description',
        'quantity',
        'unit_cost',
        'quote_id'
    ];
}
