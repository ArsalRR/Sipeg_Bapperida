<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthapiController;

Route::post('login', [AuthapiController::class, 'login']);
Route::post('logout', [AuthapiController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/me', function (Request $request) {
        return response()->json($request->user());
    });
});