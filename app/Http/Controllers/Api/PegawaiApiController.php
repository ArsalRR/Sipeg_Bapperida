<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;

class PegawaiApiController extends Controller
{
    public function index (){
$pegawai = Pegawai::with([
    'jabatan',
    'bidang',
    'user',
])->latest()->get();

return response()->json($pegawai);
    }
}
