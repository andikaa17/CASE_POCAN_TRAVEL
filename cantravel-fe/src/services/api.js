import axios from "axios";

const API_KEY = import.meta.env.VITE_API_KEY || "rahasia123";
const BASE_URL = "http://localhost:8000/api";

const api = axios.create({
    baseURL: BASE_URL,
    headers: {
        "X-API-Key": API_KEY,
        "Content-Type": "application/json",
        Accept: "application/json",
    },
    timeout: 30000, // 30 DETIK
});

// Request interceptor
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("token");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        // ===== DEBUG LOG DETAIL =====
        console.log("🔵 ====== API REQUEST DETAIL ======");
        console.log("🟡 [URL]", config.url);
        console.log("🟡 [Method]", config.method?.toUpperCase());
        console.log("🟡 [Headers]", {
            "X-API-Key": config.headers["X-API-Key"],
            Authorization: config.headers.Authorization,
            "Content-Type": config.headers["Content-Type"],
        });
        console.log("🟡 [Data]", config.data || config.params || "");
        console.log("🔵 ================================");

        return config;
    },
    (error) => {
        console.error("🔴 [Request Error]", error);
        return Promise.reject(error);
    },
);

// Response interceptor - YANG DIUBAH
api.interceptors.response.use(
    (response) => {
        console.log("🟢 [API Response]", response.status, response.config.url);
        console.log(
            "🟢 [Response DATA]",
            JSON.stringify(response.data, null, 2),
        );
        console.log(
            "🟢 [Response Type]",
            Array.isArray(response.data) ? "ARRAY" : typeof response.data,
        );

        if (Array.isArray(response.data)) {
            console.log("🟢 [Array Length]", response.data.length);
        }

        if (
            response.data &&
            response.data.data &&
            Array.isArray(response.data.data)
        ) {
            console.log("🟢 [Data Length]", response.data.data.length);
        }

        return response;
    },
    (error) => {
        if (error.code === "ECONNABORTED") {
            console.log("🔴 [API Timeout] Request timeout");
            return Promise.reject({
                response: {
                    data: {
                        message: "Request timeout, silakan coba lagi",
                    },
                },
            });
        }

        if (error.response) {
            console.log(
                "🔴 [API Error]",
                error.response.status,
                error.response.config?.url,
                error.response.data,
            );

            // ===== HANDLE KHUSUS UNTUK "Payment masih berlaku" =====
            if (error.response.data?.data?.expires_in_minutes) {
                console.log("⏱️ [Payment Info]", error.response.data.message);
                // LANJUTKAN ERROR AGAR BISA DI-HANDLE DI COMPONENT
                return Promise.reject(error);
            }

            if (error.response.status === 401) {
                window.location.href = "/login";
                localStorage.removeItem("token");
                localStorage.removeItem("user");
            }

            if (error.response.status === 403) {
                console.log("🚫 [Forbidden] Email belum diverifikasi");
            }

            if (error.response.status === 422) {
                console.log(
                    "⚠️ [Validation Error]",
                    error.response.data.errors,
                );
            }
        } else if (error.request) {
            console.log("🔴 [No Response] Server tidak merespon");
            return Promise.reject({
                response: {
                    data: {
                        message: "Server tidak merespon, periksa koneksi",
                    },
                },
            });
        } else {
            console.log("🔴 [Error]", error.message);
        }

        return Promise.reject(error);
    },
);

export default api;
