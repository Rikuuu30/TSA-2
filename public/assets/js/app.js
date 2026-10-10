(() => {
    const root = document.documentElement;
    const themeButton = document.getElementById('theme-toggle');
    const readPreference = (key) => {
        try {
            return localStorage.getItem(key);
        } catch (error) {
            return null;
        }
    };

    const setTheme = (theme) => {
        const isDark = theme === 'dark';
        root.dataset.theme = isDark ? 'dark' : 'light';

        if (themeButton) {
            themeButton.setAttribute('aria-pressed', String(isDark));
            themeButton.setAttribute('aria-label', `Switch to ${isDark ? 'light' : 'dark'} appearance`);
            themeButton.querySelector('.theme-toggle-icon').textContent = isDark ? '☼' : '☾';
            themeButton.querySelector('.theme-toggle-label').textContent = isDark ? 'Light' : 'Dark';
        }
    };

    setTheme(root.dataset.theme || 'light');

    themeButton?.addEventListener('click', () => {
        const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
        setTheme(nextTheme);

        try {
            localStorage.setItem('tasks-today-theme', nextTheme);
        } catch (error) {
            // The appearance still changes for this page even if storage is unavailable.
        }
    });

    const focusButton = document.getElementById('focus-mode-toggle');
    const setFocusMode = (enabled) => {
        root.classList.toggle('focus-mode', enabled);
        if (focusButton) {
            focusButton.setAttribute('aria-pressed', String(enabled));
            focusButton.innerHTML = `<span aria-hidden="true">${enabled ? '◉' : '◎'}</span> ${enabled ? 'Show completed' : 'Focus mode'}`;
        }
    };
    setFocusMode(readPreference('tasks-today-focus') === 'true');
    focusButton?.addEventListener('click', () => {
        const enabled = !root.classList.contains('focus-mode');
        setFocusMode(enabled);
        try {
            localStorage.setItem('tasks-today-focus', String(enabled));
        } catch (error) {
            // The current page still responds if browser storage is unavailable.
        }
    });

    const densityButton = document.getElementById('density-toggle');
    const setCompact = (enabled) => {
        document.body.classList.toggle('compact-schedule', enabled);
        if (densityButton) {
            densityButton.setAttribute('aria-pressed', String(enabled));
            densityButton.innerHTML = `<span aria-hidden="true">${enabled ? '☰' : '☷'}</span> ${enabled ? 'Comfortable view' : 'Compact view'}`;
        }
    };
    setCompact(readPreference('tasks-today-compact') === 'true');
    densityButton?.addEventListener('click', () => {
        const enabled = !document.body.classList.contains('compact-schedule');
        setCompact(enabled);
        try {
            localStorage.setItem('tasks-today-compact', String(enabled));
        } catch (error) {
            // The current page still responds if browser storage is unavailable.
        }
    });

    const clock = document.querySelector('[data-live-clock]');
    if (clock) {
        const updateClock = () => {
            clock.textContent = new Intl.DateTimeFormat('en-PH', {
                timeZone: 'Asia/Manila',
                hour: 'numeric',
                minute: '2-digit',
                second: '2-digit',
            }).format(new Date());
        };
        updateClock();
        window.setInterval(updateClock, 1000);
    }

    const searchField = document.querySelector('input[name="q"]');
    document.addEventListener('keydown', (event) => {
        const typing = event.target instanceof HTMLElement
            && (event.target.isContentEditable || ['INPUT', 'TEXTAREA', 'SELECT'].includes(event.target.tagName));
        const quickSearch = searchField && ((!event.ctrlKey && !event.metaKey && event.key === '/')
            || ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k'));
        if (quickSearch && !typing) {
            event.preventDefault();
            searchField.focus();
            searchField.select();
        }
    });

    let scrollTicking = false;
    const updateScrollProgress = () => {
        const scrollable = document.documentElement.scrollHeight - window.innerHeight;
        const progress = scrollable > 0 ? Math.min(1, Math.max(0, window.scrollY / scrollable)) : 0;
        root.style.setProperty('--scroll-progress', String(progress));
        scrollTicking = false;
    };
    window.addEventListener('scroll', () => {
        if (!scrollTicking) {
            window.requestAnimationFrame(updateScrollProgress);
            scrollTicking = true;
        }
    }, { passive: true });
    updateScrollProgress();

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const revealTargets = document.querySelectorAll(
        '.metric, .task-row, .schedule-day, .profile-card, .detail-card, .auth-review-grid article, .audit-list article'
    );

    if (!reduceMotion && 'IntersectionObserver' in window && revealTargets.length > 0) {
        revealTargets.forEach((item, index) => {
            item.classList.add('reveal-item');
            item.style.setProperty('--reveal-delay', `${Math.min(index % 5, 4) * 55}ms`);
        });

        document.documentElement.classList.add('reveal-ready');
        const observer = new IntersectionObserver((entries, currentObserver) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    currentObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -24px 0px' });

        revealTargets.forEach((item) => observer.observe(item));
    }
})();
