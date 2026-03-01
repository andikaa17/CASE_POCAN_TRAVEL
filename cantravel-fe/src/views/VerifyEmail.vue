<template>
    <div class="min-h-[80vh] flex items-center justify-center px-4">
        <div class="bg-white p-8 rounded-2xl shadow-xl max-w-md w-full">
            <div class="text-center">
                <div
                    class="bg-green-100 p-4 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center"
                >
                    <svg
                        class="w-10 h-10 text-green-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        ></path>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    Verifikasi Email
                </h2>

                <p class="text-gray-600 mb-4">
                    Kami telah mengirim link verifikasi ke:
                </p>

                <div class="bg-blue-50 p-3 rounded-lg mb-6">
                    <p class="font-semibold text-blue-700">{{ email }}</p>
                </div>

                <p class="text-sm text-gray-500 mb-6">
                    Silakan cek email Anda (termasuk folder spam) dan klik link
                    verifikasi untuk mengaktifkan akun.
                </p>

                <!-- Timer -->
                <div class="mb-4">
                    <p class="text-sm text-gray-500 mb-2">
                        Belum terima email? Kirim ulang dalam
                    </p>
                    <div class="text-2xl font-bold text-blue-600">
                        {{ formatTime(resendTimer) }}
                    </div>
                </div>

                <button
                    @click="resendVerification"
                    :disabled="resendTimer > 0 || loading"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition disabled:opacity-50 mb-3"
                >
                    {{ loading ? "Mengirim..." : "Kirim Ulang Email" }}
                </button>

                <router-link
                    to="/login"
                    class="text-sm text-blue-600 hover:underline"
                >
                    Kembali ke Login
                </router-link>

                <div
                    v-if="message"
                    class="mt-4 p-3 rounded-lg text-sm"
                    :class="
                        messageType === 'success'
                            ? 'bg-green-50 text-green-600'
                            : 'bg-red-50 text-red-600'
                    "
                >
                    {{ message }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useRoute } from "vue-router";
import axios from "axios"; // <-- PAKSA PAKAI AXIOS

const route = useRoute();
const email = ref(route.query.email || "");
const resendTimer = ref(60);
const loading = ref(false);
const message = ref("");
const messageType = ref("success");
let timerInterval = null;

const startTimer = () => {
    resendTimer.value = 60;
    if (timerInterval) clearInterval(timerInterval);
    timerInterval = setInterval(() => {
        if (resendTimer.value > 0) {
            resendTimer.value--;
        } else {
            clearInterval(timerInterval);
        }
    }, 1000);
};

const formatTime = (seconds) => {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins}:${secs.toString().padStart(2, "0")}`;
};

const resendVerification = async () => {
    if (resendTimer.value > 0) return;

    loading.value = true;
    message.value = "";

    try {
        // PAKSA PAKAI AXIOS DENGAN HEADER MANUAL
        const response = await axios.post(
            "http://localhost:8000/api/email/resend",
            {
                email: email.value,
            },
            {
                headers: {
                    "X-API-Key": "rahasia123", // <-- PAKSA!
                    "Content-Type": "application/json",
                },
            },
        );

        message.value = response.data.message;
        messageType.value = "success";
        startTimer();
    } catch (error) {
        console.log("Error response:", error.response?.data);
        message.value = error.response?.data?.message || "Gagal mengirim ulang";
        messageType.value = "error";
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    startTimer();
});

onUnmounted(() => {
    if (timerInterval) clearInterval(timerInterval);
});
</script>
