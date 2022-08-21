<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $creationDate = $this->faker->dateTimeBetween('-5 years');
        $users = User::pluck('id');

        return [
            'title' => $this->faker->text(50),
            'description' => $this->faker->text(150),
            'creation_date' => $creationDate,
            'updated_date'  => $this->faker->dateTimeBetween('-5 years', $creationDate->getTimestamp()),
            'user_id' => $this->faker->randomElement($users)
        ];
    }
}
