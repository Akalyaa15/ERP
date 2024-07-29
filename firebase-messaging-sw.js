importScripts('https://www.gstatic.com/firebasejs/7.14.3/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.14.3/firebase-messaging.js');
/*Update this config*/
  var firebaseConfig = {
    apiKey: "AIzaSyC5ATR4K4e993K1v43RoubEpTeW3_oylY0",
    authDomain: "mess-2faae.firebaseapp.com",
    databaseURL: "https://mess-2faae.firebaseio.com",
    projectId: "mess-2faae",
    storageBucket: "mess-2faae.appspot.com",
    messagingSenderId: "422796695048",
    appId: "1:422796695048:web:5c85fcdd701ed96d5963bd",
    measurementId: "G-MNX7SDYYP1"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = payload.data.title;
  const notificationOptions = {
    body: payload.data.body,
  icon: payload.data.icon,
  image: payload.data.image,
  
  };

  return self.registration.showNotification(notificationTitle,
      notificationOptions);
});
// [END background_handler]