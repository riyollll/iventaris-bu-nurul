const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
const sidebarClose = document.querySelectorAll('[data-sidebar-close]');

function setSidebarOpen(isOpen) {
    document.body.classList.toggle('sidebar-open', isOpen);
    sidebarToggle?.setAttribute('aria-expanded', String(isOpen));
}

sidebarToggle?.addEventListener('click', () => {
    setSidebarOpen(!document.body.classList.contains('sidebar-open'));
});

sidebarClose.forEach((element) => {
    element.addEventListener('click', () => setSidebarOpen(false));
});

document.querySelectorAll('.app-sidebar a').forEach((link) => {
    link.addEventListener('click', () => setSidebarOpen(false));
});

const themeToggle = document.querySelector('[data-theme-toggle]');
const savedTheme = localStorage.getItem('theme');

if (savedTheme === 'dark') {
    document.documentElement.classList.add('dark');
}

themeToggle?.addEventListener('click', () => {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
});
