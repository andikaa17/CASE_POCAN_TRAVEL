<template>
    <div class="payment-container">
        <h3>Pembayaran QRIS</h3>

        <div v-if="loading" class="loading">
            <p>Memproses...</p>
        </div>

        <div v-else-if="paymentStatus === 'pending'" class="qr-section">
            <h4>Scan QRIS menggunakan e-wallet</h4>

            <div class="qr-image-container">
                <img :src="qrCode" alt="QRIS" class="qris-image" />
            </div>

            <div class="payment-info">
                <p><strong>Total:</strong> Rp {{ formatPrice(amount) }}</p>
                <p><strong>Booking:</strong> {{ bookingCode }}</p>
                <p>
                    <strong>Status:</strong>
                    <span class="badge pending">⏳ Menunggu pembayaran</span>
                </p>
            </div>

            <div class="real-time-status">
                <p>🔄 Status akan berubah otomatis saat pembayaran sukses</p>
            </div>
        </div>

        <div v-else-if="paymentStatus === 'success'" class="success-section">
            <h4>✅ Pembayaran Berhasil!</h4>
            <p>Terima kasih, tiket anda telah dikonfirmasi.</p>
            <button @click="downloadInvoice" class="btn-primary">
                Download Invoice
            </button>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from "vue";
import { listenToPayment } from "../firebase";
import axios from "axios";

export default {
    props: {
        bookingId: {
            type: Number,
            required: true,
        },
    },

    setup(props) {
        const loading = ref(true);
        const paymentStatus = ref(null);
        const qrCode = ref(null);
        const amount = ref(0);
        const bookingCode = ref("");

        let unsubscribe = null;

        onMounted(async () => {
            try {
                // Generate QR via API
                const response = await axios.post(
                    `/api/payments/${props.bookingId}/qris`,
                    {
                        payment_method: "qris",
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${localStorage.getItem("token")}`,
                        },
                    },
                );

                const data = response.data.data;
                qrCode.value = data.qr_code;
                amount.value = data.amount;

                // Firebase listener
                unsubscribe = listenToPayment(props.bookingId, (data) => {
                    if (data) {
                        paymentStatus.value = data.status;
                        bookingCode.value = data.booking_code || "";
                    }
                    loading.value = false;
                });
            } catch (error) {
                console.error("Error:", error);
                loading.value = false;
            }
        });

        onUnmounted(() => {
            if (unsubscribe) unsubscribe();
        });

        const formatPrice = (price) => {
            return new Intl.NumberFormat("id-ID").format(price);
        };

        const downloadInvoice = () => {
            window.location.href = `/api/invoice/${props.bookingId}/download`;
        };

        return {
            loading,
            paymentStatus,
            qrCode,
            amount,
            bookingCode,
            formatPrice,
            downloadInvoice,
        };
    },
};
</script>

<style scoped>
.payment-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.qr-image {
    width: 250px;
    height: 250px;
}
.badge.pending {
    background: #fff3cd;
    color: #856404;
    padding: 4px 12px;
    border-radius: 20px;
}
.btn-primary {
    background: #0d6efd;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
}
</style>
