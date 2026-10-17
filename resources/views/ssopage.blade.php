@php
    $profile = $profile ?? null;
    $nama = $profile['nama'] ?? null;
    $jabatan = $profile['jabatan'] ?? null;

    $apps = array_merge([
        [
            'name' => 'BAPERAN',
            'desc' => 'Basis data personalia ASN: data pegawai, jabatan, keluarga, dan dokumen kepegawaian.',
            'url'  => auth()->check() ? route('dashboard') : route('login'),
            'icon' => 'icons/pns.webp',
        ],
    ], config('sso.apps', []));

    // Ubah nilai 'icon' (path relatif terhadap public/) menjadi URL file .webp.
    // Toleran terhadap key 'icons' (typo) dan ekstensi yang dihilangkan.
    $resolveIcon = function (array $a) {
        $icon = $a['icon'] ?? $a['icons'] ?? ('icons/' . strtolower($a['name'] ?? 'default'));
        $icon = ltrim((string) $icon, '/');
        if (! pathinfo($icon, PATHINFO_EXTENSION)) {
            $icon .= '.webp';
        }
        return asset($icon);
    };

    $items = [];
    foreach ($apps as $a) {
        if (empty($a['name']) || empty($a['url'])) {
            continue;
        }
        $items[] = [
            'name' => $a['name'],
            'desc' => $a['desc'] ?? 'Aplikasi terintegrasi SSO BAPERAN.',
            'url'  => $a['url'],
            'icon' => $resolveIcon($a),
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Kepegawaian ASN</title>
    <link rel="icon" type="image/png" href="{{ asset('pemkot.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sso.css') }}">
    <style>
        /* Mode gelap: ikon .webp yang gelap diberi alas terang agar tetap terlihat */
        .dark .hub-orb .nd .cr img.ic {
            background: #f1f5f9;
            border-radius: 9999px;
            box-sizing: border-box;
            padding: 12%;
        }
        /* Alternatif untuk ikon satu warna (hitam): jadikan putih di mode gelap
        .dark .hub-orb .nd .cr img.ic { filter: brightness(0) invert(1); }
        */
    </style>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <header class="top">
        <div class="brand">
            <img src="{{ asset('pemkot.webp') }}" alt="Pemerintah Kota Pekalongan">
            <div>
                <b>Bapperida</b>
                <small>Kota Pekalongan</small>
            </div>
        </div>

        <div class="right">
            @if ($nama)
                <div class="user">
                    <div class="user-text">
                        <b title="{{ $nama }}">{{ $nama }}</b>
                        @if ($jabatan)
                            <small title="{{ $jabatan }}">{{ $jabatan }}</small>
                        @endif
                    </div>
                </div>
            @endif

            <button type="button" class="tbtn" title="Ganti tema" aria-label="Ganti tema"
                    onclick="localStorage.theme = document.documentElement.classList.toggle('dark') ? 'dark' : 'light'">
                <svg class="moon" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>
                <svg class="sun" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
            </button>

            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="tbtn">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="tbtn">Masuk</a>
            @endauth
        </div>
    </header>

    <main class="flex min-h-screen flex-col-reverse items-center justify-center gap-6 px-6 pb-10 pt-24 md:flex-row md:justify-between md:gap-8 md:px-12 lg:px-20">
        <section class="w-full max-w-md text-center md:text-left">
            @if ($nama)
                <p class="greet">Selamat datang, <b>{{ $nama }}</b>@if ($jabatan)<br>{{ $jabatan }}@endif</p>
            @endif
            <div id="eb" class="text-xs font-bold uppercase tracking-widest" style="color: var(--accent)"></div>
            <h1 id="nm" class="mt-3 text-4xl font-extrabold leading-tight md:text-6xl"></h1>
            <p id="ds" class="mt-3 min-h-[4.5rem] text-sm leading-relaxed md:text-base" style="color: var(--muted)"></p>
            <div id="dots" class="mt-6 flex justify-center gap-1.5 md:justify-start"></div>
        </section>

        <div id="orb" class="hub-orb" tabindex="0" role="listbox" aria-label="Daftar aplikasi">
            <div class="hub-ring"></div>
            <div class="hub-center">
                <img src="{{ asset('pemkot.webp') }}" alt="Pemerintah Kota Pekalongan">
                <span>Bapperida<br>Kota Pekalongan</span>
            </div>
        </div>
    </main>
    <script>
        (function () {
            var apps = @json($items);
            var N = apps.length;
            if (!N) return;
            var STEP = 360 / N, RP = 37, rot = 0, sel = -1, raf = 0;
            var orb = document.getElementById('orb'), dots = document.getElementById('dots');

            // Cadangan bila file .webp gagal dimuat
            var FALLBACK = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:60%;height:60%"><rect x="4" y="4" width="7" height="7" rx="1"/><rect x="13" y="4" width="7" height="7" rx="1"/><rect x="4" y="13" width="7" height="7" rx="1"/><rect x="13" y="13" width="7" height="7" rx="1"/></svg>';

            function esc(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

            function makeIcon(src) {
                var img = document.createElement('img');
                img.src = src;
                img.className = 'ic';
                img.alt = '';
                img.draggable = false;
                img.style.cssText = 'width:60%;height:60%;object-fit:contain;pointer-events:none;user-select:none';
                img.onerror = function () {
                    var wrap = img.parentNode;
                    if (wrap) wrap.innerHTML = FALLBACK;
                };
                return img;
            }

            var nodes = apps.map(function (a, k) {
                var nd = document.createElement('div');
                nd.className = 'nd';
                nd.dataset.k = k;
                nd.setAttribute('role', 'option');
                nd.innerHTML = '<div class="cr"></div><div class="lb">' + esc(a.name) + '<span class="hint">Klik untuk membuka</span></div>';
                nd.querySelector('.cr').appendChild(makeIcon(a.icon));
                orb.appendChild(nd);
                var d = document.createElement('i');
                d.className = 'dot';
                dots.appendChild(d);
                return nd;
            });

            function norm(a) { return ((a + 180) % 360 + 360) % 360 - 180; }

            function render() {
                var best = 0, bd = 999;
                nodes.forEach(function (nd, k) {
                    var a = norm(k * STEP + rot), r = a * Math.PI / 180;
                    nd.style.left = (50 + RP * Math.cos(r)) + '%';
                    nd.style.top = (50 + RP * Math.sin(r)) + '%';
                    if (Math.abs(a) < bd) { bd = Math.abs(a); best = k; }
                });
                if (best === sel) return;
                sel = best;
                nodes.forEach(function (nd, k) {
                    nd.classList.toggle('on', k === sel);
                    nd.setAttribute('aria-selected', k === sel ? 'true' : 'false');
                    dots.children[k].classList.toggle('on', k === sel);
                });
                var a = apps[sel];
                document.getElementById('nm').textContent = a.name;
                document.getElementById('ds').textContent = a.desc;
            }

            function animTo(t) {
                cancelAnimationFrame(raf);
                var s = rot, t0 = performance.now(), D = 450;
                (function f(now) {
                    var p = Math.min(1, (now - t0) / D), e = 1 - Math.pow(1 - p, 3);
                    rot = s + (t - s) * e;
                    render();
                    if (p < 1) raf = requestAnimationFrame(f);
                })(t0);
            }
            function snapTo(k) { animTo(rot + norm(-k * STEP - rot)); }
            function nearest() {
                var b = 0, bd = 999;
                for (var k = 0; k < N; k++) {
                    var d = Math.abs(norm(k * STEP + rot));
                    if (d < bd) { bd = d; b = k; }
                }
                return b;
            }
            function ang(e) {
                var r = orb.getBoundingClientRect();
                return Math.atan2(e.clientY - (r.top + r.height / 2), e.clientX - (r.left + r.width / 2)) * 180 / Math.PI;
            }
            function open(k) { window.location.href = apps[k].url; }

            var down = false, moved = false, sA = 0, sR = 0, tk = null;
            orb.addEventListener('pointerdown', function (e) {
                cancelAnimationFrame(raf);
                down = true; moved = false; sA = ang(e); sR = rot;
                var n = e.target.closest('.nd');
                tk = n ? +n.dataset.k : null;
                orb.setPointerCapture(e.pointerId);
                orb.classList.add('drag');
            });
            orb.addEventListener('pointermove', function (e) {
                if (!down) return;
                var d = norm(ang(e) - sA);
                if (Math.abs(d) > 3) moved = true;
                if (moved) { rot = sR + d; render(); }
            });
            function end() {
                if (!down) return;
                down = false;
                orb.classList.remove('drag');
                if (moved || tk === null) { snapTo(nearest()); return; }
                if (tk === sel && Math.abs(norm(tk * STEP + rot)) < 1) { open(tk); return; }
                snapTo(tk);
            }
            orb.addEventListener('pointerup', end);
            orb.addEventListener('pointercancel', end);
            orb.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') { e.preventDefault(); snapTo((sel + 1) % N); }
                if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') { e.preventDefault(); snapTo((sel - 1 + N) % N); }
                if (e.key === 'Enter') { e.preventDefault(); open(sel); }
            });

            render();
        })();
    </script>
</body>
</html>