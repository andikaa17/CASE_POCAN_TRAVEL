import { initializeApp } from "firebase/app";
import { getDatabase, ref, onValue } from "firebase/database";

// Konfigurasi Firebase lo
const firebaseConfig = {
    apiKey: "AIzaSyCbC2ydrz9_uxiNZb-1cGI9-UeRuAlt8MI",
    authDomain: "can-travel-d478d.firebaseapp.com",
    databaseURL:
        "https://can-travel-d478d-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "can-travel-d478d",
    storageBucket: "can-travel-d478d.firebasestorage.app",
    messagingSenderId: "245575878295",
    appId: "1:245575878295:web:710135f7ed51ec46340bd2",
    measurementId: "G-VPTXLQ8EWY",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const database = getDatabase(app);

// Fungsi untuk listen real-time payment
export const listenToPayment = (bookingId, callback) => {
    const paymentRef = ref(database, `payments/${bookingId}`);
    return onValue(paymentRef, (snapshot) => {
        callback(snapshot.val());
    });
};

export default database;
