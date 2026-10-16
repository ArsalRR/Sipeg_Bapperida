<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - BAPERAN ASN</title>
    <link rel="icon" type="image/webp" href="{{ asset('pemkot.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="{{ asset('js/login.js') }}" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    $isRegister = old('_form') === 'register';

    $forms = [
        'login' => [
            'action' => route('login'),
            'submit' => 'Masuk',
            'fields' => [
                ['id' => 'login', 'name' => 'login', 'label' => 'Email atau Username', 'type' => 'text', 'autocomplete' => 'username', 'keep' => true],
                ['id' => 'password', 'name' => 'password', 'label' => 'Kata Sandi', 'type' => 'password', 'autocomplete' => 'current-password'],
                ['id' => 'captcha', 'name' => 'captcha', 'label' => 'Masukkan kode pada gambar', 'type' => 'text', 'autocomplete' => 'off'],
            ],
        ],
        'register' => [
            'action' => route('register'),
            'submit' => 'Daftar',
            'fields' => [
                ['id' => 'reg_username', 'name' => 'username', 'label' => 'Username', 'type' => 'text', 'autocomplete' => 'username', 'keep' => true],
                ['id' => 'reg_email', 'name' => 'email', 'label' => 'Email', 'type' => 'email', 'autocomplete' => 'email', 'keep' => true],
                ['id' => 'reg_password', 'name' => 'password', 'label' => 'Kata Sandi', 'type' => 'password', 'autocomplete' => 'new-password'],
                ['id' => 'reg_password_confirmation', 'name' => 'password_confirmation', 'label' => 'Konfirmasi Kata Sandi', 'type' => 'password', 'autocomplete' => 'new-password'],
            ],
        ],
    ];
@endphp

