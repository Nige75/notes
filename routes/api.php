<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotesApiController;

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

Route::middleware('auth:api')->prefix('v1')->group(function(){
    Route::get('/user', function(Request $request){
        return $request->user();
    });

    //Route::resource('tags', TagController::class);

    //Route::get('/notesapi/{note}', 'App\Http\Controllers\NotesApiController@show')->name('notesapi.show');

    Route::apiResource('notes', NotesApiController::class);
});
