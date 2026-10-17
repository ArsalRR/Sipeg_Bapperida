(function () {
    var root = document.documentElement;
    var saved = null;

    try {
        saved = localStorage.getItem('theme');
    } catch (e) {}

    var initialDark = saved
        ? saved === 'dark'
        : window.matchMedia('(prefers-color-scheme: dark)').matches;

    root.classList.toggle('dark', initialDark);

    var themeToggle = document.getElementById('themeToggle');
    themeToggle.setAttribute('aria-checked', String(root.classList.contains('dark')));

    themeToggle.addEventListener('click', function () {
        var dark = !root.classList.contains('dark');

        root.classList.toggle('dark', dark);
        themeToggle.setAttribute('aria-checked', String(dark));

        try {
            localStorage.setItem('theme', dark ? 'dark' : 'light');
        } catch (e) {}
    });

    var tabs = document.querySelectorAll('[data-tab]');
    var tabList = document.getElementById('tabList');

    var copy = {
        login: [
            'Selamat Datang Kembali!',
            'Silakan masuk menggunakan Akun Anda.'
        ],
        register: [
            'Buat Akun Baru',
            'Isi data berikut untuk mendaftar.'
        ]
    };

    function setTab(name) {
        tabList.classList.toggle('is-register', name === 'register');

        tabs.forEach(function (tab) {
            tab.setAttribute(
                'aria-selected',
                String(tab.dataset.tab === name)
            );
        });

        ['login', 'register'].forEach(function (key) {
            document.getElementById('panel-' + key)
                .classList.toggle('hidden', key !== name);
        });

        document.getElementById('formTitle').textContent = copy[name][0];
        document.getElementById('formSubtitle').textContent = copy[name][1];
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            setTab(tab.dataset.tab);
        });
    });

    document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
        button.addEventListener('click', function () {
            var input = document.getElementById(button.dataset.togglePassword);
            var show = input.type === 'password';

            input.type = show ? 'text' : 'password';

            button.querySelector('[data-eye]').classList.toggle('hidden', show);
            button.querySelector('[data-eye-off]').classList.toggle('hidden', !show);
            button.setAttribute(
                'aria-label',
                show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'
            );
        });
    });

    var captchaImage = document.getElementById('captchaImage');
    var captchaRefresh = document.getElementById('captchaRefresh');

    if (captchaImage && captchaRefresh) {
        captchaRefresh.addEventListener('click', function () {
            captchaImage.src = captchaImage.dataset.base + '?' + Math.random();

            var input = document.getElementById('captcha');
            input.value = '';
            input.focus();
        });
    }
})();