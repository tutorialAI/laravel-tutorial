<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Вызов дополнительных наполнителей
        $this->call([
//            UserSeeder::class,
//            PostSeeder::class,
//            CommentSeeder::class,
            ProductSeeder::class
        ]);
    }
}
