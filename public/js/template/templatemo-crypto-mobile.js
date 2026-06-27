(function() {
    'use strict';

    function showToast(message, type) {
        var existing = document.querySelector('.app-toast');
        if (existing) existing.remove();
        var toast = document.createElement('div');
        toast.className = 'app-toast' + (type === 'error' ? ' error' : '');
        toast.textContent = message;
        toast.style.cssText = 'position:fixed;bottom:80px;left:50%;transform:translateX(-50%);background:var(--bg-card);color:var(--text-primary);padding:12px 24px;border-radius:12px;font-size:14px;border:1px solid var(--border);z-index:9999;box-shadow:0 8px 24px var(--shadow);opacity:0;transition:opacity 0.3s ease;';
        document.body.appendChild(toast);
        requestAnimationFrame(function() { toast.style.opacity = '1'; });
        setTimeout(function() {
            toast.style.opacity = '0';
            setTimeout(function() { toast.remove(); }, 300);
        }, 2500);
    }



    function initPullToRefresh() {
        if (window.innerWidth > 768) return;
        var mainContent = document.querySelector('.main-content');
        if (!mainContent) return;
        var touchStartY = 0;
        var refreshIndicator = document.createElement('div');
        refreshIndicator.className = 'pull-indicator';
        refreshIndicator.innerHTML = '&#8595; Pull to refresh';
        mainContent.parentNode.insertBefore(refreshIndicator, mainContent);

        mainContent.addEventListener('touchstart', function(e) {
            if (window.scrollY === 0) touchStartY = e.touches[0].clientY;
        }, { passive: true });

        mainContent.addEventListener('touchmove', function(e) {
            if (window.scrollY === 0 && touchStartY > 0) {
                var diff = e.touches[0].clientY - touchStartY;
                if (diff > 20) {
                    refreshIndicator.style.opacity = Math.min(1, (diff - 20) / 60);
                    refreshIndicator.innerHTML = diff > 80 ? '&#8635; Release to refresh' : '&#8595; Pull to refresh';
                    if (diff > 80) {
                        touchStartY = 0;
                        refreshIndicator.innerHTML = '&#8635; Refreshing...';
                        setTimeout(function() { location.reload(); }, 500);
                    }
                }
            }
        }, { passive: true });

        mainContent.addEventListener('touchend', function() {
            refreshIndicator.style.opacity = '0';
            touchStartY = 0;
        }, { passive: true });
    }

    function initSwipeSidebar() {
    }

    function initFloatingButton() {
        if (window.innerWidth > 768) return;
        var existingBtn = document.querySelector('.fab-btn');
        if (existingBtn) return;
        var headerBtn = document.querySelector('.card-header .btn.primary');
        if (!headerBtn) return;
        var fab = document.createElement('button');
        fab.className = 'fab-btn';
        fab.innerHTML = '+';
        fab.setAttribute('aria-label', 'Tambah');
        fab.addEventListener('click', function() {
            headerBtn.click();
        });
        document.body.appendChild(fab);
    }

    function initSearchFilter() {
        var searchInput = document.querySelector('.search-box input');
        if (!searchInput) return;
        searchInput.addEventListener('input', function() {
            var q = this.value.toLowerCase().trim();
            var rows = document.querySelectorAll('.market-table tbody tr');
            rows.forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    }

    function initButtonTouchFeedback() {
        document.querySelectorAll('.btn, .nav-item, .stat-card, .market-stat, .market-table tbody tr').forEach(function(el) {
            el.addEventListener('touchstart', function() { this.style.transform = 'scale(0.97)'; }, { passive: true });
            el.addEventListener('touchend', function() { this.style.transform = ''; }, { passive: true });
        });
    }

    function initSearchToggle() {
        var searchBox = document.querySelector('.header-search');
        if (!searchBox) return;
        var input = searchBox.querySelector('input');
        searchBox.addEventListener('click', function() {
            if (window.innerWidth <= 640 && input) input.focus();
        });
    }

    function init() {
        initPullToRefresh();
        initSwipeSidebar();
        initButtonTouchFeedback();
        initSearchToggle();
        initFloatingButton();
        initSearchFilter();
        window.showToast = showToast;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();