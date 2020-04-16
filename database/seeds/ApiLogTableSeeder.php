<?php

use App\ApiLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ApiLogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sauce = [
            '/api/user',
            '/api/user/rand',
            '/api/post',
            '/api/post/rand',
            '/api/comment',
            '/api/comment/rand'
        ];

        for($i = 0; $i < 25; $i++){
            $dt = Carbon::parse('-'.rand(1, 25).' days');

            $data[] = [
                'api_id' => 1,
                'description' => url($sauce[rand(0, count($sauce) - 1)]),
                'status' => rand(0, 1),
                'created_at' => $dt,
                'updated_at' => $dt
            ];
        }

        ApiLog::insert($data);
    }
}
