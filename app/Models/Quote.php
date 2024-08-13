<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'description',
        'date_quote',
        'total',
        'consecutive',
        'customer_id',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'date'
    ];

    private $monthSpanish = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quoteDetails(): HasMany
    {
        return $this->hasMany(QuoteDetail::class);
    }

    public function getCreatedAtAttribute($value)
    {
        $createdAt = Carbon::parse($value);
        return $createdAt->format('d') . ' de ' . $this->monthSpanish[($createdAt->format('n')) - 1] . ' de ' . $createdAt->format('Y');
    }

    public function getDateQuoteAttribute($value)
    {
        $createdAt = Carbon::parse($value);
        return $createdAt->format('d') . ' de ' . $this->monthSpanish[($createdAt->format('n')) - 1] . ' de ' . $createdAt->format('Y');
    }

    /**
     * Retorna el siguiente consecutivo de la cotización
     * @author Hadik Chavez (ChivoDev) -  CofeeBlcks <cofeeblcks@gmail.com, chavezhadik@gmail.com>
     * @return int
     */
    public function nextConsecutive(): int
    {
        $consecutive = $this->get('consecutive')->last();
        if( $consecutive ){
            return $consecutive->consecutive + 1;
        }
        return 1;
    }

    /**
     * Scope para filtrar la busqueda por titulo
     * @author Hadik Chavez (ChivoDev) -  CofeeBlcks <cofeeblcks@gmail.com, chavezhadik@gmail.com>
     * @param Builder $builder
     * @param string $search
     * @return object
     */
    public function scopeSearch(Builder $builder, string $search = null)
    {
        if ($search) {
            $searchCustomer = '%' . $search . '%';
            return $builder->whereRelation('customer', 'customers.name', 'like', $searchCustomer)
                ->orWhere('consecutive', $search);
        }
        return $builder;
    }
}
