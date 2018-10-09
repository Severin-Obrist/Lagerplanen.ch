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

/**
 * Routes Sagen der Webseite, wo welcher URL hinführen soll
 */

//Routes für GET-requests
Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');
Route::get('/services', 'PagesController@services');
Route::get('/lager', 'PagesController@lager');
Route::get('/deleteBudgetPosten/{id}/{budgetPosten}', 'BudgetController@deleteBudgetPosten'); //Diese Route hat nich die $id Variableim URL
Route::get('/home', 'HomeController@index')->name('home'); //Gibt der Route einen Namen

//Routes für POST-requests
Route::post('addBudgetPosten', 'BudgetController@addBudgetPosten');
Route::post('createBudget', 'BudgetController@createBudget');
Route::post('leiterSearch', 'BudgetController@leiterSearch');
Route::post('addLeiter', 'BudgetController@addLeiter');

//::resource macht, dass die vordefinierten Funktionen von Ressourcen automatisch richtig geroutet werden
Route::resource('posts', 'PostsController');
Route::resource('budgets', 'BudgetController');

//Fügt die Routes für die Authorisation hinzu
Auth::routes();

