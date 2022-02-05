<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\DateFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Date extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'processed_dates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_hash',
        'date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    protected static function newFactory(): Factory
    {
        return DateFactory::new();
    }
}
