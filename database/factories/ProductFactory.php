<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word . ' '. $this->faker->randomLetter . '_'.  $this->faker->randomNumber(2),
            'article' => $this->faker->hexColor,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'quantity' => $this->faker->randomDigit()
        ];
    }
}
