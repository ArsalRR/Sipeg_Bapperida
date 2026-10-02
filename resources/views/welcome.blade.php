@php $bg = 'bappeda2.jpg'; @endphp
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
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        .page { background: #1e3a5f url('{{ asset($bg) }}') center / cover no-repeat; }
        .page::before { content: ''; position: absolute; inset: 0; background: rgba(14, 56, 110, .55); transition: background .3s; }
        html.dark .page::before { background: rgba(6, 10, 20, .8); }
        .t-shadow { text-shadow: 0 2px 10px rgba(0, 0, 0, .65); }
        .tools { position: fixed; top: 1rem; right: 1rem; z-index: 50; display: flex; align-items: center; gap: .5rem; }
        .fab {
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            height: 2.75rem; min-width: 2.75rem; padding: 0; border: 0; border-radius: 9999px;
            font: 700 .875rem/1 Inter, system-ui, sans-serif; cursor: pointer;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .45), 0 0 0 1px rgba(0, 0, 0, .12);
            transition: transform .15s, background .15s;
        }
        .fab:hover { transform: scale(1.06); }
        .fab svg { width: 1.25rem; height: 1.25rem; flex: none; display: block; }

        .fab-theme { background: #fff; color: #1e293b; }
        .fab-theme .sun { display: none; }
        html.dark .fab-theme { background: #1e293b; color: #fcd34d; box-shadow: 0 6px 18px rgba(0, 0, 0, .6), 0 0 0 1px rgba(255, 255, 255, .3); }
        html.dark .fab-theme .sun { display: block; }
        html.dark .fab-theme .moon { display: none; }

        .fab-out { background: #dc2626; color: #fff; padding: 0 1.1rem 0 .95rem; }
        .fab-out:hover { background: #b91c1c; }
        .hub-card { background: #fff;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,.6), 0 0 0 1px rgba(15,23,42,.1); }
        .hub-card:hover { box-shadow: 0 30px 60px -10px rgba(0,0,0,.75), 0 0 0 3px #7dc4f5; }
        .c-name { color: #5a3b41; }
        .c-desc { color: #4b5563; }
        .c-go   { color: #b4452b; border-top: 1px solid #eadfe0; }

        html.dark .hub-card { background: #241c1f;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,.9), 0 0 0 1px rgba(255,255,255,.12); }
        html.dark .hub-card:hover { box-shadow: 0 30px 60px -10px rgba(0,0,0,.95), 0 0 0 3px #e2795a; }
        html.dark .c-name { color: #f4c3b5; }
        html.dark .c-desc { color: #d6cccd; }
        html.dark .c-go   { color: #f09a80; border-top-color: #3d3033; }

        .hub {
            --logo: min(34vmin, 15rem);  
            --cw: min(19rem, 44vw);       
            --ch: 9rem;                  
            --gap: 1rem;
            --H: calc(var(--logo) + 4rem);
            --X: calc(var(--logo) / 2 + var(--cw) / 2 + var(--gap));
            --Y: calc(var(--H) / 2 + var(--ch) / 2 + var(--gap));
            position: absolute; left: 50%; top: 50%; translate: -50% -50%;
            width: calc(var(--logo) + 2 * (var(--cw) + 2 * var(--gap)));
            height: calc(var(--H) + 2 * (var(--ch) + 2 * var(--gap)));
        }
        @media (max-width: 639px) {
            .hub { --X: calc(var(--cw) / 2 + var(--gap) / 2); width: 100vw; }
        }

        .hub-card {
            position: absolute; left: 50%; top: 50%;
            width: var(--cw); height: var(--ch);
            margin: calc(var(--ch) / -2) 0 0 calc(var(--cw) / -2);
            opacity: 0; pointer-events: none;
            transform: translate(0, 0) scale(.2);
            transition: transform .5s cubic-bezier(.34, 1.56, .64, 1), opacity .3s, box-shadow .2s;
        }
        .hub-card:nth-child(1) { --dx: 0;  --dy: -1; }
        .hub-card:nth-child(2) { --dx: 1;  --dy: 0; }
        .hub-card:nth-child(3) { --dx: 0;  --dy: 1; }
        .hub-card:nth-child(4) { --dx: -1; --dy: 0; }
        @media (max-width: 639px) {
            .hub-card:nth-child(1) { --dx: -1; --dy: -1; }
            .hub-card:nth-child(2) { --dx: 1;  --dy: -1; }
            .hub-card:nth-child(3) { --dx: -1; --dy: 1; }
            .hub-card:nth-child(4) { --dx: 1;  --dy: 1; }
        }

        .hub:hover .hub-card,
        .hub:focus-within .hub-card {
            opacity: 1; pointer-events: auto;
            transform: translate(calc(var(--dx) * var(--X)), calc(var(--dy) * var(--Y))) scale(1);
            transition-delay: calc(var(--i) * 70ms);
        }

        @media (prefers-reduced-motion: reduce) { .hub-card, .fab { transition: none; } }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    $items = array_slice(array_merge([
        [
            'name' => 'BAPERAN',
            'desc' => 'Basis data personalia ASN: data pegawai, jabatan, keluarga, dan dokumen kepegawaian.',
            'url'  => auth()->check() ? route('dashboard') : route('login'),
        ],
    ], config('sso.apps', [])), 0, 4);

    while (count($items) < 4) {
        $items[] = ['name' => 'Aplikasi ' . (count($items) + 1), 'desc' => 'Slot aplikasi SSO berikutnya akan tampil di sini.', 'url' => null];
    }
@endphp

<body class="bg-slate-900 font-sans antialiased">
    <div class="tools">
        <button type="button" class="fab fab-theme" title="Ganti tema" aria-label="Ganti tema"
                onclick="localStorage.theme = document.documentElement.classList.toggle('dark') ? 'dark' : 'light'">
            <svg class="moon" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 12.8A9 9 0 1111.2 3a7 7 0 009.8 9.8z"/></svg>
            <svg class="sun" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
        </button>

        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="fab fab-out" title="Logout">
                    <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    <span>Keluar</span>
                </button>
            </form>
        @endauth
    </div>

    <main class="page fixed inset-0 overflow-hidden">
        <div class="hub group">
            <div class="absolute left-1/2 top-1/2 z-10 flex -translate-x-1/2 -translate-y-1/2 flex-col items-center text-center"
                 style="width: var(--logo); gap: .75rem">
                <img src="{{ asset('pemkot.webp') }}" alt="Pemerintah Kota Pekalongan"
                     class="object-contain drop-shadow-[0_10px_25px_rgba(0,0,0,.5)]"
                     style="height: var(--logo); width: var(--logo)">
                <span class="t-shadow text-balance text-sm font-extrabold uppercase leading-tight tracking-wide text-white md:text-base">
                    Bapperida Kota Pekalongan
                </span>
            </div>
            <div>
                @foreach ($items as $item)
                    <a @if (!empty($item['url'])) href="{{ $item['url'] }}" @else aria-disabled="true" @endif
                       class="hub-card flex flex-col rounded-2xl p-4 {{ empty($item['url']) ? 'opacity-60' : '' }}"
                       style="--i: {{ $loop->index }}">
                        <span class="c-name text-lg font-extrabold leading-tight md:text-xl">{{ $item['name'] }}</span>
                        <span class="c-desc mt-1 line-clamp-3 text-xs leading-snug md:text-sm">{{ $item['desc'] ?? 'Aplikasi terintegrasi SSO BAPERAN.' }}</span>
                        <span class="c-go mt-auto pt-2 text-xs font-bold md:text-sm">{{ empty($item['url']) ? 'Segera hadir' : 'Buka aplikasi →' }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </main>

</body>
</html>