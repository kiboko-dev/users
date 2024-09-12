<?php

use App\Enums\ActionEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    //    return ActionEnum::getValues();
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'registration']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('users/deleted', [UserController::class, 'deletedList']); //
    Route::post('users/bulkDelete', [UserController::class, 'bulkDelete']); //
    Route::post('users/bulkDestroy', [UserController::class, 'bulkDestroy']); //
    Route::post('users/bulkRestore', [UserController::class, 'bulkRestore']); //
    Route::resource('users', UserController::class)->except('store'); //
    Route::delete('users/{user}/delete', [UserController::class, 'delete']); //
    Route::get('users/{user}/restore', [UserController::class, 'restore']); //
});
