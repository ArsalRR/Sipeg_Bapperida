<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthapiController;
use App\Http\Controllers\Api\PegawaiApiController;
use App\Http\Controllers\SsoController;

Route::post('login', [AuthapiController::class, 'login']);
Route::post('sso/token', [SsoController::class, 'token']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('pegawai', [PegawaiApiController::class, 'index']);
    Route::post('logout', [AuthapiController::class, 'logout']);
    Route::get('user/me', function (Request $request) {
        return response()->json($request->user());
    });
});