                    {{-- TAB DATA --}}
                    <div x-show="activeTab === 'data'">
                        <form :action="formAction" method="POST" enctype="multipart/form-data" id="pegawai-form" @submit="submitting = true">
                            @csrf
                            <template x-if="isEdit">
                                <input type="hidden" name="_method" value="PUT">
                            </template>

                            <input type="hidden" name="cropped_foto" :value="croppedFoto">

                            {{-- Foto --}}
                            <div class="flex items-center gap-4 pb-5 mb-5 border-b border-gray-100 dark:border-gray-800">
                                <div class="relative shrink-0 w-20 h-20">
                                    <div class="w-20 h-20 rounded-full border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-slate-900 flex items-center justify-center overflow-hidden">
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" class="w-full h-full object-cover" alt="Pratinjau foto">
                                        </template>
                                        <template x-if="!photoPreview">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </template>
                                    </div>
                                    <input type="file" @change="initAdminCrop($event)" class="absolute inset-0 w-20 h-20 opacity-0 cursor-pointer rounded-full" accept=".jpg,.jpeg,.png,image/jpeg,image/png" aria-label="Pilih foto profil">
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">Foto profil</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Klik lingkaran untuk ganti &amp; atur posisi foto.</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Format JPG, JPEG, PNG (Maks 2MB).</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                                {{-- Kolom kiri: identitas --}}
                                <div class="space-y-4">
                                    <h4 class="flex items-center gap-2 font-semibold text-blue-600 dark:text-blue-400 text-sm mb-1">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Identitas utama
                                    </h4>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">NIP (18 digit) <span class="text-red-500">*</span></label>
                                            <input type="text" name="nip" x-model="form.nip" required maxlength="18" pattern="\d{18}" title="Harus 18 digit angka" inputmode="numeric" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">NIK (16 digit) <span class="text-red-500">*</span></label>
                                            <input type="text" name="nik" x-model="form.nik" required maxlength="16" pattern="\d{16}" title="Harus 16 digit angka" inputmode="numeric" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama lengkap (sesuai KTP) <span class="text-red-500">*</span></label>
                                        <input type="text" name="nama" x-model="form.nama" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Gelar depan</label>
                                            <input type="text" name="gelar_depan" x-model="form.gelar_depan" placeholder="Dr." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Gelar belakang</label>
                                            <input type="text" name="gelar_belakang" x-model="form.gelar_belakang" placeholder="S.Kom" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tempat lahir <span class="text-red-500">*</span></label>
                                            <input type="text" name="tempat_lahir" x-model="form.tempat_lahir" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal lahir <span class="text-red-500">*</span></label>
                                            <input type="date" name="tanggal_lahir" x-model="form.tanggal_lahir" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Jenis kelamin <span class="text-red-500">*</span></label>
                                            <select name="jenis_kelamin" x-model="form.jenis_kelamin" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Agama <span class="text-red-500">*</span></label>
                                            <input type="text" name="agama" x-model="form.agama" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                        </div>
                                    </div>
                                </div>

                                {{-- Kolom kanan: kepegawaian --}}
                                <div class="space-y-4">
                                    <h4 class="flex items-center gap-2 font-semibold text-blue-600 dark:text-blue-400 text-sm mb-1">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2M5 21h2m10 0h-6"></path></svg>
                                        Detail kepegawaian
                                    </h4>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status kepegawaian <span class="text-red-500">*</span></label>
                                            <select name="status_kepegawaian" x-model="form.status_kepegawaian" required @change="form.golongan = ''" class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                                <option value="PNS">PNS</option>
                                                <option value="PPPK">PPPK</option>
                                                <option value="CPNS">CPNS</option>
                                                <option value="PPPK Paruh Waktu">PPPK Paruh Waktu</option>
                                                <option value="Non ASN">Non ASN</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status keaktifan <span class="text-red-500">*</span></label>
                                            <select name="status_kerja" x-model="form.status_kerja" required class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                                <option value="Aktif">Aktif</option>
                                                <option value="Tidak Aktif">Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Golongan --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                            Golongan
                                            <span x-show="form.status_kepegawaian === 'Non ASN'" class="text-gray-400 font-normal">(tidak berlaku)</span>
                                        </label>
                                        <select x-show="['PNS','CPNS'].includes(form.status_kepegawaian)" :disabled="!['PNS','CPNS'].includes(form.status_kepegawaian)" name="golongan" x-model="form.golongan" class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                            <option value="">-- Pilih golongan --</option>
                                            <option value="I/a">I/a</option><option value="I/b">I/b</option><option value="I/c">I/c</option><option value="I/d">I/d</option>
                                            <option value="II/a">II/a</option><option value="II/b">II/b</option><option value="II/c">II/c</option><option value="II/d">II/d</option>
                                            <option value="III/a">III/a</option><option value="III/b">III/b</option><option value="III/c">III/c</option><option value="III/d">III/d</option>
                                            <option value="IV/a">IV/a</option><option value="IV/b">IV/b</option><option value="IV/c">IV/c</option><option value="IV/d">IV/d</option><option value="IV/e">IV/e</option>
                                        </select>
                                        <select x-show="['PPPK','PPPK Paruh Waktu'].includes(form.status_kepegawaian)" :disabled="!['PPPK','PPPK Paruh Waktu'].includes(form.status_kepegawaian)" name="golongan" x-model="form.golongan" class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                            <option value="">-- Pilih golongan --</option>
                                            <template x-for="g in ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII']" :key="g">
                                                <option :value="g" x-text="g"></option>
                                            </template>
                                        </select>
                                        <input x-show="form.status_kepegawaian === 'Non ASN'" type="text" disabled value="-" class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm border border-gray-200 dark:border-gray-800 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-400">
                                    </div>

                                    {{-- MKG: default dihitung dari NIP, bisa di-override manual --}}
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Masa Kerja Golongan (MKG)</label>
                                            <template x-if="hasManualMkg">
                                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    Manual
                                                </span>
                                            </template>
                                            <template x-if="!hasManualMkg">
                                                <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                    Otomatis (NIP)
                                                </span>
                                            </template>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="relative">
                                                <input type="number" name="mkg_tahun" x-model="form.mkg_tahun" min="0" max="60" placeholder="Tahun (auto)"
                                                    class="w-full px-3 sm:px-4 py-2 pr-14 text-xs sm:text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                                <span class="absolute right-3 top-2 text-xs text-gray-400 dark:text-gray-500 pointer-events-none">Tahun</span>
                                            </div>
                                            <div class="relative">
                                                <input type="number" name="mkg_bulan" x-model="form.mkg_bulan" min="0" max="11" placeholder="Bulan (auto)"
                                                    class="w-full px-3 sm:px-4 py-2 pr-14 text-xs sm:text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                                <span class="absolute right-3 top-2 text-xs text-gray-400 dark:text-gray-500 pointer-events-none">Bulan</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between gap-2 mt-1.5">
                                            <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-tight">
                                                <template x-if="!hasManualMkg">
                                                    <span>
                                                        Dihitung otomatis dari NIP
                                                        <span class="font-medium text-slate-700 dark:text-slate-300" x-text="mkgAutoLabel"></span>.
                                                    </span>
                                                </template>
                                                <template x-if="hasManualMkg">
                                                    <span>MKG manual: <strong class="text-amber-600 dark:text-amber-400" x-text="`${form.mkg_tahun || 0} Thn ${form.mkg_bulan || 0} Bln`"></strong></span>
                                                </template>
                                            </p>
                                            <div class="flex items-center gap-1.5 shrink-0">
                                                <button type="button" @click="fillMkgFromNip()"
                                                    title="Hitung dan isikan angka tahun & bulan berdasarkan NIP saat ini"
                                                    class="text-[11px] font-medium text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                    Hitung dari NIP
                                                </button>
                                                <span x-show="hasManualMkg" class="text-gray-300 dark:text-gray-600">|</span>
                                                <button x-show="hasManualMkg" type="button" @click="resetMkgToAuto()"
                                                    title="Kosongkan nilai manual agar kembali menggunakan hitungan NIP otomatis"
                                                    class="text-[11px] font-medium text-rose-500 hover:underline">
                                                    Reset ke otomatis
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Bidang --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Bidang / unit kerja <span class="text-red-500">*</span></label>
                                        <select name="bidang_id" x-model="form.bidang_id" @change="onBidangChange()" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                            <option value="">-- Pilih bidang / unit kerja terlebih dahulu --</option>
                                            @foreach($bidangs as $b)
                                                <option value="{{ $b->id }}">{{ $b->nama_bidang }} ({{ $b->singkatan }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Jabatan --}}
                                    <div x-data="{ jabatanOpen: false }" class="relative">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                            Jabatan <span class="text-red-500">*</span>
                                            <span x-show="!form.bidang_id" class="text-xs text-amber-600 dark:text-amber-400 font-normal ml-1">(pilih bidang terlebih dahulu)</span>
                                        </label>
                                        <input type="hidden" name="jabatan_id" x-model="form.jabatan_id">
                                        <button type="button"
                                            @click="if (form.bidang_id) jabatanOpen = !jabatanOpen"
                                            @click.outside="jabatanOpen = false"
                                            :disabled="!form.bidang_id"
                                            :class="!form.bidang_id ? 'opacity-60 cursor-not-allowed bg-gray-100 dark:bg-slate-800' : 'bg-gray-50 dark:bg-slate-900'"
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none text-left flex items-center justify-between transition-colors">
                                            <span x-text="form.jabatan_id ? getJabatanNama(form.jabatan_id) : (form.bidang_id ? '-- Pilih jabatan --' : '-- Pilih bidang terlebih dahulu --')" :class="!form.jabatan_id ? 'text-gray-400' : ''"></span>
                                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div x-show="jabatanOpen && form.bidang_id" x-transition x-cloak
                                            class="absolute z-50 mt-1 w-full max-h-60 overflow-y-auto bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl">
                                            <template x-for="jab in availableJabatansForForm" :key="jab.id">
                                                <button type="button"
                                                    @click="
                                                        if (jabatanPenuh(jab)) {
                                                            Swal.fire({
                                                                icon: 'warning',
                                                                title: 'Formasi jabatan penuh',
                                                                text: 'Jabatan ' + jab.nama_jabatan + ' sudah terisi penuh (' + jab.pegawais_count + '/' + (jab.kebutuhan || jab.jumlah) + ' formasi). Tidak dapat menambah pegawai pada posisi ini.',
                                                                confirmButtonColor: '#3085d6'
                                                            });
                                                        } else {
                                                            form.jabatan_id = jab.id;
                                                            jabatanOpen = false;
                                                        }
                                                    "
                                                    :class="jabatanPenuh(jab)
                                                        ? 'text-gray-400 dark:text-gray-600 cursor-not-allowed bg-gray-50 dark:bg-[#111]'
                                                        : (form.jabatan_id == jab.id ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 font-semibold' : 'text-slate-900 dark:text-white hover:bg-gray-100 dark:hover:bg-[#222]')"
                                                    class="w-full px-4 py-2.5 text-left text-sm flex items-center justify-between border-b border-gray-100 dark:border-gray-800 last:border-0 transition-colors">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <span class="truncate" x-text="jab.nama_jabatan"></span>
                                                        <span class="text-xs opacity-60 shrink-0" x-text="'(' + jab.jenis_jabatan + ')'"></span>
                                                    </div>
                                                </button>
                                            </template>
                                            <template x-if="availableJabatansForForm.length === 0">
                                                <div class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400 text-center">
                                                    Tidak ada jabatan untuk bidang ini.
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status pernikahan <span class="text-red-500">*</span></label>
                                        <select name="status_pernikahan" x-model="form.status_pernikahan" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                            <option value="Lajang">Lajang</option>
                                            <option value="Menikah">Menikah</option>
                                            <option value="Cerai Hidup">Cerai Hidup</option>
                                            <option value="Cerai Mati">Cerai Mati</option>
                                        </select>
                                    </div>

                                    {{-- Akun terhubung --}}
                                    <div x-data="{ openAccountDropdown: false, accountSearch: '' }">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Akun terhubung</label>
                                        <input type="hidden" name="user_id" :value="form.user_id">

                                        <div class="relative" @click.outside="openAccountDropdown = false">
                                            <button type="button" @click="openAccountDropdown = !openAccountDropdown; accountSearch = ''"
                                                class="w-full px-4 py-2 text-left border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none flex items-center justify-between">
                                                <span x-text="getSelectedAccountLabel()"></span>
                                                <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="openAccountDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>

                                            <div x-show="openAccountDropdown" x-transition x-cloak
                                                class="absolute left-0 right-0 mt-1.5 bg-white dark:bg-[#18181b] border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl z-50 p-2 space-y-1 max-h-60 flex flex-col">

                                                <div class="relative shrink-0 p-1">
                                                    <input type="text" x-model="accountSearch" placeholder="Cari username atau email..."
                                                        class="w-full pl-8 pr-3 py-1.5 text-xs border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none">
                                                    <svg class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                                </div>

                                                <div class="overflow-y-auto flex-1 divide-y divide-gray-50 dark:divide-gray-800">
                                                    <button type="button" @click="form.user_id = ''; openAccountDropdown = false"
                                                        class="w-full text-left px-3 py-2 text-xs rounded-md hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-between"
                                                        :class="form.user_id === '' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300'">
                                                        <span>-- Tidak ada akun --</span>
                                                        <svg x-show="form.user_id === ''" class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>

                                                    <template x-if="isEdit && currentAccount && (accountSearch === '' || currentAccount.username.toLowerCase().includes(accountSearch.toLowerCase()) || (currentAccount.email && currentAccount.email.toLowerCase().includes(accountSearch.toLowerCase())))">
                                                        <button type="button" @click="form.user_id = currentAccount.id; openAccountDropdown = false"
                                                            class="w-full text-left px-3 py-2 text-xs rounded-md hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-between"
                                                            :class="form.user_id == currentAccount.id ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300'">
                                                            <span x-text="currentAccount.username + ' (' + (currentAccount.email || '-') + ') — [saat ini]'"></span>
                                                            <svg x-show="form.user_id == currentAccount.id" class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                    </template>

                                                    <template x-for="u in getFilteredSortedUsers(accountSearch)" :key="u.id">
                                                        <button type="button" @click="form.user_id = u.id; openAccountDropdown = false"
                                                            class="w-full text-left px-3 py-2 text-xs rounded-md hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-between"
                                                            :class="form.user_id == u.id ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300'">
                                                            <span x-text="u.username + ' (' + (u.email || '-') + ')'"></span>
                                                            <svg x-show="form.user_id == u.id" class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Alamat lengkap <span class="text-red-500">*</span></label>
                                    <textarea name="alamat" x-model="form.alamat" required rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-600 outline-none resize-none"></textarea>
                                </div>

                                <div class="md:col-span-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                                    <div class="bg-blue-50/50 dark:bg-blue-900/10 p-4 rounded-xl border border-blue-100 dark:border-blue-900/30 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                        <div>
                                            <label class="block text-sm font-bold text-blue-600 dark:text-blue-400">Tanggal berlaku perubahan <span class="text-red-500">*</span></label>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kapan data status/jabatan/golongan di atas mulai berlaku (untuk pencatatan riwayat).</p>
                                        </div>
                                        <div class="w-full sm:w-56 shrink-0">
                                            <input type="date" name="tanggal_berlaku" x-model="form.tanggal_berlaku" required class="w-full px-4 py-2 border-2 border-blue-500 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-blue-600 outline-none shadow-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
