<!DOCTYPE html>
<html>
<head>
  <title>Web Push Notification in PHP/MySQL using FCM</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="manifest" href="manifest.json">
Skip to main content
Firebase logo
Project Overview

Develop
Authentication, Database, Storage, Hosting, Functions and ML Kit

Quality
Crashlytics, Performance, Test Lab and App Distribution

Analytics
Dashboard, Events, Conversions, Audiences, Funnels, User Properties, Latest Release, Retention, StreamView and DebugView

Grow
Predictions, A/B Testing, Cloud Messaging, In-App Messaging, Remote Config, Dynamic Links and AdMob
Extensions
Spark
Free $0/month

mess	
 
Go to docs
Receive email updates about new Firebase features, research and events
mess
Waiting for Analytics data...
Store and sync app data in milliseconds

Authentication
Authenticate and manage users

Cloud Firestore
Real-time updates, powerful queries and automatic scaling
See all Develop features
Keep tabs on your app's quality

Crashlytics
Prioritise and fix stability issues

Performance
Get insights into your app's performance
See all Quality features
Grow & engage your audience

Cloud Messaging
Engage the right users at the right time

A/B Testing
Improve key flows & notifications
See all Grow features
Deploy extended functionality to your app quickly

Extensions
Pre-packaged solutions that save you time
See all Extensions features
See all Firebase features


Go to docs
Add Firebase to your web app
Register app
2 
Add Firebase SDK
Copy and paste these scripts into the bottom of your <body> tag, but before you use any Firebase services:


<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.14.3/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/7.14.3/firebase-analytics.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.3/firebase-messaging.js"></script>

<script>
  // Your web app's Firebase configuration
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
  firebase.analytics();
  // Retrieve Firebase Messaging object.
const messaging = firebase.messaging();
// Add the public key generated from the console here.
messaging.usePublicVapidKey("BMG9Stxg7zMHXoYqMdhBwB_upVfC9LcYWeXIifT591da2tf31yO_3L9TqD6UNcwh30-dZCWUs5-Q7epkYtmSFZE");
// Get Instance ID token. Initially this makes a network call, once retrieved
// subsequent calls to getToken will return from cache.
  messaging.requestPermission()
  .then(function() {
    console.log('Notification permission granted.');
    // TODO(developer): Retrieve an Instance ID token for use with FCM.
    if(isTokenSentToServer()) {
      console.log('Token already saved.');
    } else {
      getRegToken();
    }

  })
  .catch(function(err) {
    console.log('Unable to get permission to notify.', err);
  });

  function getRegToken(argument) {
    messaging.getToken()
      .then(function(currentToken) {

        if (currentToken) {
          saveToken(currentToken);
          console.log(currentToken);
          setTokenSentToServer(true);
        } else {
          console.log('No Instance ID token available. Request permission to generate one.');
          setTokenSentToServer(false);
        }
      })
      .catch(function(err) {
        console.log('An error occurred while retrieving token. ', err);
        setTokenSentToServer(false);
      });
  }

  function setTokenSentToServer(sent) {
      window.localStorage.setItem('sentToServer', sent ? 1 : 0);
  }

  function isTokenSentToServer() {
      return window.localStorage.getItem('sentToServer') == 1;
  }

  function saveToken(currentToken) {
    $.ajax({
      url: 'action.php',
      method: 'post',
      data: 'token=' + currentToken
    }).done(function(result){
      console.log(result);
    })
  }

  messaging.onMessage(function(payload) {
    console.log("Message received. ", payload);
    notificationTitle = payload.data.title;
    notificationOptions = {
      body: payload.data.body,
      icon: payload.data.icon,
      image:  payload.data.image
    };
    var notification = new Notification(notificationTitle,notificationOptions);
  });
</script>
Learn more about Firebase for web: Get started, Web SDK API reference, Samples



</head>
<body>
<center>
  <h1>FCM Web Push Notification in PHP/MySQL from localhost</h1>
  <h2>Part 5: Send and Receive Push Notifications in background</h2>
</center>
</body>
