<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('auth', 'AuthController@auth');
Route::any('logout', 'AuthController@logout');
Route::any('categories', 'CategoryController@index');
Route::any('categories/add', 'CategoryController@store');
Route::any('categories/edit', 'CategoryController@edit');
Route::any('categories/del', 'CategoryController@destroy');
Route::any('transcat', 'CategoryController@transcat');

//“data”: [ {“id”:<id>, “title”: <title>}, ...]
Route::any('categories/synch', 'CategoryController@synch');
Route::any('categories/{id}', 'CategoryController@show');

Route::any('balance', 'UsersController@balance');

Route::any('transactions', 'OperationsController@index');
Route::any('transactions/add', 'OperationsController@store');
Route::any('transactions/edit', 'OperationsController@edit');
Route::any('transactions/del', 'OperationsController@destroy');
Route::any('transactions/synch', 'OperationsController@synch');