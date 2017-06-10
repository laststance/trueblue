const CACHE_NAME = 'trueblue-cache-v0001'
const urlsToCache = [
    '/',
    '/assets/build/index.js?v0001',
    '/assets/build/27faf19206c7a20e8126bedc37c95458.woff',
    '/assets/build/796a7724727a6bffefce3b5655f06b09.woff',
    '/assets/build/288eb21cf19a4f5eab19c1ccd3cc21b9.woff',
    'https://fonts.gstatic.com/s/shadowsintolighttwo/v4/gDxHeefcXIo-lOuZFCn2xVbBQNzAwd3E4WXIZWrwe_7r7w4p9aSvGirXi6XmeXNA.woff2',
    'https://fonts.googleapis.com/css?family=Shadows+Into+Light+Two',
    'http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css',
]


self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                console.log('Opened cache')
                return cache.addAll(urlsToCache)
            })
    )
})
