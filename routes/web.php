<?php
use App\Excel;
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
Route::resource('gangguan','GangguanController')->middleware('auth');
Route::resource('kendala','KendalaController')->middleware('auth');
Route::resource('excel','excelController')->middleware('auth');
Route::resource('prevMain','PrevMainController')->middleware('auth');
Route::resource('allData','DBController')->middleware('auth');
Route::resource('national','NationalController')->middleware('auth');
Route::get('refresh','DBController@refresh')->middleware('auth');

Route::get('gangguan-data/{label}/{region}/{pAwal?}/{pAkhir?}','GangguanController@gangguanData')->middleware('auth');
Route::get('kendala-data/{label}/{region}/{pAwal?}/{pAkhir?}','KendalaController@kendalaData')->middleware('auth');
Route::get('prevMainData','PrevMainController@data')->middleware('auth');