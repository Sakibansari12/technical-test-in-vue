<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PmsApi;
//use App\Http\Controllers\PmsApi\Location;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:sanctum')->group(function() {
    Route::post("/pms-logout",[PmsApi\PmsAuthController::class,'logout']);
         //----------------------User api -----------------------------------//
         Route::resource('task-list', PmsApi\TasksController::class);
         Route::post('delete-multiple-task', [PmsApi\TasksController::class, 'deleteMultipleRecord']);
         Route::delete('delete-task/{id}', [PmsApi\TasksController::class, 'destroy']);
         Route::post('task-update-status/{id}', [PmsApi\TasksController::class, 'updateStatus']);
         Route::post('save-task-detail', [PmsApi\TasksController::class, 'store']);
         Route::post('save-task-detail/{id}', [PmsApi\TasksController::class, 'store']);
         Route::get('task-detail/{id}', [PmsApi\TasksController::class, 'detail']);
});
Route::post("/pms-login",[PmsApi\PmsAuthController::class,'pms_login']);
Route::post('user-register', [PmsApi\UserController::class, 'store']);
