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

/*========== Guest Routes ===========*/
Route::get('/', function () {
    return view('welcome');
});

/*========== Auth Routes ===========*/
Auth::routes();

/*========== Home Route ===========*/
Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);

/*========== Forecasts Routes ===========*/
Route::get('forecasts', ['as' => 'forecasts.index', 'uses' => 'ForecastsController@listForecasts']);
Route::post('forecasts/import', ['as' => 'forecasts.import', 'uses' => 'ForecastsController@importDatFile']);
Route::get('forecasts/export', ['as' => 'forecasts.export', 'uses' => 'ForecastsController@exportData']);
Route::get('forecasts/fetch', ['as' => 'forecasts.fetch', 'uses' => 'ForecastsController@fetchForecastsData']);

/*========== Datatables Routes ===========*/
Route::get('datatables/get/forecasts', ['as' => 'datatables.get.forecasts', 'uses' => 'ForecastsController@getForecastsData']);