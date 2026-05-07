importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyBu8UWsZ4UZDrFLhsmGNgxvoWyuETK_Bdw",
    authDomain: "sarpras-72ec3.firebaseapp.com",
    projectId: "sarpras-72ec3",
    storageBucket: "sarpras-72ec3.appspot.com",
    messagingSenderId: "219024370680",
    appId: "1:219024370680:web:de096934d6d1970eee440d"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {

    console.log('FCM Background Message:', payload);

    const title =
        payload.notification?.title ||
        payload.data?.title ||
        'Notifikasi';

    const body =
        payload.notification?.body ||
        payload.data?.body ||
        '';

    const url =
        payload.data?.url ||
        '/';

    self.registration.showNotification(title, {
        body: body,
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        data: {
            url: url
        }
    });
});

self.addEventListener('notificationclick', function(event) {

    event.notification.close();

    const url = event.notification.data.url;

    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then(function(clientList) {

            for (const client of clientList) {

                if (client.url.includes(url) && 'focus' in client) {
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});