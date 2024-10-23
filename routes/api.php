<?php

use App\Models\ReceivedCapsule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CapsuleController;
use App\Http\Controllers\Api\ReceivedCapsuleController;

Route::post('/user', [Controller::class, 'register']); 

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/showName/{id}', [UserController::class, 'usernameView']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',[UserController::class, 'logout']);
});

Route::get('/view/{id}', [CapsuleController::class, 'view']);
Route::post('/send', [ReceivedCapsuleController::class, 'send']);
Route::post('store', [CapsuleController::class,'store']);
Route::get('/', [CapsuleController::class, 'index']);
Route::delete('/{id}', [CapsuleController::class, 'destroy']);

Route::middleware('api')->group(function () {
    
    // Route::put('/capsules/{id}', [CapsuleController::class, 'update']);
});




Route::apiResource('capsules', CapsuleController::class);
Route::apiResource('receivedcapsules', ReceivedCapsuleController::class);