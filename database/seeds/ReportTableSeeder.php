<?php

use App\Report;
use Illuminate\Database\Seeder;

class ReportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $totalPost = \App\Post::count();
        $totalUser = \App\User::count();

        for($i = 0; $i < 15; $i++){
            $post = rand(1, $totalPost);
            $dt = \Illuminate\Support\Carbon::parse('-'.rand(1, 10).' days');
            $t = rand(1, 2);

            if($t === 1) $s = $post;
            else{
                $db = \App\Reply::where('post_id', $post)->get();
                $s = $db[rand(0, count($db) - 1)]->user_id;
            }

            $data[] = [
                'type' => $t,
                'suspect_id' => $s,
                'desc' => Faker\Factory::create()->paragraph(1, 5),
                'user_id' => rand(2, $totalUser),
                'created_at' => $dt,
                'updated_at' => $dt
            ];
        }
        Report::insert($data);
    }
}
