<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class StockPrice extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'stock_prices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_hash',
        'stock_id',
        'price_date',
        'isin',
        'currency',
        'market_place',
        'list_segment',
        'average_price',
        'open_price',
        'high_price',
        'low_price',
        'last_close_price',
        'last_price',
        'price_change',
        'best_bid',
        'best_ask',
        'trades',
        'volume',
        'turnover',
        'industry',
        'supersector'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'ticker', 'stock_id');
    }
}
