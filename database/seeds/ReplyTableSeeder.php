<?php

use App\Post;
use App\Reply;
use Illuminate\Database\Seeder;

class ReplyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for($i = 1; $i <= 50; $i++){
            for($j = 0; $j < rand(6, 21); $j++){
                $data[] = [
                    'post_id' => $i,
                    'user_id' => rand(2, 27),
                    'desc' => $faker->paragraph(rand(10, 15)),
                    'votes' => rand(10, 999),
                    'is_best' => $j === 0
                        ? (Post::where('id', $i)->where('type', 1)->count() ? 1 : 0)
                        : 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        Reply::insert($data);

        for($i = 1; $i <= 50; $i++){
            if(rand(1, 50) > rand(1, 50)){
                for($j = 0; $j < rand(3, 5); $j++){
                    $c = Reply::where('post_id', $i)->get();
                    $dataa[] = [
                        'post_id' => $i,
                        'user_id' => rand(2, 27),
                        'desc' => $faker->paragraph(rand(5, 10)),
                        'votes' => rand(10, 100),
                        'is_best' => 0,
                        'has_child_id' => $c[rand(0, count($c) - 1)]['id'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }
        Reply::insert($dataa);
    }
}
