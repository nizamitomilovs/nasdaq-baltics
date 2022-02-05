<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Stock extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'stocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_hash',
        'ticker',
        'name',
    ];

    /**
     * @var array<string>
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(StockPrice::class, 'stock_id', 'ticker');
    }
}
