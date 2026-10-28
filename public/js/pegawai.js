(function () {
'use strict';

const STORAGE_KEYS = {
    page:    'sipeg_pegawai_page',
    perPage: 'sipeg_pegawai_per_page',
    columns: 'sipeg_pegawai_columns',
};

const LEGACY_KEYS = ['sipeg_pegawai_search'];

const GOLONGAN_MAP = {
    'I/a': 'Juru Muda', 'I/b': 'Juru Muda Tingkat I', 'I/c': 'Juru', 'I/d': 'Juru Tingkat I',
    'II/a': 'Pengatur Muda', 'II/b': 'Pengatur Muda Tingkat I', 'II/c': 'Pengatur', 'II/d': 'Pengatur Tingkat I',
    'III/a': 'Penata Muda', 'III/b': 'Penata Muda Tingkat I', 'III/c': 'Penata', 'III/d': 'Penata Tingkat I',
    'IV/a': 'Pembina', 'IV/b': 'Pembina Tingkat I', 'IV/c': 'Pembina Utama Muda',
    'IV/d': 'Pembina Utama Madya', 'IV/e': 'Pembina Utama',
};

const STATUS_CLASS = {
    'PNS':              'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    'PPPK':             'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
    'CPNS':             'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
    'PPPK Paruh Waktu': 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    'Non ASN':          'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400',
};
const STATUS_CLASS_DEFAULT = 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400';

const SORT_ICONS = {
    none: '<svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>',
    asc:  '<svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>',
    desc: '<svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>',
};

/** Ubah nilai apa pun jadi string aman (angka dari JSON, null, undefined). */
const str = (v) => (v === null || v === undefined) ? '' : String(v);

const lower = (v) => str(v).toLowerCase();

const escapeHtml = (v) => str(v)
    .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

/** Nilai MKG manual dianggap terisi jika bukan kosong/null. */
const isManualMkg = (v) => v !== '' && v !== null && v !== undefined;

/** Total bulan sejak TMT (dari NIP, lalu tanggal berlaku). null jika tidak bisa dihitung. */
function mkgFromSource(nip, tanggalBerlaku) {
    const now = new Date();
    let y = null, m = null;

    const s = str(nip);
    if (s.length >= 14) {
        const yy = parseInt(s.substring(8, 12), 10);
        const mm = parseInt(s.substring(12, 14), 10);
        if (yy >= 1950 && yy <= now.getFullYear() && mm >= 1 && mm <= 12) { y = yy; m = mm; }
    }

    if (y === null && tanggalBerlaku) {
        const tb = new Date(tanggalBerlaku);
        if (!isNaN(tb.getTime())) { y = tb.getFullYear(); m = tb.getMonth() + 1; }
    }

    if (y === null) return null;

    return Math.max(0, (now.getFullYear() - y) * 12 + (now.getMonth() - (m - 1)));
}

function emptyForm(tanggalBerlaku = '') {
    return {
        nama: '', gelar_depan: '', gelar_belakang: '', nip: '', nik: '', alamat: '',
        tempat_lahir: '', tanggal_lahir: '', jenis_kelamin: 'Laki-laki', agama: '',
        status_kepegawaian: 'PNS', status_kerja: 'Aktif', jabatan_id: '', bidang_id: '',
        golongan: '', mkg_tahun: '', mkg_bulan: '', status_pernikahan: 'Lajang',
        tanggal_berlaku: tanggalBerlaku, user_id: '',
    };
}

function readStoredInt(storage, key) {
    try {
        const n = parseInt(storage.getItem(key), 10);
        return Number.isFinite(n) && n > 0 ? n : null;
    } catch (e) {
        return null;
    }
}

function writeStored(storage, key, value) {
    try { storage.setItem(key, String(value)); } catch (e) { /* storage penuh / diblokir */ }
}

// =====================================================================
// 2. Komponen Alpine
// =====================================================================

function pegawaiCrud() {
    const cfg = window.PEGAWAI_CONFIG || {};

    return {
        // ---------- Data dari server ----------
        allPegawai:  cfg.pegawais || [],
        allJabatans: cfg.jabatans || [],
        allBidangs:  cfg.bidangs || [],
        allUsers:    cfg.users || [],

        // ---------- URL ----------
        storageBase:  cfg.storageBase || '/storage/',
        baseUrl:      cfg.baseUrl,
        exportPdfUrl: cfg.exportPdfUrl,
        storeUrl:     cfg.storeUrl,

        // ---------- State tabel ----------
        search: '',
        perPage: 5,
        currentPage: 1,
        sortCol: 'nama',
        sortAsc: true,
        showColumnDropdown: false,
        exportOpen: false,
        expandedId: null,

        // short = judul di header tabel, label = nama di dropdown "Kolom",
        // sort = kunci pengurutan (tidak ada = kolom tidak bisa diurutkan)
        columns: {
            nama:         { label: 'Nama',               short: 'Nama',         sort: 'nama',               visible: true },
            nip:          { label: 'NIP',                short: 'NIP',          sort: 'nip',                visible: true },
            mkg:          { label: 'MKG',                short: 'MKG',          sort: 'mkg_total_months',   visible: true },
            jabatan:      { label: 'Jabatan',            short: 'Jabatan',      sort: 'jabatan_nama',       visible: true },
            bidang:       { label: 'Bidang / Unit',      short: 'Bidang / Unit', sort: 'bidang_nama',       visible: true },
            status:       { label: 'Status Kepegawaian', short: 'Status',       sort: 'status_kepegawaian', visible: true },
            status_kerja: { label: 'Status Keaktifan',   short: 'Keaktifan',    sort: 'status_kerja',       visible: true },
            golongan:     { label: 'Golongan',           short: 'Golongan',     sort: 'golongan',           visible: true },
            ttl:          { label: 'Tempat, Tgl Lahir',  short: 'TTL',          sort: null,                 visible: false },
            jk:           { label: 'Jenis Kelamin',      short: 'L/P',          sort: null,                 visible: false },
            agama:        { label: 'Agama',              short: 'Agama',        sort: null,                 visible: false },
            nik:          { label: 'NIK',                short: 'NIK',          sort: null,                 visible: false },
            pernikahan:   { label: 'Status Nikah',       short: 'Status Nikah', sort: null,                 visible: false },
            alamat:       { label: 'Alamat',             short: 'Alamat',       sort: null,                 visible: false },
        },

        // ---------- State modal ----------
        modalOpen: false,
        isEdit: false,
        activeTab: 'data',
        submitting: false,
        formAction: '',
        currentAccount: null,
        photoPreview: null,
        croppedFoto: '',
        cropModalOpen: false,
        adminCropper: null,
        histories: [],
        form: emptyForm(),

        // =================================================================
        // Init
        // =================================================================
        init() {
            LEGACY_KEYS.forEach((k) => { try { sessionStorage.removeItem(k); } catch (e) {} });

            const savedPerPage = readStoredInt(sessionStorage, STORAGE_KEYS.perPage);
            const savedPage    = readStoredInt(sessionStorage, STORAGE_KEYS.page);

            if (savedPerPage) this.perPage = savedPerPage;
            if (savedPage) this.currentPage = savedPage;
            this.clampPage();

            this.$watch('search', () => { this.currentPage = 1; });
            this.$watch('perPage', (val) => {
                writeStored(sessionStorage, STORAGE_KEYS.perPage, val);
                this.currentPage = 1;
            });
            this.$watch('currentPage', (val) => writeStored(sessionStorage, STORAGE_KEYS.page, val));

            // Inti perbaikan halaman kosong: setiap jumlah halaman berubah
            // (hapus data, ganti pencarian, ganti jumlah baris), halaman aktif dikoreksi.
            this.$watch('totalPages', () => this.clampPage());

            this.restoreColumns();
            this.$watch('columns', (val) => {
                const state = {};
                Object.keys(val).forEach((k) => { state[k] = val[k].visible; });
                try { localStorage.setItem(STORAGE_KEYS.columns, JSON.stringify(state)); } catch (e) {}
            }, { deep: true });
        },

        restoreColumns() {
            try {
                const parsed = JSON.parse(localStorage.getItem(STORAGE_KEYS.columns) || 'null');
                if (!parsed) return;
                Object.keys(parsed).forEach((k) => {
                    if (this.columns[k]) this.columns[k].visible = !!parsed[k];
                });
                if (parsed.status_kerja === undefined) this.columns.status_kerja.visible = true;
            } catch (e) { /* abaikan data kolom yang rusak */ }
        },

        // =================================================================
        // Helper tampilan
        // =================================================================
        esc: escapeHtml,

        namaLengkap(p) {
            return (p.gelar_depan ? p.gelar_depan + ' ' : '') + (p.nama || '-') + (p.gelar_belakang ? ', ' + p.gelar_belakang : '');
        },

        fmtTgl(d) {
            if (!d) return '-';
            const dt = new Date(d);
            return isNaN(dt.getTime()) ? '-' : dt.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        },

        ttlText(p) {
            return (p.tempat_lahir || '-') + ', ' + this.fmtTgl(p.tanggal_lahir);
        },

        today() {
            return new Date().toLocaleDateString('sv-SE'); // YYYY-MM-DD waktu lokal
        },

        statusClass(s) {
            return STATUS_CLASS[s] || STATUS_CLASS_DEFAULT;
        },

        kerjaClass(s) {
            return (s || 'Aktif') === 'Aktif'
                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                : 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400';
        },

        golonganText(gol) {
            if (!gol) return '-';
            if (!GOLONGAN_MAP[gol]) return 'Golongan ' + gol;
            return GOLONGAN_MAP[gol] + ' (' + gol.replace('/', ' ').toUpperCase() + ')';
        },

        golonganPangkat(gol) {
            if (!gol) return '-';
            return GOLONGAN_MAP[gol] || ('Golongan ' + gol);
        },

        sortIcon(col) {
            if (this.sortCol !== col) return SORT_ICONS.none;
            return this.sortAsc ? SORT_ICONS.asc : SORT_ICONS.desc;
        },

        // =================================================================
        // MKG
        // =================================================================
        get hasManualMkg() {
            return isManualMkg(this.form.mkg_tahun);
        },

        get mkgAutoLabel() {
            const total = mkgFromSource(this.form.nip, this.form.tanggal_berlaku);
            return total === null ? '' : `(${Math.floor(total / 12)} Thn ${total % 12} Bln)`;
        },

        getMkgTotalMonths(p) {
            if (p && isManualMkg(p.mkg_tahun)) {
                return (parseInt(p.mkg_tahun, 10) || 0) * 12 + (parseInt(p.mkg_bulan, 10) || 0);
            }
            const total = mkgFromSource(p && p.nip, p && p.tanggal_berlaku);
            return total === null ? -1 : total;
        },

        getMkgDisplay(p) {
            const total = this.getMkgTotalMonths(p);
            return total < 0 ? '-' : `${Math.floor(total / 12)} Tahun ${total % 12} Bulan`;
        },

        fillMkgFromNip() {
            const total = mkgFromSource(this.form.nip, this.form.tanggal_berlaku);
            if (total !== null) {
                this.form.mkg_tahun = Math.floor(total / 12);
                this.form.mkg_bulan = total % 12;
                return;
            }
            Swal.fire({
                icon: 'info',
                title: 'NIP belum valid',
                text: 'Pastikan NIP minimal 14-18 digit angka yang memuat tahun & bulan pengangkatan CPNS/PNS.',
                confirmButtonColor: '#3085d6',
            });
        },

        resetMkgToAuto() {
            this.form.mkg_tahun = '';
            this.form.mkg_bulan = '';
        },

        // =================================================================
        // Filter, urut, paginasi
        // =================================================================
        get visibleColumnCount() {
            return Object.values(this.columns).filter((c) => c.visible).length;
        },

        /** Nilai yang dibandingkan saat mengurutkan kolom `col`. */
        sortValue(p, col) {
            switch (col) {
                case 'mkg_total_months': return this.getMkgTotalMonths(p);
                case 'jabatan_nama':     return lower(p.jabatan ? p.jabatan.nama_jabatan : '');
                case 'bidang_nama':      return lower(p.bidang ? p.bidang.nama_bidang : '');
                case 'status_kerja':     return lower(p.status_kerja || 'Aktif');
                default:                 return lower(p[col]);
            }
        },

        matchesSearch(p, q) {
            return lower(p.nama).includes(q)
                || lower(p.nip).includes(q)
                || lower(p.nik).includes(q)
                || lower(p.jabatan && p.jabatan.nama_jabatan).includes(q)
                || lower(p.bidang && p.bidang.nama_bidang).includes(q)
                || lower(p.bidang && p.bidang.singkatan).includes(q);
        },

        get filteredPegawai() {
            let result = [...this.allPegawai]; // salin, jangan urutkan array asli

            const q = lower(this.search).trim();
            if (q) result = result.filter((p) => this.matchesSearch(p, q));

            const dir = this.sortAsc ? 1 : -1;
            result.sort((a, b) => {
                const valA = this.sortValue(a, this.sortCol);
                const valB = this.sortValue(b, this.sortCol);
                if (valA < valB) return -1 * dir;
                if (valA > valB) return 1 * dir;
                return 0;
            });

            return result;
        },

        get perPageNumber() {
            return parseInt(this.perPage, 10) || 5;
        },

        /** Minimal 1, supaya halaman aktif selalu punya tujuan yang valid. */
        get totalPages() {
            return Math.max(1, Math.ceil(this.filteredPegawai.length / this.perPageNumber));
        },

        /** Halaman aktif yang dijamin berada di rentang 1..totalPages. */
        get safePage() {
            const cur = parseInt(this.currentPage, 10) || 1;
            return Math.min(Math.max(1, cur), this.totalPages);
        },

        clampPage() {
            if (this.currentPage !== this.safePage) this.currentPage = this.safePage;
        },

        get paginatedPegawai() {
            const start = (this.safePage - 1) * this.perPageNumber;
            return this.filteredPegawai.slice(start, start + this.perPageNumber);
        },

        get visiblePages() {
            const total = this.totalPages, cur = this.safePage, pages = [];
            for (let i = 1; i <= total; i++) {
                if (i === 1 || i === total || Math.abs(i - cur) <= 1) {
                    pages.push(i);
                } else if (pages[pages.length - 1] !== '…') {
                    pages.push('…');
                }
            }
            return pages;
        },

        sortBy(col) {
            if (!col) return;
            if (this.sortCol === col) {
                this.sortAsc = !this.sortAsc;
            } else {
                this.sortCol = col;
                this.sortAsc = true;
            }
        },

        // =================================================================
        // Form: bidang, jabatan, akun
        // =================================================================
        get availableJabatansForForm() {
            if (!this.form.bidang_id) return [];
            const bidangId = parseInt(this.form.bidang_id, 10);

            const list = this.allJabatans.filter((j) => j.bidang_id === bidangId);

            // Kelas jabatan tertinggi di atas, lalu urut nama
            list.sort((a, b) => {
                const kA = parseInt(a.kelas_jabatan, 10) || 0;
                const kB = parseInt(b.kelas_jabatan, 10) || 0;
                if (kB !== kA) return kB - kA;
                return str(a.nama_jabatan).localeCompare(str(b.nama_jabatan));
            });

            return list;
        },

        jabatanPenuh(jab) {
            const maxCap = jab.kebutuhan || jab.jumlah;
            return maxCap !== null && maxCap > 0 && jab.pegawais_count >= maxCap && this.form.jabatan_id != jab.id;
        },

        onBidangChange() {
            if (!this.form.bidang_id) {
                this.form.jabatan_id = '';
                return;
            }
            if (this.form.jabatan_id && !this.availableJabatansForForm.some((j) => j.id == this.form.jabatan_id)) {
                this.form.jabatan_id = '';
            }
        },

        getJabatanNama(id) {
            if (!id) return '-';
            const j = this.allJabatans.find((item) => item.id == id);
            return j ? j.nama_jabatan : '-';
        },

        getSelectedAccountLabel() {
            if (!this.form.user_id) return '-- Tidak ada akun --';
            if (this.isEdit && this.currentAccount && this.form.user_id == this.currentAccount.id) {
                return this.currentAccount.username + ' (' + (this.currentAccount.email || '-') + ') — [saat ini]';
            }
            const found = this.allUsers.find((u) => u.id == this.form.user_id);
            return found ? (found.username + ' (' + (found.email || '-') + ')') : '-- Tidak ada akun --';
        },

        getFilteredSortedUsers(query) {
            let list = [...this.allUsers];
            const q = lower(query).trim();
            if (q) {
                list = list.filter((u) => lower(u.username).includes(q) || lower(u.email).includes(q));
            }
            return list.sort((a, b) => str(a.username).localeCompare(str(b.username), undefined, { sensitivity: 'base' }));
        },

        // =================================================================
        // Foto + crop
        // =================================================================
        initAdminCrop(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validasi format: Hanya JPG, JPEG, PNG
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            const allowedExts = ['.jpg', '.jpeg', '.png'];
            const fileExt = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();

            if (!allowedTypes.includes(file.type) && !allowedExts.includes(fileExt)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format File Tidak Didukung',
                    text: 'Foto profil hanya boleh menggunakan format JPG, JPEG, atau PNG.',
                    confirmButtonColor: '#2563eb'
                });
                e.target.value = '';
                return;
            }

            // Validasi ukuran: Maksimal 2 MB
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ukuran Terlalu Besar',
                    text: 'Ukuran foto maksimal adalah 2 MB. Silakan pilih foto dengan ukuran lebih kecil.',
                    confirmButtonColor: '#2563eb'
                });
                e.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (evt) => {
                const img = this.$refs.adminCropImg;
                img.src = evt.target.result;
                this.cropModalOpen = true;

                this.$nextTick(() => {
                    if (this.adminCropper) this.adminCropper.destroy();
                    this.adminCropper = new Cropper(img, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.9,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                    });
                });
            };
            reader.readAsDataURL(file);
            e.target.value = ''; // supaya memilih file yang sama bisa memicu change lagi
        },

        closeAdminCrop() {
            this.cropModalOpen = false;
            if (this.adminCropper) {
                this.adminCropper.destroy();
                this.adminCropper = null;
            }
        },

        applyAdminCrop() {
            if (!this.adminCropper) return;
            const canvas = this.adminCropper.getCroppedCanvas({
                width: 500,
                height: 500,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            if (!canvas) return;

            const base64 = canvas.toDataURL('image/jpeg', 0.9);
            this.photoPreview = base64;
            this.croppedFoto = base64;
            this.closeAdminCrop();
        },

        // =================================================================
        // Modal tambah / edit / hapus
        // =================================================================
        openCreateModal() {
            this.isEdit = false;
            this.activeTab = 'data';
            this.submitting = false;
            this.formAction = this.storeUrl;
            this.form = emptyForm(this.today());
            this.currentAccount = null;
            this.photoPreview = null;
            this.croppedFoto = '';
            this.histories = [];
            this.modalOpen = true;
        },

        /** Data lama mungkin belum punya bidang_id: tebak dari unit_kerja jabatan. */
        guessBidangId(p) {
            if (p.bidang_id) return p.bidang_id;
            if (!(p.jabatan && p.jabatan.unit_kerja)) return '';

            const u = lower(p.jabatan.unit_kerja).trim();
            const matched = this.allBidangs.find((b) => {
                const bs = lower(b.singkatan).trim();
                const bn = lower(b.nama_bidang).trim();
                return (bs && (u === bs || u.includes(bs) || bs.includes(u)))
                    || (bn && (u.includes(bn) || bn.includes(u)));
            });
            return matched ? matched.id : '';
        },

        openEditModal(p) {
            this.isEdit = true;
            this.activeTab = 'data';
            this.submitting = false;
            this.formAction = `${this.baseUrl}/${p.id}`;
            this.croppedFoto = '';

            this.form = {
                nama: p.nama, gelar_depan: p.gelar_depan || '', gelar_belakang: p.gelar_belakang || '',
                nip: p.nip, nik: p.nik, alamat: p.alamat, tempat_lahir: p.tempat_lahir,
                tanggal_lahir: p.tanggal_lahir ? p.tanggal_lahir.split('T')[0] : '',
                jenis_kelamin: p.jenis_kelamin, agama: p.agama, status_kepegawaian: p.status_kepegawaian,
                status_kerja: p.status_kerja || 'Aktif',
                jabatan_id: p.jabatan_id, bidang_id: this.guessBidangId(p), golongan: p.golongan || '',
                mkg_tahun: isManualMkg(p.mkg_tahun) ? p.mkg_tahun : '',
                mkg_bulan: isManualMkg(p.mkg_bulan) ? p.mkg_bulan : '',
                status_pernikahan: p.status_pernikahan,
                tanggal_berlaku: this.today(),
                user_id: p.user_id || '',
            };
            this.currentAccount = p.user;
            this.photoPreview = p.foto ? this.storageBase + p.foto : null;
            this.histories = p.histories || [];
            this.modalOpen = true;
        },

        confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data pegawai akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (!result.isConfirmed) return;
                const form = document.getElementById('delete-form');
                form.action = `${this.baseUrl}/${id}`;
                form.submit();
            });
        },

        // =================================================================
        // Export
        // =================================================================
        exportPdf() {
            // Server membuat PDF sesuai pencarian, urutan, dan kolom yang tampil di layar
            const params = new URLSearchParams({
                search: this.search || '',
                sort: this.sortCol,
                dir: this.sortAsc ? 'asc' : 'desc',
            });
            Object.keys(this.columns)
                .filter((k) => this.columns[k].visible)
                .forEach((k) => params.append('columns[]', k));

            window.location.href = `${this.exportPdfUrl}?${params.toString()}`;
        },

        exportExcel() {
            const e = escapeHtml;
            const cell = 'border: 1px solid #000000; vertical-align: middle;';
            const center = cell + ' text-align: center;';
            const text = cell + " mso-number-format:'\\@';";

            const rows = this.allPegawai.map((p, idx) => {
                const jab = p.jabatan ? p.jabatan.nama_jabatan : '-';
                const tglLahir = p.tanggal_lahir ? p.tanggal_lahir.split('T')[0] : '-';

                return `<tr>
                    <td style="${center}">${idx + 1}</td>
                    <td style="${text}">${e(p.nip || '-')}</td>
                    <td style="${cell}">${e(this.namaLengkap(p))}</td>
                    <td style="${text}">${e(this.getMkgDisplay(p))}</td>
                    <td style="${text}">${e(p.nik || '-')}</td>
                    <td style="${cell}">${e(p.tempat_lahir || '-')}</td>
                    <td style="${center}">${e(tglLahir)}</td>
                    <td style="${center}">${e(p.jenis_kelamin || '-')}</td>
                    <td style="${center}">${e(p.agama || '-')}</td>
                    <td style="${center}">${e(p.status_kepegawaian || '-')}</td>
                    <td style="${cell}">${e(jab)}</td>
                    <td style="${center}">${e(p.golongan || '-')}</td>
                    <td style="${cell}">${e(this.golonganPangkat(p.golongan))}</td>
                    <td style="${center}">${e(p.status_pernikahan || '-')}</td>
                    <td style="${cell}">${e(p.alamat || '-')}</td>
                </tr>`;
            }).join('');

            const headers = ['No', 'NIP', 'Nama Lengkap', 'MKG', 'NIK', 'Tempat Lahir', 'Tanggal Lahir', 'L/P', 'Agama',
                'Status Kepegawaian', 'Jabatan', 'Golongan', 'Pangkat', 'Status Nikah', 'Alamat'];
            const th = (t) => `<th style="border: 1px solid #000000; padding: 8px; text-align: center; background-color: #000000; color: #ffffff;">${t}</th>`;

            const html = `
                <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <!--[if gte mso 9]>
                    <xml>
                        <x:ExcelWorkbook>
                            <x:ExcelWorksheets>
                                <x:ExcelWorksheet>
                                    <x:Name>Data Pegawai</x:Name>
                                    <x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>
                                </x:ExcelWorksheet>
                            </x:ExcelWorksheets>
                        </x:ExcelWorkbook>
                    </xml>
                    <![endif]-->
                </head>
                <body>
                    <h2 style="font-family: Arial, sans-serif; color: #000000;">Data Pegawai BAPERAN BAPPERIDA</h2>
                    <table border="1" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11px;">
                        <thead><tr style="background-color: #000000; color: #ffffff;">${headers.map(th).join('')}</tr></thead>
                        <tbody>${rows}</tbody>
                    </table>
                </body>
                </html>`;

            const blob = new Blob([html], { type: 'application/vnd.ms-excel;charset=utf-8' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `Data_Pegawai_BAPERAN_${this.today()}.xls`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            setTimeout(() => URL.revokeObjectURL(link.href), 1000);
        },
    };
}

// Daftarkan ke Alpine. Aman dipanggil sebelum maupun sesudah Alpine dimuat.
(function () {
    const register = () => window.Alpine.data('pegawaiCrud', pegawaiCrud);

    if (window.Alpine && typeof window.Alpine.data === 'function') {
        register();
    } else {
        document.addEventListener('alpine:init', register);
    }
})();

})();
