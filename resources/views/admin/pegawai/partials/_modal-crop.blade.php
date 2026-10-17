            {{-- Modal crop foto: di luar card agar tidak terpotong overflow --}}
            <div x-show="cropModalOpen" x-transition x-cloak class="absolute inset-0 z-20 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
                <div class="bg-white dark:bg-[#111111] rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-gray-200 dark:border-gray-800 flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                        <h3 class="font-bold text-slate-900 dark:text-white text-base">Atur posisi &amp; crop foto pegawai</h3>
                        <button type="button" @click="closeAdminCrop()" aria-label="Tutup" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="w-full max-h-[360px] bg-slate-900 rounded-xl overflow-hidden flex items-center justify-center">
                            <img x-ref="adminCropImg" class="max-w-full block" alt="Foto yang akan di-crop">
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 text-center">Geser foto untuk menyesuaikan posisi, gunakan scroll mouse atau pinch untuk zoom.</p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-900/50 border-t border-gray-100 dark:border-gray-800 flex justify-end gap-3">
                        <button type="button" @click="closeAdminCrop()" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl text-xs hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">Batal</button>
                        <button type="button" @click="applyAdminCrop()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-500/20 transition-all">Gunakan foto ini</button>
                    </div>
                </div>
            </div>
