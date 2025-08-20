// Dark mode initialization script
document.addEventListener('DOMContentLoaded', function () {
    // Check for saved dark mode preference or default to system preference
    const savedDarkMode = localStorage.getItem('darkMode');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    let isDarkMode = false;

    if (savedDarkMode !== null) {
        isDarkMode = JSON.parse(savedDarkMode);
    } else {
        isDarkMode = systemPrefersDark;
        localStorage.setItem('darkMode', JSON.stringify(isDarkMode));
    }

    // Apply dark mode class immediately to prevent flash
    if (isDarkMode) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Listen for system theme changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (localStorage.getItem('darkMode') === null) {
            const isDark = e.matches;
            localStorage.setItem('darkMode', JSON.stringify(isDark));
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    });
});