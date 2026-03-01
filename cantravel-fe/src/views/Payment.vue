<template>
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold">Pembayaran Tiket</h1>

        <!-- Booking Summary -->
        <div v-if="booking" class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Ringkasan Booking</h2>

            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-500">Kode Booking</span>
                    <span class="font-semibold">{{
                        booking.booking_code
                    }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Rute</span>
                    <span class="font-semibold">
                        {{ booking.schedule?.route?.origin }} →
                        {{ booking.schedule?.route?.destination }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal</span>
                    <span class="font-semibold">
                        {{ formatDate(booking.schedule?.departure_date) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jam</span>
                    <span class="font-semibold">
                        {{ formatTime(booking.schedule?.departure_time) }} WIB
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jumlah Penumpang</span>
                    <span class="font-semibold"
                        >{{ booking.total_passengers }} orang</span
                    >
                </div>
                <div class="flex justify-between border-t pt-3">
                    <span class="text-gray-700 font-semibold">Total Bayar</span>
                    <span class="text-2xl font-bold text-blue-600"
                        >Rp {{ formatPrice(booking.total_price) }}</span
                    >
                </div>
            </div>
        </div>

        <!-- Pilih Metode Pembayaran (Hanya QRIS) -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Metode Pembayaran</h2>

            <!-- QRIS Card -->
            <div
                class="border rounded-lg p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-300"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="text-3xl">🏧</div>
                        <div>
                            <div class="font-semibold text-lg">QRIS</div>
                            <div class="text-sm text-gray-600">
                                GoPay, OVO, Dana, ShopeePay, LinkAja
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold"
                    >
                        REAL-TIME
                    </div>
                </div>

                <!-- Generate QR Button - CEK DULU APAKAH SUDAH ADA PAYMENT -->
                <div class="mt-4" v-if="!qrData && !hasExistingPayment">
                    <button
                        @click="generateQR"
                        :disabled="loading"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{
                            loading ? "Memproses..." : "Generate QR Pembayaran"
                        }}
                    </button>
                </div>
            </div>

            <!-- QRIS Display -->
            <div v-if="qrData" class="mt-6 text-center">
                <div
                    class="bg-white p-6 rounded-lg border-2 border-blue-200 shadow-lg"
                >
                    <h3 class="font-semibold mb-2 text-gray-700">Scan QRIS</h3>
                    <img
                        :src="qrData.qr_code"
                        alt="QRIS"
                        class="mx-auto w-64 h-64"
                    />

                    <div class="mt-4 text-left bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-500">Total Bayar:</span>
                            <span class="font-bold"
                                >Rp {{ formatPrice(qrData.amount) }}</span
                            >
                        </div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-500">Kode Booking:</span>
                            <span>{{
                                qrData.booking_code || booking?.booking_code
                            }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Berlaku hingga:</span>
                            <span class="text-orange-600">{{
                                formatExpiredDate(qrData.expired_at)
                            }}</span>
                        </div>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="mt-2 text-sm">
                        <span
                            v-if="timeRemaining > 0"
                            class="text-blue-600 font-semibold"
                        >
                            ⏱️ Sisa waktu:
                            {{ formatTimeRemaining(timeRemaining) }}
                        </span>
                        <span v-else class="text-red-600 font-semibold">
                            ⏱️ Waktu habis! Silakan generate ulang.
                        </span>
                    </div>

                    <!-- Real-time Status -->
                    <div
                        class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200"
                    >
                        <div class="flex items-center justify-center space-x-2">
                            <div
                                class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"
                            ></div>
                            <p class="text-blue-800 font-medium">
                                ⚡ Menunggu pembayaran...
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Regenerate QR -->
                <button
                    @click="regenerateQR"
                    class="mt-4 text-sm text-blue-600 hover:underline"
                >
                    ↻ Generate QR Baru
                </button>
            </div>

            <!-- Payment Status -->
            <div
                v-if="booking?.status === 'paid'"
                class="mt-4 p-4 bg-green-100 text-green-800 rounded-lg"
            >
                <div class="flex items-center justify-center space-x-2">
                    <span class="text-2xl">✅</span>
                    <span class="font-semibold">Pembayaran Berhasil!</span>
                </div>
            </div>

            <!-- Info Message -->
            <div
                v-if="infoMessage"
                class="mt-4 p-4 bg-blue-100 text-blue-800 rounded-lg"
            >
                {{ infoMessage }}
            </div>

            <!-- Error -->
            <div
                v-if="error"
                class="mt-4 bg-red-100 text-red-700 p-3 rounded-lg"
            >
                {{ error }}
            </div>

            <!-- Actions -->
            <div class="mt-6 space-y-3">
                <button
                    v-if="booking?.status === 'paid'"
                    @click="downloadInvoice"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700"
                >
                    Download Invoice
                </button>

                <router-link
                    to="/my-bookings"
                    class="block text-center text-blue-600 hover:underline"
                >
                    ← Kembali ke My Bookings
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { useRoute } from "vue-router";
import { useBookingStore } from "../stores/booking";
import { usePaymentStore } from "../stores/payment";
import { listenToPayment } from "../firebase";
import { format } from "date-fns";
import { id } from "date-fns/locale";

const route = useRoute();
const bookingStore = useBookingStore();
const paymentStore = usePaymentStore();

const bookingId = route.params.bookingId;
const booking = ref(null);
const loading = ref(false);
const error = ref("");
const infoMessage = ref("");
const qrData = ref(null);
const timeRemaining = ref(0);
let timerInterval = null;

// Computed property untuk cek apakah sudah ada payment
const hasExistingPayment = computed(() => {
    return booking.value?.payment_id || false;
});

// Firebase listener
let unsubscribe = null;

// ==================== FORMAT HELPERS ====================

// Format tanggal (Sabtu, 28 Februari 2026)
const formatDate = (date) => {
    if (!date) return "-";
    try {
        // KALAU YANG DIKIRIM CUMA JAM (CONTOH: "06:00:00")
        if (
            typeof date === "string" &&
            date.length <= 8 &&
            date.includes(":")
        ) {
            return date.substring(0, 5);
        }

        const d = new Date(date);
        if (isNaN(d.getTime())) return "-";
        return format(d, "EEEE, dd MMM yyyy", { locale: id });
    } catch (error) {
        return "-";
    }
};

// ✅ FORMAT WAKTU (15:00)
const formatTime = (time) => {
    if (!time) return "-";
    // Handle format HH:MM:SS
    if (typeof time === "string" && time.match(/^\d{2}:\d{2}:\d{2}$/)) {
        return time.substring(0, 5); // Return HH:MM
    }
    try {
        const date = new Date(time);
        if (isNaN(date.getTime())) return "-";
        return format(date, "HH:mm", { locale: id });
    } catch {
        return "-";
    }
};

const formatExpiredDate = (date) => {
    if (!date) return "-";
    try {
        return format(new Date(date), "dd MMM yyyy HH:mm", { locale: id });
    } catch (error) {
        return "-";
    }
};

const formatPrice = (price) => {
    return new Intl.NumberFormat("id-ID").format(price);
};

const formatTimeRemaining = (seconds) => {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins}:${secs.toString().padStart(2, "0")}`;
};

// ===== LOAD QR DARI LOCALSTORAGE =====
const loadQRFromStorage = () => {
    const savedQR = localStorage.getItem(`payment_qr_${bookingId}`);
    if (savedQR) {
        try {
            const parsed = JSON.parse(savedQR);
            // Cek apakah masih berlaku (belum expired)
            const expiredAt = new Date(parsed.expired_at);
            if (expiredAt > new Date()) {
                qrData.value = parsed;
                startCountdown(expiredAt);

                // SET hasExistingPayment KE TRUE
                if (booking.value) {
                    booking.value.payment_id = parsed.payment_id || true;
                }

                // HITUNG SISA WAKTU
                const diffMs = expiredAt - new Date();
                const diffMins = Math.floor(diffMs / 60000);

                if (diffMins > 0) {
                    infoMessage.value = `⏱️ Payment masih berlaku. Sisa waktu: ${diffMins} menit`;
                }

                // Setup listener lagi
                if (typeof listenToPayment === "function") {
                    unsubscribe = listenToPayment(bookingId, (data) => {
                        if (data && data.status === "success") {
                            if (booking.value) {
                                booking.value.status = "paid";
                            }
                            qrData.value = null;
                            localStorage.removeItem(`payment_qr_${bookingId}`);
                            infoMessage.value = "";
                            if (timerInterval) clearInterval(timerInterval);
                        }
                    });
                }
            } else {
                // QR expired, hapus dari storage
                localStorage.removeItem(`payment_qr_${bookingId}`);
            }
        } catch (e) {
            localStorage.removeItem(`payment_qr_${bookingId}`);
        }
    }
};

// ===== COUNTDOWN TIMER =====
const startCountdown = (expiredAt) => {
    if (timerInterval) clearInterval(timerInterval);

    timerInterval = setInterval(() => {
        const now = new Date();
        const diff = expiredAt - now;

        if (diff <= 0) {
            timeRemaining.value = 0;
            clearInterval(timerInterval);
            // QR expired, hapus dari storage
            localStorage.removeItem(`payment_qr_${bookingId}`);
            qrData.value = null;
            infoMessage.value = "";
            error.value = "⏱️ Waktu pembayaran habis. Silakan generate ulang.";

            // Reset payment_id
            if (booking.value) {
                booking.value.payment_id = null;
            }
        } else {
            timeRemaining.value = Math.floor(diff / 1000); // dalam detik

            // Update info message setiap menit
            const diffMins = Math.floor(diff / 60000);
            if (diffMins > 0) {
                infoMessage.value = `⏱️ Payment masih berlaku. Sisa waktu: ${diffMins} menit`;
            }
        }
    }, 1000);
};

const loadBooking = async () => {
    const result = await bookingStore.getMyBookings();
    if (result.success) {
        booking.value = result.data.find((b) => b.id == bookingId);

        // Cek apakah booking sudah lunas
        if (booking.value?.status === "paid") {
            localStorage.removeItem(`payment_qr_${bookingId}`);
        }
    }
};

// Generate QR
const generateQR = async () => {
    loading.value = true;
    error.value = "";
    infoMessage.value = "";

    const result = await paymentStore.processQris(bookingId);

    if (result.success) {
        qrData.value = result.data;

        // SIMPAN KE LOCALSTORAGE
        localStorage.setItem(
            `payment_qr_${bookingId}`,
            JSON.stringify(result.data),
        );

        // START COUNTDOWN
        startCountdown(new Date(result.data.expired_at));

        if (booking.value) {
            booking.value.payment_id = result.data.payment_id;
        }

        // Setup Firebase listener
        if (typeof listenToPayment === "function") {
            unsubscribe = listenToPayment(bookingId, (data) => {
                if (data && data.status === "success") {
                    if (booking.value) {
                        booking.value.status = "paid";
                    }
                    qrData.value = null;
                    localStorage.removeItem(`payment_qr_${bookingId}`);
                    infoMessage.value = "";
                    if (timerInterval) clearInterval(timerInterval);
                }
            });
        }
    } else {
        // Handle error "Payment masih berlaku"
        if (result.data?.expires_in_minutes) {
            const minutes = Math.round(result.data.expires_in_minutes);

            // AMBIL DATA PAYMENT DARI RESPONSE
            if (result.data?.existing_payment) {
                const paymentData = result.data.existing_payment;
                qrData.value = {
                    qr_code: `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${paymentData.payment_code}`,
                    amount: paymentData.amount,
                    booking_code: booking.value?.booking_code,
                    expired_at: paymentData.expired_at,
                    payment_id: paymentData.id,
                };

                // Simpan ke localStorage
                localStorage.setItem(
                    `payment_qr_${bookingId}`,
                    JSON.stringify(qrData.value),
                );

                // Set payment_id
                if (booking.value) {
                    booking.value.payment_id = paymentData.id;
                }

                // Start countdown
                startCountdown(new Date(paymentData.expired_at));
            } else {
                // Fallback: coba load dari storage
                loadQRFromStorage();
            }

            infoMessage.value = `⏱️ Payment masih berlaku. Sisa waktu: ${minutes} menit`;
        } else {
            error.value = result.message || "Gagal generate QR";
        }
    }

    loading.value = false;
};

// Regenerate QR
const regenerateQR = async () => {
    if (!confirm("Generate QR baru akan menghapus QR sebelumnya. Lanjutkan?")) {
        return;
    }

    loading.value = true;
    error.value = "";
    infoMessage.value = "";
    qrData.value = null;

    // HAPUS DARI STORAGE
    localStorage.removeItem(`payment_qr_${bookingId}`);
    if (timerInterval) clearInterval(timerInterval);

    const result = await paymentStore.regenerateQris(bookingId);

    if (result.success) {
        qrData.value = result.data;

        // SIMPAN KE STORAGE
        localStorage.setItem(
            `payment_qr_${bookingId}`,
            JSON.stringify(result.data),
        );

        // START COUNTDOWN
        startCountdown(new Date(result.data.expired_at));

        if (booking.value) {
            booking.value.payment_id = result.data.payment_id;
        }

        if (typeof listenToPayment === "function") {
            unsubscribe = listenToPayment(bookingId, (data) => {
                if (data && data.status === "success") {
                    if (booking.value) {
                        booking.value.status = "paid";
                    }
                    qrData.value = null;
                    localStorage.removeItem(`payment_qr_${bookingId}`);
                    infoMessage.value = "";
                    if (timerInterval) clearInterval(timerInterval);
                }
            });
        }
    } else {
        error.value = result.message || "Gagal regenerate QR";
    }

    loading.value = false;
};

const downloadInvoice = async () => {
    await paymentStore.downloadInvoice(bookingId);
};

onMounted(() => {
    loadBooking();
    loadQRFromStorage();
});

onUnmounted(() => {
    if (unsubscribe) {
        unsubscribe();
    }
    if (timerInterval) {
        clearInterval(timerInterval);
    }
});
</script>
