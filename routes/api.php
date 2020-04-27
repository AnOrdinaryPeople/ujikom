<?php

Route::prefix('auth')->group(function(){
	Route::post('register', 'AuthController@register');
	Route::post('login', 'AuthController@login');
	Route::get('refresh', 'AuthController@refresh');
	
	Route::group(['middleware' => ['web']], function(){
		Route::get('{provider}/redirect', 'OutController@redirectToProvider');
		Route::get('{provider}/callback', 'OutController@handleProviderCallback');
	});

	Route::group(['middleware' => 'auth:api'], function(){
		Route::get('user', 'AuthController@user');
		Route::post('logout', 'AuthController@logout');
	});
});

Route::group(['prefix' => 'user'], function(){
	Route::post('verify', 'AuthController@verify');
	Route::post('forgot', 'AuthController@forgot');
	Route::post('forgot/reset', 'AuthController@reset');
	Route::post('forgot/reset/check', 'AuthController@resetCheck');
	Route::post('profile/{user}', 'AuthController@update');
	Route::post('notif/{user}', 'UserController@notifCount');
	Route::post('notif/{user}/{skip}', 'UserController@notif');
	Route::post('not/read/{id}', 'UserController@notifRead');
	Route::post('not/del/{id}/{user}', 'UserController@notifDel');
	Route::post('not/all/{id}', 'UserController@notifAll');
	Route::post('not/alll/{id}', 'UserController@notifAllDel');
	Route::post('not/detail/{id}/{user}', 'UserController@notifDetail');
	Route::post('mine/{user}/{skip}', 'UserController@mine');
	Route::post('home/{user}', 'UserController@home');
	Route::post('home/{skip}/{user}', 'UserController@homeSkip');
	Route::post('home/side/{type}/{take}', 'UserController@sideRand');
	Route::post('home/detail/{id}/{user}', 'UserController@detail');
	Route::post('home/detail/child/more/{id}/{skip}', 'UserController@moreChild');
	Route::post('detail/reply/{post}/{user}', 'UserController@detailChild');
	Route::post('search', 'UserController@search');
	Route::post('post/reply/{id}/{user}/{owner}', 'UserController@sendReply');
	Route::post('post/reply/child/{id}/{user}/{owner}/{reply}', 'UserController@sendChild');
	Route::post('reply/{id}/{user}', 'UserController@reply');
	Route::post('vote/{id}/{type}/{user}', 'UserController@vote');
	Route::post('best/{id}/{post}/{user}', 'UserController@best');
	Route::post('warning/{post}/{user}', 'UserController@warn');
	Route::post('warn/edit/{warn}/{post}', 'UserController@warnUpdate');
	Route::post('warn/del/{warn}/{post}', 'UserController@warnDel');
	Route::post('report/{post}/{user}', 'UserController@reportSend');
	Route::post('store/{id}', 'UserController@store');
	Route::post('edit/{user}/{id}', 'UserController@edit');
	Route::post('update/{id}', 'UserController@update');
	Route::post('update/reply/{id}', 'UserController@updateRep');
	Route::post('destroy/{id}', 'UserController@destroy');
	Route::post('destroy/reply/{id}', 'UserController@destroyRep');
	Route::post('resend/{id}', 'AuthController@resendVer');
});

Route::group(['prefix' => 'admin'], function(){
	Route::post('home', 'AdminController@home');
	Route::post('users', 'AdminController@users');
	Route::post('users/notif/send/{id}', 'AdminController@notifSend');
	Route::delete('users/destroy/{id}/{desc}', 'AdminController@userDel');
	Route::post('posts', 'AdminController@posts');
	Route::post('posts/reply/{id}/{user}', 'AdminController@postsRepDel');
	Route::post('posts/destroy/{id}/{user}', 'AdminController@postsDel');
	Route::post('reports', 'AdminController@reports');
	Route::post('reports/rep/{id}', 'AdminController@reportsDet');
	Route::post('reports/reply/{id}/{user}', 'AdminController@reportsRep');
	Route::post('reports/destroy/{id}/{user}', 'AdminController@reportsDel');
	Route::post('msg', 'AdminController@msg');
	Route::post('msg/update/{id}', 'AdminController@msgUpdate');
	Route::post('msg/destroy/{id}/{user}', 'AdminController@msgDel');
	Route::post('history', 'AdminController@history');
});

Route::post('token/{user}', 'ApiController@index');
Route::post('token/{user}/get', 'ApiController@generate');
Route::post('dev/success/{user}', 'ApiController@getSuccess');
Route::post('dev/failed/{user}', 'ApiController@getFailed');

Route::get('user', 'ApiController@user');
Route::get('user/rand', 'ApiController@userRand');
Route::get('user/paginate', 'ApiController@userPag');
Route::get('user/paginate/{count}', 'ApiController@userPag');
Route::get('post', 'ApiController@post');
Route::get('post/rand', 'ApiController@postRand');
Route::get('post/paginate', 'ApiController@postPag');
Route::get('post/paginate/{count}', 'ApiController@postPag');
Route::get('comment/{id}', 'ApiController@comment');
Route::get('comment/{id}/rand', 'ApiController@commentRand');
Route::get('comment/{id}/paginate', 'ApiController@commentPag');
Route::get('comment/{id}/paginate/{count}', 'ApiController@commentPag');

Route::get('/{any}', function () {
    return response()->json(['status' => 'Not found.'], 404);
})->where('any','.*');
