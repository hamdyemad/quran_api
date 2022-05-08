<?php

use App\Http\Controllers\Api\AzkarCategoryController;
use App\Http\Controllers\Api\AzkarController;
use App\Http\Controllers\Api\DoaaController;
use App\Http\Controllers\Api\PictureController;
use App\Http\Controllers\Api\ReaderController;
use App\Http\Controllers\Api\UserController;
use App\Models\AzkarCategory;
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


Route::group(['prefix' => 'users'], function() {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/logout', [UserController::class, 'logout'])->middleware('jwt');
});


// Readers
Route::group(['prefix' => 'readers'], function() {
    Route::get('/', [ReaderController::class, 'index']);
    Route::post('/', [ReaderController::class, 'store']);
    Route::get('/{id}', [ReaderController::class, 'show']);
    Route::post('/{id}', [ReaderController::class, 'update']);
    Route::delete('/{id}', [ReaderController::class, 'destroy']);
});


// Pictures
Route::group(['prefix' => 'pictures'], function() {
    Route::get('/', [PictureController::class, 'index']);
    Route::post('/', [PictureController::class, 'store']);
    Route::get('/{id}', [PictureController::class, 'show']);
    Route::post('/{id}', [PictureController::class, 'update']);
    Route::delete('/{id}', [PictureController::class, 'destroy']);
});

// Azkar Categories
Route::group(['prefix' => 'categories'], function() {
    Route::get('/', [AzkarCategoryController::class, 'index']);
    Route::post('/', [AzkarCategoryController::class, 'store']);
    Route::get('/{id}', [AzkarCategoryController::class, 'show']);
    Route::post('/{id}', [AzkarCategoryController::class, 'update']);
    Route::delete('/{id}', [AzkarCategoryController::class, 'destroy']);
});
// Azkars
Route::group(['prefix' => 'azkars'], function() {
    Route::get('/', [AzkarController::class, 'index']);
    Route::post('/', [AzkarController::class, 'store']);
    Route::get('/{id}', [AzkarController::class, 'show']);
    Route::post('/{id}', [AzkarController::class, 'update']);
    Route::delete('/{id}', [AzkarController::class, 'destroy']);
});

// doaas
Route::group(['prefix' => 'doaas'], function() {
    Route::get('/', [DoaaController::class, 'index']);
    Route::post('/', [DoaaController::class, 'store']);
    Route::get('/{id}', [DoaaController::class, 'show']);
    Route::post('/{id}', [DoaaController::class, 'update']);
    Route::delete('/{id}', [DoaaController::class, 'destroy']);
});
