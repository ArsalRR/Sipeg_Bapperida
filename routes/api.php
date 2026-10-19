<?php

use IlluminateHttpRequest;
use IlluminateSupportFacadesRoute;
use AppHttpControllersApiAuthApiController;
use AppHttpControllersApiPegawaiApiController;
use AppHttpControllersApiUserApiController;
use AppHttpControllersSSOController;

/*
|--------------------------------------------------------------------------
| API Routes - SIPEG (Sistem Informasi Pegawai)
|--------------------------------------------------------------------------
| SIPEG berfungsi sebagai master data untuk seluruh sistem (KAWAL, dll).
| Alur SSO:
|   1. Sistem lain redirect user ke SIPEG /sso/authorize
|   2. SIPEG redirect balik dengan ?code=xxxx
|   3. Sistem lain tukar code ke token via POST /api/sso/token
|   4. Gunakan Bearer Token untuk akses endpoint di bawah
|--------------------------------------------------------------------------
*/

// ─── Public Endpoints (tanpa auth) ────────────────────────────────────────────
Route::post('login', [AuthApiController::class, 'login']);
Route::post('sso/token', [SSOController::class, 'token']);
Route::get('sso/master-pegawai', [PegawaiApiController::class, 'masterData']);

// ─── Protected Endpoints (perlu Bearer Token dari Sanctum / SSO) ──────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('logout', [AuthApiController::class, 'logout']);
    Route::get('user/profile', [AuthApiController::class, 'profile']);

    // Info user yang sedang login (berguna untuk KAWAL setelah SSO)
    Route::get('user/me', function (Request $request) {
        $user = $request->user()->load(['pegawai.jabatan', 'pegawai.bidang']);
        return response()->json(['data' => $user]);
    });

    // ── User Endpoints ─────────────────────────────────────────────────────────
    Route::get('users', [UserApiController::class, 'index']);
    Route::get('user/by-username/{username}', [UserApiController::class, 'byUsername']);
    Route::get('user/{id}', [UserApiController::class, 'show']);

    // ── Pegawai Endpoints ──────────────────────────────────────────────────────
    Route::get('pegawai', [PegawaiApiController::class, 'index']);
    Route::get('pegawai/by-nip/{nip}', [PegawaiApiController::class, 'byNip']);
    Route::get('pegawai/{id}', [PegawaiApiController::class, 'show']);
});
