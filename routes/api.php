<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthapiController;
use App\Http\Controllers\Api\PegawaiApiController;

Route::post('login', [AuthapiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthapiController::class, 'logout']);

    Route::get('pegawai', [PegawaiApiController::class, 'index']);

    Route::get('user/me', function (Request $request) {
        return response()->json($request->user());
    });

});