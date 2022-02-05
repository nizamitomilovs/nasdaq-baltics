<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StockPriceFactory extends Factory
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
            'stock_id' => 'AMG1L',
            'name' => 'Amber Grid',
            'price_date' => '2022-02-05',
            'isin' => $this->faker->randomAscii,
            'currency' => $this->faker->randomAscii,
            'market_place' => $this->faker->randomAscii,
            'list_segment' => $this->faker->randomAscii,
            'average_price' => $this->faker->randomFloat(),
            'open_price' => $this->faker->randomFloat(),
            'high_price' => $this->faker->randomFloat(),
            'low_price' => $this->faker->randomFloat(),
            'last_close_price' => $this->faker->randomFloat(),
            'last_price' => $this->faker->randomFloat(),
            'price_change' => $this->faker->randomFloat(),
            'best_bid' => $this->faker->randomFloat(),
            'best_ask' => $this->faker->randomFloat(),
            'trades' => $this->faker->randomAscii,
            'volume' => $this->faker->randomAscii,
            'turnover' => $this->faker->randomAscii,
            'industry' => $this->faker->randomAscii,
            'supersector' => $this->faker->randomAscii,
        ];
    }
}
