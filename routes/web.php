<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TagController;
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
/*
Route::get('/', function () {
    return view('welcome');
});

Route::post('notes', 'App\Http\Controllers\NotesController@store');
Route::patch('notes/{note}', 'App\Http\Controllers\NotesController@update');
Route::delete('notes/{note}', 'App\Http\Controllers\NotesController@delete');


Route::post('tags', 'App\Http\Controllers\TagsController@store');
Route::patch('tags/{tag}', 'App\Http\Controllers\TagsController@update');
Route::delete('tags/{tag}', 'App\Http\Controllers\TagsController@delete');

Route::get('test', 'App\Http\Controllers\NotesController@test');

Route::get('notes/create', 'App\Http\Controllers\NotesController@create');
Route::get('notes/read/{id}', 'App\Http\Controllers\NotesController@read');
Route::get('notes/edit', 'App\Http\Controllers\NotesController@edit');
Route::get('notes', 'App\Http\Controllers\NotesController@index');*/

Route::resource('notes', NoteController::class);
Route::resource('tags', TagController::class);
Route::get('notes/download/{id}', 'App\Http\Controllers\NoteController@download')->name('notes.download');