const CACHE_NAME = 'offline-cache-v5';
const OFFLINE_URL = '/device-player'; // The URL of the web page to cache
const ASSETS_URL = '/get-assets-list'; // Endpoint to get the list of assets

// Install event: caching the offline page
self.addEventListener('install', event => {

    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return fetch(ASSETS_URL)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(assets => {

                    return cache.addAll([
                        OFFLINE_URL,
                        '/js/starter-device.js',
                        '/js/handle-play.js',
                        '/js/handle-storage.js',
                        ...assets
                    ]);
                })
                .catch(error => {
                    console.error('Failed to cache resources:', error);
                });
        })
    );
});

// Activate event: cleaning up old caches
self.addEventListener('activate', event => {

    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {

                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Fetch event: serving cached content when offline
self.addEventListener('fetch', event => {
    const requestUrl = new URL(event.request.url);
    console.log('request url',requestUrl);
    if (event.request.method === 'POST' && requestUrl.pathname === '/uploadmedia') {
        // If it is an upload request, do not intercept it
        console.log('return',event);
        return;
      }
    event.respondWith(
        fetch(event.request).catch(() => {
            // If the network request fails, try to serve the request from the cache
            return caches.match(event.request).then(response => {
                if (response) {
                    return response;
                } else if (event.request.headers.get('accept').includes('text/html')) {
                    // If the request is for an HTML page, serve the offline page
                    return caches.match(OFFLINE_URL);
                }
            });
        })
    );
});
