    {{-- MODAL TAMBAH / EDIT (di-teleport ke body) --}}
    <template x-teleport="body">
        <div x-show="modalOpen" x-cloak
             class="flex items-center justify-center p-6 sm:p-10"
             style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999;"
             @keydown.escape.window="cropModalOpen ? closeAdminCrop() : (modalOpen = false)">

            <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false"
                 class="absolute inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-sm"></div>

            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative w-full max-w-4xl max-h-[calc(100vh-3rem)] sm:max-h-[calc(100vh-5rem)] flex flex-col bg-white dark:bg-[#111111] shadow-2xl rounded-2xl border border-gray-100 dark:border-gray-800 z-10 overflow-hidden">

                {{-- Header modal --}}
                <div class="flex justify-between items-center gap-4 px-6 py-3.5 border-b border-gray-100 dark:border-gray-800 shrink-0">
                    <div class="min-w-0 flex items-baseline gap-2">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white truncate shrink-0" x-text="isEdit ? 'Detail & edit data pegawai' : 'Tambah pegawai baru'"></h3>
                        <span class="text-xs text-gray-400 dark:text-gray-500 truncate" x-show="isEdit" x-text="'NIP ' + form.nip"></span>
                    </div>
                    <button @click="modalOpen = false" type="button" aria-label="Tutup" class="shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg p-1.5 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Tab (hanya saat edit) --}}
                <div class="px-6 pt-3 shrink-0" x-show="isEdit" x-cloak>
                    <div class="inline-flex bg-gray-100 dark:bg-slate-900 rounded-lg p-1 gap-1">
                        <button @click="activeTab = 'data'" type="button"
                            :class="activeTab === 'data' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                            class="px-4 py-1.5 rounded-md font-medium text-sm transition-all">
                            Data utama
                        </button>
                        <button @click="activeTab = 'history'" type="button"
                            :class="activeTab === 'history' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                            class="px-4 py-1.5 rounded-md font-medium text-sm transition-all inline-flex items-center gap-1.5">
                            Riwayat perubahan
                            <span x-show="histories.length" x-text="histories.length" class="text-[10px] leading-none bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-full px-1.5 py-1"></span>
                        </button>
                    </div>
                </div>

                {{-- Area isi yang bisa di-scroll --}}
                <div class="flex-1 overflow-y-auto px-6 pb-6" :class="isEdit ? 'pt-4' : 'pt-5'">


                    @include('admin.pegawai.partials._modal-form')

                    @include('admin.pegawai.partials._modal-history')
                </div>{{-- /area scroll --}}

                {{-- Footer modal (tetap di bawah, tidak ikut scroll) --}}
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 dark:border-gray-800 shrink-0" x-show="activeTab === 'data'">
                    <button type="button" @click="modalOpen = false" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-[#111111] dark:text-gray-300 dark:border-gray-700 dark:hover:bg-slate-800 transition-colors">
                        Batal
                    </button>
                    <button type="submit" form="pegawai-form" :disabled="submitting" class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md shadow-blue-500/20 disabled:opacity-60 disabled:cursor-not-allowed inline-flex items-center gap-2 transition-colors">
                        <svg x-show="submitting" class="animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span x-text="submitting ? 'Menyimpan...' : 'Simpan data'"></span>
                    </button>
                </div>
            </div>{{-- /card modal --}}


            @include('admin.pegawai.partials._modal-crop')
        </div>
    </template>