<body class="min-h-screen bg-gray-50 font-sans text-slate-900 antialiased transition-colors duration-200 selection:bg-blue-600 selection:text-white dark:bg-slate-950 dark:text-white">

    <button type="button"
            id="themeToggle"
            role="switch"
            aria-checked="false"
            aria-label="Ganti tema terang/gelap"
            title="Ganti tema terang/gelap"
            class="theme-switch">

        <span class="theme-knob">
            <svg class="icon-sun h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
            </svg>

            <svg class="icon-moon h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
            </svg>
        </span>
    </button>

    <div class="flex min-h-screen w-full">

       <main class="flex w-full flex-col justify-center px-4 pb-16 pt-44 sm:px-8 lg:w-1/2">

            <div class="mx-auto w-full max-w-md">

                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl shadow-gray-200/50 dark:border-slate-800 dark:bg-slate-900 dark:shadow-black/20">

                    <div class="px-6 pb-5 pt-8 text-center sm:px-8 sm:pt-10">

                        <div class="text-3xl font-extrabold leading-none tracking-tight">
                            BAPERAN
                        </div>

                        <div class="mt-2 text-[10px] font-bold uppercase tracking-[0.19em] text-gray-400 dark:text-gray-500">
                            Aplikasi Basis Data Personalia ASN
                        </div>

                        <div class="mx-auto mt-5 h-1 w-12 rounded-full bg-blue-600"></div>

                        <h1 id="formTitle" class="mt-6 text-2xl font-bold tracking-tight">
                            {{ $isRegister ? 'Buat Akun Baru' : 'Selamat Datang Kembali!' }}
                        </h1>

                        <p id="formSubtitle" class="mt-2 text-sm leading-relaxed text-gray-500 dark:text-gray-400">
                            {{ $isRegister ? 'Isi data berikut untuk mendaftar.' : 'Silakan masuk menggunakan Akun Anda.' }}
                        </p>

                    </div>

                    <div class="px-6 pb-6 sm:px-8">
                        <div id="tabList" role="tablist" class="seg {{ $isRegister ? 'is-register' : '' }}">

                            <span class="seg-thumb"></span>

                            @foreach (['login' => 'Masuk', 'register' => 'Daftar'] as $key => $label)
                                <button type="button"
                                        role="tab"
                                        data-tab="{{ $key }}"
                                        aria-selected="{{ (($key === 'register') === $isRegister) ? 'true' : 'false' }}"
                                        class="seg-tab">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @foreach ($forms as $key => $form)
                        @php
                            $active = ($key === 'register') === $isRegister;
                        @endphp

                        <form id="panel-{{ $key }}"
                              method="POST"
                              action="{{ $form['action'] }}"
                              class="{{ $active ? '' : 'hidden' }}">

                            @csrf

                            @if ($key === 'register')
                                <input type="hidden" name="_form" value="register">
                            @endif

                            <div class="space-y-4 px-6 pb-6 sm:px-8">
                                @foreach ($form['fields'] as $field)
                                    <div>

                                        @if ($field['name'] === 'captcha')
                                            <div class="mb-2 flex items-center justify-between gap-3">
                                                <img id="captchaImage"
                                                     src="{{ captcha_src('flat') }}"
                                                     data-base="{{ url('captcha/flat') }}"
                                                     alt="Gambar captcha"
                                                     class="h-12 w-auto rounded-lg border border-gray-200 bg-white dark:border-slate-700">

                                                <button type="button"
                                                        id="captchaRefresh"
                                                        class="rounded-lg px-3 py-2 text-sm font-medium text-blue-600 transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:text-blue-400 dark:hover:bg-slate-800">
                                                    Muat ulang
                                                </button>
                                            </div>
                                        @endif

                                        <div class="flex items-center rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 transition focus-within:border-blue-600 focus-within:ring-2 focus-within:ring-blue-600/20 dark:border-slate-700 dark:bg-slate-950">

                                            <div class="min-w-0 flex-1">

                                                <label for="{{ $field['id'] }}"
                                                       class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">
                                                    {{ $field['label'] }}
                                                </label>

                                                <input
                                                    id="{{ $field['id'] }}"
                                                    name="{{ $field['name'] }}"
                                                    type="{{ $field['type'] }}"
                                                    autocomplete="{{ $field['autocomplete'] }}"
                                                    @if (!empty($field['keep']))
                                                        value="{{ old($field['name']) }}"
                                                    @endif
                                                    class="block w-full border-0 bg-transparent p-0 text-sm font-medium text-slate-900 placeholder:text-gray-400 focus:outline-none focus:ring-0 dark:text-white"
                                                    @if ($field['name'] === 'captcha') required autocapitalize="off" spellcheck="false" @endif
                                                >

                                            </div>

                                            @if ($field['type'] === 'password')
                                                <button type="button"
                                                        data-toggle-password="{{ $field['id'] }}"
                                                        aria-label="Tampilkan kata sandi"
                                                        title="Tampilkan/sembunyikan kata sandi"
                                                        class="-mr-2 ml-2 flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-200 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:text-gray-500 dark:hover:bg-slate-800 dark:hover:text-blue-400">

                                                    <svg data-eye class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                    </svg>

                                                    <svg data-eye-off class="hidden h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                                    </svg>
                                                </button>
                                            @endif

                                        </div>

                                        @if ($active)
                                            @error($field['name'])
                                                <p class="mt-1.5 text-xs text-red-500">
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        @endif

                                    </div>
                                @endforeach

                                <button type="submit"
                                        class="block w-full rounded-xl bg-blue-600 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-900">
                                    {{ $form['submit'] }}
                                </button>
                            </div>

                        </form>
                    @endforeach

                </div>

            </div>
        </main>

        <aside class="hidden w-1/2 items-center justify-center border-l border-gray-200 bg-gray-100 p-12 dark:border-slate-800 dark:bg-slate-900 lg:flex">

            <div class="max-w-md text-center">

                <img src="{{ asset('pemkot.webp') }}"
                     alt="Logo Pemerintah Kota Pekalongan"
                     class="mx-auto mb-8 h-28 w-auto">

                <h2 class="text-4xl font-extrabold tracking-tight">
                    Halo ASN!
                </h2>

                <p class="mt-4 leading-relaxed text-slate-600 dark:text-slate-300">
                    Data kepegawaian Anda tersimpan aman dan terpusat dalam satu sistem.
                </p>
            </div>

        </aside>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
       function showAlert(title, message) {
                var dark = root.classList.contains('dark');

                var icon = title === 'Berhasil' ? 'success' : 'error';

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: icon,
                    title: message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: dark ? '#0f172a' : '#ffffff',
                    color: dark ? '#ffffff' : '#0f172a'
                });
            }

            @if (session('success'))
                showAlert('Berhasil', @json(session('success')));
            @endif

            @if (session('error'))
                showAlert('Gagal', @json(session('error')));
            @endif
    </script>

</body>
</html>