document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const savedTheme = localStorage.getItem('pizzaria-theme') || 'light';
    body.dataset.theme = savedTheme;

    const toggle = document.querySelector('.theme-toggle');
    if (toggle) {
        toggle.textContent = body.dataset.theme === 'dark' ? '☀️ Tema' : '🌙 Tema';
        toggle.addEventListener('click', function () {
            const nextTheme = body.dataset.theme === 'dark' ? 'light' : 'dark';
            body.dataset.theme = nextTheme;
            localStorage.setItem('pizzaria-theme', nextTheme);
            toggle.textContent = nextTheme === 'dark' ? '☀️ Tema' : '🌙 Tema';
        });
    }

    const mobileToggle = document.querySelector('.mobile-toggle');
    const sidebar = document.querySelector('.sidebar');
    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', function () {
            sidebar.classList.toggle('is-open');
        });

        document.addEventListener('click', function (event) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('is-open') && !sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                sidebar.classList.remove('is-open');
            }
        });
    }
});