<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {  // Generates 10 random blogs
            DB::table('blogs')->insert([
                'title' => $faker->sentence,
                'image' => $faker->imageUrl(), // Generates random image URL
                'description' => $faker->paragraph,
                'user_id' => 1,  // You can change this to match any user_id in your users table
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}