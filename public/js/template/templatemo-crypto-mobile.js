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

    function initMobileRows() {
        if (window.innerWidth > 768) return;
        var rows = document.querySelectorAll('.market-table tbody tr');
        rows.forEach(function(row) {
            if (row.dataset.mobileReady) return;
            row.dataset.mobileReady = '1';

            var touchStartX = 0, isSwiping = false;

            row.addEventListener('touchstart', function(e) {
                touchStartX = e.touches[0].clientX;
                isSwiping = false;
            }, { passive: true });

            row.addEventListener('touchmove', function(e) {
                var diff = touchStartX - e.touches[0].clientX;
                if (Math.abs(diff) > 10) isSwiping = true;
                if (diff > 0 && diff < 120) {
                    row.style.transform = 'translateX(-' + diff + 'px)';
                }
            }, { passive: true });

            row.addEventListener('touchend', function(e) {
                var diff = touchStartX - (e.changedTouches ? e.changedTouches[0].clientX : touchStartX);
                if (diff > 60) {
                    row.classList.add('revealed');
                    row.style.transform = '';
                } else {
                    row.classList.remove('revealed');
                    row.style.transform = '';
                }
                if (!isSwiping && diff < 10) {
                    showDetailSheet(row);
                }
            }, { passive: true });

            row.addEventListener('click', function() {
                if (!isSwiping) showDetailSheet(row);
            });
        });
    }

    function showDetailSheet(row) {
        var existing = document.querySelector('.detail-sheet');
        if (existing) existing.remove();

        var overlay = document.createElement('div');
        overlay.className = 'detail-overlay';
        overlay.addEventListener('click', function() { overlay.remove(); sheet.remove(); document.body.style.overflow = ''; });

        var sheet = document.createElement('div');
        sheet.className = 'detail-sheet';

        var handle = document.createElement('div');
        handle.className = 'detail-handle';
        sheet.appendChild(handle);

        var cells = row.querySelectorAll('td');
        cells.forEach(function(cell) {
            var label = cell.getAttribute('data-label') || '';
            if (label.toLowerCase() === 'aksi' || label.toLowerCase() === 'actions') return;
            var rowEl = document.createElement('div');
            rowEl.className = 'detail-row';
            var l = document.createElement('div');
            l.className = 'detail-label';
            l.textContent = label;
            var v = document.createElement('div');
            v.className = 'detail-value';
            v.innerHTML = cell.innerHTML;
            rowEl.appendChild(l);
            rowEl.appendChild(v);
            sheet.appendChild(rowEl);
        });

        var actions = row.querySelector('.text-right');
        if (actions) {
            var btnGroup = document.createElement('div');
            btnGroup.style.cssText = 'display:flex;gap:12px;margin-top:24px;';
            var btns = actions.querySelectorAll('.btn');
            btns.forEach(function(btn) {
                var clone = btn.cloneNode(true);
                clone.style.cssText = 'flex:1;' + (clone.classList.contains('danger') ? '' : '');
                btnGroup.appendChild(clone);
            });
            sheet.appendChild(btnGroup);
        }

        document.body.appendChild(overlay);
        document.body.appendChild(sheet);
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(function() {
            overlay.style.opacity = '1';
            sheet.style.transform = 'translateY(0)';
        });
    }

    function initRevealActions() {
        var rows = document.querySelectorAll('.market-table tbody tr');
        rows.forEach(function(row) {
            var actions = row.querySelector('.text-right');
            if (!actions) return;
            var reveal = document.createElement('div');
            reveal.className = 'mobile-card-reveal';
            var btns = actions.querySelectorAll('.btn');
            btns.forEach(function(btn) {
                var clone = btn.cloneNode(true);
                clone.style.cssText = 'height:100%;border-radius:0;margin:0;flex:1;';
                reveal.appendChild(clone);
            });
            row.appendChild(reveal);
        });
    }

    function initFloatingButton() {
        if (window.innerWidth > 768) return;
        var headerBtn = document.querySelector('.card-header .btn.primary');
        if (!headerBtn) return;
        var fab = document.createElement('button');
        fab.className = 'fab-btn';
        fab.innerHTML = '+';
        fab.setAttribute('aria-label', 'Add');
        fab.addEventListener('click', function() {
            headerBtn.click();
        });
        document.body.appendChild(fab);
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
        if (window.innerWidth <= 768) return;
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('sidebarOverlay');
        var toggle = document.getElementById('mobileMenuToggle');
        if (!sidebar || !overlay) return;

        var touchStartX = 0, isDragging = false, sidebarOpen = false;
        var SIDEBAR_WIDTH = 280;

        document.addEventListener('touchstart', function(e) {
            var x = e.touches[0].clientX;
            sidebarOpen = sidebar.classList.contains('active');
            if (sidebarOpen) {
                touchStartX = x;
                isDragging = true;
                sidebar.style.transition = 'none';
            } else if (x < 30) {
                touchStartX = x;
                isDragging = true;
                sidebar.style.transition = 'none';
                sidebar.style.transform = 'translateX(-100%)';
                overlay.classList.add('active');
            }
        }, { passive: true });

        document.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            var diff = e.touches[0].clientX - touchStartX;
            if (sidebarOpen) {
                var translate = Math.max(-SIDEBAR_WIDTH, Math.min(0, diff));
                sidebar.style.transform = 'translateX(' + translate + 'px)';
                overlay.style.opacity = 0.5 * (1 + translate / SIDEBAR_WIDTH);
            } else {
                var translate = Math.min(0, Math.max(-SIDEBAR_WIDTH, -SIDEBAR_WIDTH + diff));
                sidebar.style.transform = 'translateX(' + translate + 'px)';
                overlay.style.opacity = 0.5 * (1 + translate / SIDEBAR_WIDTH);
            }
        }, { passive: true });

        document.addEventListener('touchend', function(e) {
            if (!isDragging) return;
            isDragging = false;
            sidebar.style.transition = '';
            var diff = e.changedTouches[0].clientX - touchStartX;
            var threshold = SIDEBAR_WIDTH * 0.35;
            if (sidebarOpen) {
                if (-diff > threshold) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    sidebar.style.transform = '';
                    overlay.style.opacity = '';
                    if (toggle) toggle.classList.remove('active');
                    document.body.style.overflow = '';
                } else {
                    sidebar.classList.add('active');
                    sidebar.style.transform = '';
                    overlay.style.opacity = '';
                }
            } else {
                if (diff > threshold) {
                    sidebar.classList.add('active');
                    sidebar.style.transform = '';
                    overlay.style.opacity = '';
                    if (toggle) toggle.classList.add('active');
                    document.body.style.overflow = 'hidden';
                } else {
                    overlay.classList.remove('active');
                    sidebar.style.transform = '';
                }
            }
        }, { passive: true });
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
        initRevealActions();
        initMobileRows();
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