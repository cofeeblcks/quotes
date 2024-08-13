<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address'
    ];

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
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
            $search = '%' . $search . '%';
            return $builder->where('name', 'like', $search);
        }
        return $builder;
    }
}
