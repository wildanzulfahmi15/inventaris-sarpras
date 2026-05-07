import './bootstrap';

import { initializeApp } from "firebase/app";

import {
    getMessaging,
    getToken,
    onMessage
} from "firebase/messaging";

const firebaseConfig = {
    apiKey: "AIzaSyBu8UWsZ4UZDrFLhsmGNgxvoWyuETK_Bdw",
    authDomain: "sarpras-72ec3.firebaseapp.com",
    projectId: "sarpras-72ec3",
    storageBucket: "sarpras-72ec3.appspot.com",
    messagingSenderId: "219024370680",
    appId: "1:219024370680:web:de096934d6d1970eee440d"
};

const app = initializeApp(firebaseConfig);

const messaging = getMessaging(app);

navigator.serviceWorker.register('/firebase-messaging-sw.js')
.then(async (registration) => {

    console.log('SW registered');

    const permission =
        await Notification.requestPermission();

    if (permission !== 'granted') {
        console.log('Permission denied');
        return;
    }

    const token = await getToken(messaging, {
        vapidKey: 'ISI_VAPID_KEY_FIREBASE',
        serviceWorkerRegistration: registration
    });

    console.log('FCM TOKEN:', token);

    await fetch('/save-fcm-token', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN':
                document.querySelector(
                    'meta[name="csrf-token"]'
                ).content
        },
        body: JSON.stringify({
            token: token
        })
    });

});

onMessage(messaging, (payload) => {

    console.log('Foreground message:', payload);

    new Notification(
        payload.notification.title,
        {
            body: payload.notification.body,
            icon: '/favicon.ico'
        }
    );
});w