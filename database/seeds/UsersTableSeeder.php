<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $a = Carbon::parse('-27 days');
        $b = Carbon::parse('-20 days');

        $data = [
            [
                'name' => 'Admyn',
                'email' => 'admin@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 1,
                'created_at' => $a,
                'updated_at' => $a
            ], [
                'name' => 'Uzerr',
                'email' => 'user@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role' => 0,
                'created_at' => $a,
                'updated_at' => $a
            ]
        ];

        for($i = 0; $i < 25; $i++){
            $dt = Carbon::parse('-'.rand(1, 25).' days');

            $data[] = [
                'name' => Faker\Factory::create()->name,
                'email' => 'user'.($i + 1).'@mail.com',
                'email_verified_at' => null,
                'password' => Hash::make('12345678'),
                'role' => 0,
                'created_at' => $dt,
                'updated_at' => $dt
            ];
        }
        User::insert($data);
    }
}
