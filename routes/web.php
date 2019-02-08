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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home','HomeController@reload');
Route::resource('category','CategoryController')->middleware('auth');
Route::resource('excel','excelController')->middleware('auth');
Route::resource('db','DBController')->middleware('auth');
// Route::get('/download',function(){
//     return view('download',compact(''));
// });
// Route::post('/excel', function(){
//     return view('excel');
// });