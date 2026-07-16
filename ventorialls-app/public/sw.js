const CACHE_NAME = 'ventorialls-pwa-v2';
const urlsToCache = [
  '/image/logo-biru.png',
  '/image/logo-ventorialls-2.png'
];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', event => {
  // Hanya proses request GET (jangan intercept POST/PUT/DELETE) 
  // agar tidak merusak token CSRF Laravel (Error 419 Page Expired)
  if (event.request.method !== 'GET') {
    return;
  }

  // Network-first strategy: selalu ambil data terbaru dari server
  event.respondWith(
    fetch(event.request).catch(() => {
      // Jika offline, baru cari di cache
      return caches.match(event.request);
    })
  );
});
