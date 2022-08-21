<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $post = Post::pluck('creation_date', 'post_id');
        $randomPost = random_int($post->keys()->first(), $post->keys()->last());
        $creationDate = $this->faker->dateTimeBetween($post[$randomPost]);

        return [
            'text' => $this->faker->text(),
            'like' => $this->faker->randomNumber(2),
            'dislike' => $this->faker->randomNumber(2),
            'parent_id' => random_int(0, 50), // comments count
            'created_at' => $creationDate,
            'updated_at'  => $this->faker->dateTimeBetween($post[$randomPost], $creationDate->getTimestamp()),
            'post_id' => $randomPost
        ];
    }
}
