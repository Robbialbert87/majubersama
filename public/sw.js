const CACHE_NAME = 'admin-panel-v2';
const urlsToCache = [
    '/',
    '/dashboard',
    '/users',
    '/roles',
    '/offline',
];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
    );
    self.skipWaiting();
});

self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(name) {
                    if (name !== CACHE_NAME) {
                        return caches.delete(name);
                    }
                })
            );
        }).then(function() {
            return self.clients.claim();
        })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        fetch(event.request).then(function(fetchResponse) {
            var responseToCache = fetchResponse.clone();
            caches.open(CACHE_NAME).then(function(cache) {
                cache.put(event.request, responseToCache);
            });
            return fetchResponse;
        }).catch(function() {
            return caches.match(event.request).then(function(cached) {
                if (cached) return cached;
                if (event.request.mode === 'navigate') {
                    return caches.match('/');
                }
                return new Response('Offline', { status: 503 });
            });
        })
    );
});
