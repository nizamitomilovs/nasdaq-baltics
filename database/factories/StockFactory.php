<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, string>
     */
    public function definition(): array
    {
        return [
            'id_hash' => mb_substr(md5(uniqid('', true)), 0, 28),
            'ticker' => 'AMG1L',
            'name' => 'Amber Grid',
        ];
    }
}
