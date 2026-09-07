@extends('layouts.admin')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-20">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Pengaturan Profil Mandiri</h2>
        </div>
    </div>

    <!-- Global Errors Alert -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg shadow-sm dark:bg-red-900/20 dark:border-red-500">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700 dark:text-red-400 font-bold">
                    Perhatian! Beberapa data tidak valid:
                </p>
                <ul class="mt-1 list-disc list-inside text-xs text-red-600 dark:text-red-400/80">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Media & Security -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Profile Picture -->
                <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 text-center">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Foto Profil</h3>
                    <div class="relative w-48 h-48 mx-auto mb-6 group">
                        <div class="w-full h-full rounded-2xl border-4 @error('foto') border-red-500 @else border-gray-50 dark:border-slate-800 @enderror overflow-hidden shadow-md">
                            <img id="preview-image" src="{{ $user->pegawai && $user->pegawai->foto ? asset('storage/' . $user->pegawai->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($user->username) . '&size=200' }}" class="w-full h-full object-cover">
                        </div>
                        <label class="absolute inset-0 bg-black/40 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-2xl">
                            <span class="text-sm font-medium">Ubah Foto</span>
                            <input type="file" name="foto" class="hidden" onchange="previewFile(this)">
                        </label>
                    </div>
                    @error('foto') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500 mb-4 px-4">Klik pada foto untuk mengganti. Format JPEG/PNG, Max 2MB.</p>
                </div>

                <!-- Security Management -->
                <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Keamanan Akun</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" placeholder="Wajib jika isi password baru" class="w-full px-4 py-3 border @error('current_password') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-xl bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('current_password') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Password Baru</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full px-4 py-3 border @error('password') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-xl bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('password') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                        </div>
                    </div>
                </div>

                <div class="hidden lg:block">
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>

            <!-- Right Column: Profile Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Account Info -->
                <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">Informasi Akun</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full px-4 py-2 border @error('username') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('username') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 border @error('email') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('email') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Personal Detail -->
                <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">Identitas Diri</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Gelar Depan</label>
                            <input type="text" name="gelar_depan" value="{{ old('gelar_depan', $user->pegawai->gelar_depan ?? '') }}" class="w-full px-4 py-2 border @error('gelar_depan') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('gelar_depan') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->pegawai->nama ?? '') }}" required class="w-full px-4 py-2 border @error('nama') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('nama') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Gelar Belakang</label>
                            <input type="text" name="gelar_belakang" value="{{ old('gelar_belakang', $user->pegawai->gelar_belakang ?? '') }}" class="w-full px-4 py-2 border @error('gelar_belakang') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('gelar_belakang') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">NIP</label>
                            <input type="text" name="nip" value="{{ old('nip', $user->pegawai->nip ?? '') }}" class="w-full px-4 py-2 border @error('nip') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('nip') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">NIK</label>
                            <input type="text" name="nik" value="{{ old('nik', $user->pegawai->nik ?? '') }}" class="w-full px-4 py-2 border @error('nik') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('nik') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full px-4 py-2 border @error('jenis_kelamin') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                <option value="Laki-laki" {{ old('jenis_kelamin', $user->pegawai->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $user->pegawai->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user->pegawai->tempat_lahir ?? '') }}" class="w-full px-4 py-2 border @error('tempat_lahir') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('tempat_lahir') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', ($user->pegawai && $user->pegawai->tanggal_lahir) ? \Carbon\Carbon::parse($user->pegawai->tanggal_lahir)->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border @error('tanggal_lahir') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('tanggal_lahir') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Agama</label>
                            <input type="text" name="agama" value="{{ old('agama', $user->pegawai->agama ?? '') }}" class="w-full px-4 py-2 border @error('agama') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                            @error('agama') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Alamat Domisili</label>
                            <textarea name="alamat" rows="3" class="w-full px-4 py-2 border @error('alamat') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none transition-all">{{ old('alamat', $user->pegawai->alamat ?? '') }}</textarea>
                            @error('alamat') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Employment Detail -->
                <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">Status Kepegawaian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Status Pegawai</label>
                            <select name="status_kepegawaian" class="w-full px-4 py-2 border @error('status_kepegawaian') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                @foreach(['PNS', 'PPPK', 'CPNS', 'PPPK Paruh Waktu', 'Non ASN'] as $status)
                                    <option value="{{ $status }}" {{ old('status_kepegawaian', $user->pegawai->status_kepegawaian ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            @error('status_kepegawaian') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Jabatan</label>
                            <select name="jabatan_id" class="w-full px-4 py-2 border @error('jabatan_id') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($jabatans as $jab)
                                    <option value="{{ $jab->id }}" {{ old('jabatan_id', $user->pegawai->jabatan_id ?? '') == $jab->id ? 'selected' : '' }}>
                                        {{ $jab->nama_jabatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jabatan_id') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Golongan</label>
                            <select name="golongan" class="w-full px-4 py-2 border @error('golongan') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                <option value="">-- Pilih Golongan --</option>
                                <optgroup label="PNS / CPNS">
                                    @foreach(['I/a', 'I/b', 'I/c', 'I/d', 'II/a', 'II/b', 'II/c', 'II/d', 'III/a', 'III/b', 'III/c', 'III/d', 'IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e'] as $gol)
                                        <option value="{{ $gol }}" {{ old('golongan', $user->pegawai->golongan ?? '') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="PPPK">
                                    @foreach(['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII'] as $gol)
                                        <option value="{{ $gol }}" {{ old('golongan', $user->pegawai->golongan ?? '') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            @error('golongan') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-gray-300 mb-2">Status Pernikahan</label>
                            <select name="status_pernikahan" class="w-full px-4 py-2 border @error('status_pernikahan') border-red-500 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                @foreach(['Lajang', 'Menikah', 'Cerai Hidup', 'Cerai Mati'] as $stat)
                                    <option value="{{ $stat }}" {{ old('status_pernikahan', $user->pegawai->status_pernikahan ?? '') == $stat ? 'selected' : '' }}>{{ $stat }}</option>
                                @endforeach
                            </select>
                            @error('status_pernikahan') <p class="text-red-500 text-[10px] font-semibold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Mobile Save Button -->
                <div class="lg:hidden">
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewFile(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
