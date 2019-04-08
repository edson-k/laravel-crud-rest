<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function(){

	Route::post('login', 'Api\AuthController@login');
	Route::post('register', 'Api\AuthController@register');

	Route::group(['middleware' => 'auth:api'], function(){
		Route::get('user', 'Api\AuthController@getUser');
		Route::get('user/show/{id}', 'Api\AuthController@show');
		Route::put('user/update/{id}', 'Api\AuthController@update');
		Route::delete('user/destroy/{id}', 'Api\AuthController@destroy');
		Route::get('user/companies/{id}', 'Api\AuthController@companies');
		Route::post('user/store/company/{id}', 'Api\AuthController@storeCompany');

		Route::get('company/show/{id}', 'Api\CompanyController@show');
		Route::post('company/store', 'Api\CompanyController@store');
		Route::put('company/update/{id}', 'Api\CompanyController@update');
		Route::delete('company/destroy/{id}', 'Api\CompanyController@destroy');
		Route::get('company/users/{id}', 'Api\CompanyController@users');
		Route::post('company/store/user/{id}', 'Api\CompanyController@storeUser');
	});
	
});