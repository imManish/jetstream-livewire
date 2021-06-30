<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectsController;
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

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:sanctum'], function() {
	
	//projects
	Route::get('/projects', [ProjectsController::class, 'index']);
	
	
	
});

// Not Found
Route::fallback(function(){
    return response()->json(['message' => 'API not found.'], 404);
});