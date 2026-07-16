const CACHE_NAME = 'ventorialls-pwa-v1';
const urlsToCache = [
  '/',
  '/workspaceinventory',
  '/user',
  '/image/logo-biru.png',
  '/image/logo-ventorialls-2.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request);
      })
  );
});
