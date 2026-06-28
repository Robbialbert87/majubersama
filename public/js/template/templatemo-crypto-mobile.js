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



    function initSearchToggle() {
        var searchBox = document.querySelector('.header-search');
        if (!searchBox) return;
        var input = searchBox.querySelector('input');
        searchBox.addEventListener('click', function() {
            if (window.innerWidth <= 640 && input) input.focus();
        });
    }

    function init() {

        initSearchToggle();
        initSearchFilter();
        window.showToast = showToast;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();