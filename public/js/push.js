import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js';
import { getMessaging, getToken } from 'https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging.js';

const firebaseConfig = {
  apiKey: "YOUR_API_KEY",
  authDomain: "YOUR_PROJECT.firebaseapp.com",
  projectId: "YOUR_PROJECT",
  messagingSenderId: "YOUR_SENDER_ID",
  appId: "YOUR_APP_ID"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

async function registerAndSaveToken(){
    const registration = await navigator.serviceWorker.register('/sw.js');
    const token = await getToken(messaging,{vapidKey:'YOUR_VAPID_KEY',serviceWorkerRegistration:registration});
    if(token){
        await fetch('/api/save_token.php',{
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify({token})
        });
        console.log('Token saved:', token);
    }
}
if('serviceWorker' in navigator) registerAndSaveToken();
