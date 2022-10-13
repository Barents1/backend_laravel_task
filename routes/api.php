<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


//rutas publicas
Route::post('/login', [UserController::class, 'authenticate']);
Route::post('/register', [UserController::class, 'store']);
// Route::get('/user', [UserController::class, 'index']);
// Route::get('/user', [UserController::class, 'show']);
// Route::get('/task', [TaskController::class, 'index']);
// Route::post('/task', [TaskController::class, 'store']);

Route::group(['middleware' => ['jwt.verify']], function(){
    //rutas para la entidad User
    Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
    Route::get('/user', [UserController::class, 'index']);
    // Route::post('/user/{id}', [UserController::class, 'store']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'delete']);


    //rutas para la entidad Task
    Route::get('/task', [TaskController::class, 'index']);
    Route::get('/task/{id}', [TaskController::class, 'show']);
    Route::post('/task', [TaskController::class, 'store']);
    Route::put('/task/{id}', [TaskController::class, 'update']);
    Route::delete('/task/{id}', [TaskController::class, 'delete']);
});