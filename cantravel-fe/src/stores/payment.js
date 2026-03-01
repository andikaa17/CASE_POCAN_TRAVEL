import { defineStore } from "pinia";
import api from "../services/api";

export const usePaymentStore = defineStore("payment", {
    state: () => ({
        payments: [],
        loading: false,
    }),

    actions: {
        async processPayment(bookingId, paymentMethod) {
            try {
                const response = await api.post(
                    `/payments/${bookingId}/process`,
                    {
                        payment_method: paymentMethod,
                    },
                );
                return { success: true, data: response.data.data };
            } catch (error) {
                return {
                    success: false,
                    message:
                        error.response?.data?.message ||
                        "Gagal proses pembayaran",
                };
            }
        },

        async processQris(bookingId) {
            try {
                console.log("🟡 Generating QRIS for booking:", bookingId);

                const response = await api.post(`/payments/${bookingId}/qris`, {
                    payment_method: "qris",
                });

                console.log("🟢 QRIS Response:", response.data);
                return response.data;
            } catch (error) {
                console.log("🔴 QRIS Error:", error.response?.data);
                return (
                    error.response?.data || {
                        success: false,
                        message: "Terjadi kesalahan",
                    }
                );
            }
        },

        // ✅ HAPUS SATU METHOD regenerateQris (yang ini sudah benar)
        async regenerateQris(bookingId) {
            try {
                console.log("🟡 Regenerating QRIS for booking:", bookingId);

                const response = await api.post(
                    `/payments/${bookingId}/regenerate-qris`,
                    { payment_method: "qris" },
                );

                console.log("🟢 Regenerate QRIS Response:", response.data);
                return response.data;
            } catch (error) {
                console.log("🔴 Regenerate QRIS Error:", error.response?.data);
                return (
                    error.response?.data || {
                        success: false,
                        message: "Gagal regenerate QR",
                    }
                );
            }
        },

        async checkPayment(bookingId) {
            try {
                const response = await api.get(`/payments/check/${bookingId}`);
                return { success: true, data: response.data.data };
            } catch (error) {
                return {
                    success: false,
                    message:
                        error.response?.data?.message || "Gagal cek status",
                };
            }
        },

        async getPaymentHistory() {
            this.loading = true;
            try {
                const response = await api.get("/payments/history");
                this.payments = response.data.data;
                return { success: true, data: response.data.data };
            } catch (error) {
                return {
                    success: false,
                    message:
                        error.response?.data?.message || "Gagal ambil riwayat",
                };
            } finally {
                this.loading = false;
            }
        },

        // ✅ PERBAIKI METHOD downloadInvoice
        async downloadInvoice(bookingId) {
            try {
                console.log("📥 Downloading invoice for booking:", bookingId);

                const response = await api.get(
                    `/invoice/${bookingId}/download`,
                    {
                        responseType: "blob",
                        headers: {
                            Accept: "application/pdf",
                        },
                    },
                );

                console.log("✅ Response received:", response);

                // Cek apakah response berupa blob
                if (response.data instanceof Blob) {
                    // Cek apakah blob adalah error JSON
                    if (response.data.type === "application/json") {
                        const text = await response.data.text();
                        const error = JSON.parse(text);
                        console.error("❌ Server error:", error);
                        return {
                            success: false,
                            message: error.message || "Gagal download invoice",
                        };
                    }

                    // Buat URL untuk blob
                    const url = window.URL.createObjectURL(
                        new Blob([response.data], { type: "application/pdf" }),
                    );

                    // Buat link download
                    const link = document.createElement("a");
                    link.href = url;

                    // Ambil nama file dari header Content-Disposition jika ada
                    const contentDisposition =
                        response.headers["content-disposition"];
                    let filename = `invoice-${bookingId}.pdf`;

                    if (contentDisposition) {
                        const filenameMatch = contentDisposition.match(
                            /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/,
                        );
                        if (filenameMatch && filenameMatch[1]) {
                            filename = filenameMatch[1].replace(/['"]/g, "");
                        }
                    }

                    link.setAttribute("download", filename);
                    document.body.appendChild(link);
                    link.click();

                    // Cleanup
                    link.remove();
                    window.URL.revokeObjectURL(url);

                    return { success: true };
                } else {
                    console.error("❌ Response is not a blob:", response.data);
                    return {
                        success: false,
                        message: "Format response tidak valid",
                    };
                }
            } catch (error) {
                console.error("❌ Download invoice error:", error);

                // Handle error response
                if (error.response && error.response.data instanceof Blob) {
                    try {
                        const text = await error.response.data.text();
                        const errorData = JSON.parse(text);
                        return {
                            success: false,
                            message:
                                errorData.message || "Gagal download invoice",
                        };
                    } catch (e) {
                        return {
                            success: false,
                            message: "Gagal download invoice",
                        };
                    }
                }

                return {
                    success: false,
                    message:
                        error.response?.data?.message ||
                        "Gagal download invoice",
                };
            }
        },
    },
});
