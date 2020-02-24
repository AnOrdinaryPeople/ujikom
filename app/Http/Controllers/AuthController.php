<?php

namespace App\Http\Controllers;

use App\User;
use App\Rules\Recaptcha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create user account.
     *
     * @param  \Illuminate\Http\Request $req
     * @param  \App\Rules\Recaptcha $recap
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $req, Recaptcha $recap){
    	$check = Validator::make($req->all(), [
            'name' => 'required',
    		'email' => 'required|email|unique:users',
    		'password' => 'required|min:8|confirmed',
            'recaptcha' => ['required', $recap]
    	]);

    	if($check->fails())
    		return response()->json([
	    		'status' => 'error',
	    		'errors' => $check->errors()
	    	], 422);
    	else{
			$token = Hash::make($req->email);

    		User::create([
                'name' => $req->name,
    			'email' => $req->email,
				'password' => Hash::make($req->password),
				'token' => $token
			]);

			Mail::to($req->email)->send(new \App\Mail\EmailContent([
				'name' => $req->name,
				'token' => base64_encode($token)
			]));

    		return response()->json(['status' => 'success']);
    	}
	}

    /**
     * Authenticate user.
     *
     * @param  \Illuminate\Http\Request $req
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $req){
    	$token = Auth::guard()->attempt($req->only('email', 'password'));

    	if($token) return response()->json(['status' => 'success'])
    		->header('Authorization', $token);
    	else return response()->json(['status' => 'error'], 401);
	}

	/**
	 * Destroy authentication user.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function logout(){
    	Auth::guard()->logout();

    	return response()->json([
    		'status' => 'success',
    		'msg' => 'Berhasil logout'
    	]);
    }

	/**
     * Get user data.
     *
     * @param  \Illuminate\Http\Request $req
     *
     * @return \Illuminate\Http\Response
     */
    public function user(Request $req){
    	return response()->json([
    		'status' => 'success',
    		'data' => User::find(Auth::user()->id)
    	]);
	}

    /**
     * Refresh user auth token.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh(){
    	if($token = Auth::guard()->refresh()) return response()
            ->json(['status' => 'success'], 200)
            ->header('Authorization', $token);
        else return response()->json(['status' => 'refresh_token_error'], 401);
	}

	/**
	 * Locate verification token.
	 *
	 * @param  \Illuminate\Http\Request $req
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function verify(Request $req){
		if($check = User::where('token', base64_decode($req->token))->first()){
			User::find($check->id)->update([
				'email_verified_at' => now(),
				'token' => null
			]);

			return response()->json(['count' => 1]);
		}else
			return response()->json(['count' => 0]);
	}

	/**
	 * Create request reset password.
	 *
	 * @param  \Illuminate\Http\Request $req
	 * @param  \App\Rules\Recaptcha $recap
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function forgot(Request $req, Recaptcha $recap){
		$check = Validator::make($req->all(), [
    		'email' => 'required',
            'recaptcha' => ['required', $recap]
    	]);

		if($check->fails()) return response()->json($check->errors(), 422);
		else{
			if($user = User::where('email', $req->email)->first()){
				$e = Hash::make($req->email);
	
				User::makeForgot($req->email, $e);
	
				Mail::to($req->email)->send(new \App\Mail\ForgotContent([
					'name' => $user->name,
					'token' => base64_encode($e)
				]));
	
				return response()->json(['msg' => true]);
			}else
				return response() ->json(['email' => ['Email not found']], 422);
		}
	}

	/**
	 * Locate reset password token.
	 *
	 * @param  \Illuminate\Http\Request $req
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function resetCheck(Request $req){
		$token = base64_decode($req->token);

		if($find = User::findForgot($token)){
			if(strtotime(now()) - strtotime($find->created_at) > 3600){
				User::deleteForgot($token);

				return response()->json(['count' => 0]);
			}
			else return response()->json(['count' => 1]);
		}else
			return response()->json(['count' => 0]);
	}

	/**
	 * Reset password user.
	 *
	 * @param  \Illuminate\Http\Request $req
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function reset(Request $req){
		$check = Validator::make($req->all(), [
			'password' => 'required|min:8|confirmed',
			'token' => 'required'
		]);

		if($check->fails()) return response()->json($check->errors(), 422);
		else{
			$token = base64_decode($req->token);
			
			User::where('email', User::findForgot($token)->email)
				->update(['password' => Hash::make($req->password)]);
			
			User::deleteForgot($token);

			return response()->json(['msg' => true]);
		}
	}

	/**
	 * Update profile user.
	 *
	 * @param int $id
	 * @param Request $req
	 * 
	 * @return \Illuminate\Http\Response
	 */
	public function update($id, Request $req){
		$user = User::find($id);
		$data['name'] = $req->name;

		if(!empty($req->password)) $data['password'] = Hash::make($req->password);

		if(!empty($req->file('avatar')) && !empty($user->avatar)){
			if($user->avatar !== url('/img/user.png'))
				Storage::disk('public_upload')->delete(str_replace(url('/').'/','',$user->avatar));
			$data['avatar'] = url('/').'/'.$req->file('avatar')->store('pic','public_upload');
    	}else if(!empty($req->file('avatar')))
			$data['avatar'] = url('/').'/'.$req->file('avatar')->store('pic','public_upload');
		
		$user->update($data);

		return response()->json($user);
	}
}
