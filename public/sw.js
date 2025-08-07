const CACHE_NAME = 'medicines-cache-v1';
const OFFLINE_URL = '/offline.html';
const ASSETS = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/vendor/fontawesome/css/all.min.css',
  '/vendor/fontawesome/webfonts/fa-solid-900.woff2',
  '/js/chart.js',
  OFFLINE_URL
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(ASSETS))
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => response || fetch(event.request))
      .catch(() => caches.match(OFFLINE_URL))
  );
});