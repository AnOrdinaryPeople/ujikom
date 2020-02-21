<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class OutController extends Controller
{
    /**
     * Redirect to selected provider.
     *
     * @param  string $provider
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider){
    	return response()->json([
            'uri' => Socialite::driver($provider)
                ->stateless()
                ->redirect()
                ->getTargetUrl()
        ]);
    }

    /**
     * Receive data from selected provider.
     *
     * @param  mixed $provider
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function handleProviderCallback($provider){
    	$user = Socialite::driver($provider)->stateless()->user();
        $email = empty($user->email) ? $user->id.'@efbih.rpl' : $user->email;
        $pass = Hash::make($user->email.$user->id);

    	if(!User::where('email', $email)->first()) User::create([
	    	'email_verified_at' => Carbon::now(),
	    	'provider_id' => $user->id,
	    	'name' => $user->name,
	    	'email' => $email,
	    	'avatar' => $user->avatar,
            'password' => $pass
        ]);
        else User::where('email', $email)->update([
            'name' => $user->name,
            'email' => $email,
            'password' => $pass,
            'avatar' => $user->avatar
        ]);

    	return redirect('/login?token='.base64_encode(json_encode(['email' => $email, 'pass' => $user->email.$user->id])));
    }
}
