<?php

use App\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for($i = 0; $i < 50; $i++){
            $type = rand(1, 3);
            $dt = Carbon::parse('-'.rand(1, 25).' days');

            $data[] = [
                'user_id' => rand(2, 27),
                'title' => str_replace('.', '', $faker->text(50)),
                'desc' => $faker->paragraph(rand(15, 30), true),
                'votes' => rand(1, 999),
                'type' => $type,
                'location' => $type === 3
                    ? str_replace('.', '', $faker->text(25))
                    : null,
                'created_at' => $dt,
                'updated_at' => $dt
            ];
        }
        Post::insert($data);
    }
}
