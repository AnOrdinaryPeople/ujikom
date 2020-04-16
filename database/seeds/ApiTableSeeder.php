<?php

use App\Api;
use Illuminate\Database\Seeder;

class ApiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\User::find(2);
        $email = md5(Hash::make($user->email));

        Api::create([
            'user_id' => $user->id,
            'access_token' => md5(base64_encode(bin2hex($user->id)).$email.'.'.base64_encode(bin2hex($user->created_at))),
            'secret_token' => base64_encode(base64_encode(bin2hex(base64_encode(date('Y-m-d', strtotime($user->created_at)))).substr_replace($email, '-', 3, 1).bin2hex(base64_encode($user->id))))
        ]);
    }
}
