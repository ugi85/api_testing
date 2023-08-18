<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);


    Route::get('show_product_id/{id}', [ProductController::class, 'show']);
    Route::post('add_product', [ProductController::class, 'store']);
    Route::get('all_product', [ProductController::class, 'index']);
    Route::put('update_product/{id}', [ProductController::class, 'update']);
    Route::delete('delete_product/{id}',  [ProductController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function () {
Route::get('users', [AuthController::class, 'index']); // Contoh route yang memerlukan otentikasi

Route::post('logout', [AuthController::class, 'logout']); // Route logout
});


// Route::middleware('auth:sanctum')->get('users', [AuthController::class, 'index']);
