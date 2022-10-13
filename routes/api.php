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

// ruta para iniciar sesion
Route::post('/login', [UserController::class, 'authenticate']);
// ruta para rgistrar un nuevo usuario
Route::post('/register', [UserController::class, 'store']);

//rutas protegidas que obliga al usuario a iniciar sesion
Route::group(['middleware' => ['jwt.verify']], function(){
    //rutas para la entidad User
    Route::get('/user', [UserController::class, 'getAuthenticatedUser']);
    Route::get('/user', [UserController::class, 'index']);
    // Route::post('/user/{id}', [UserController::class, 'store']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'delete']);


    //rutas para la entidad Task
    // ruta que lee todas las tareas
    Route::get('/task', [TaskController::class, 'index']);
    // ruta para ver una tarea esoecifica
    Route::get('/task/{id}', [TaskController::class, 'show']);
    // ruta para el registro de una tarea
    Route::post('/task', [TaskController::class, 'store']);
    // ruta para actualizar una tarea
    Route::put('/task/{id}', [TaskController::class, 'update']);
    // ruta para eliminar una tarea
    Route::delete('/task/{id}', [TaskController::class, 'delete']);
});