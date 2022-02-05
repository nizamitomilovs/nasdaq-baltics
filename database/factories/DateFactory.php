<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class DateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, string>
     */
    public function definition()
    {
        return [
            'id_hash' => mb_substr(md5(uniqid('', true)), 0, 28),
            'date' => Carbon::createFromFormat('Y-m-d', '2022-02-05'),
        ];
    }
}
