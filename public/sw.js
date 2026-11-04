/* Kartu Pasangan — service worker: bikin app tetap jalan tanpa internet */
const CACHE = 'kartu-pasangan-v7';
const DOCS = ['/', '/coba', '/app'];
const ASSETS = ['/manifest.json', '/icon-192.png', '/icon-512.png'];

self.addEventListener('install', e => {
  e.waitUntil(caches.open(CACHE).then(c => Promise.all([
    c.addAll(['/', '/coba', ...ASSETS]),
    // belum login = /app redirect (ok:false), dilewati; masuk cache saat dibuka setelah login
    fetch('/app', {redirect: 'manual'}).then(r => r.ok && c.put('/app', r)).catch(() => {})
  ])).then(() => self.skipWaiting()));
});

self.addEventListener('activate', e => {
  e.waitUntil(
    caches.keys().then(ks => Promise.all(ks.filter(k => k !== CACHE).map(k => caches.delete(k))))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', e => {
  const url = new URL(e.request.url);
  if (e.request.method !== 'GET' || url.origin !== location.origin) return;
  if (DOCS.includes(url.pathname)) {
    // HTML: coba jaringan dulu biar update selalu masuk, offline baru pakai cache
    e.respondWith(
      fetch(e.request).then(res => {
        if (res.ok) {
          const copy = res.clone();
          caches.open(CACHE).then(c => c.put(url.pathname, copy)).catch(() => {});
        }
        return res;
      }).catch(() => caches.match(url.pathname))
    );
    return;
  }
  // selain ini (/login, /app/state, ...) jangan pernah di-cache: token CSRF & data akun harus selalu baru
  if (!ASSETS.includes(url.pathname)) return;
  // asset (ikon, manifest): cache dulu, cepat & offline-aman
  e.respondWith(caches.match(url.pathname).then(hit => hit || fetch(e.request)));
});
