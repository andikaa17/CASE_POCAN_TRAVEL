import { defineStore } from "pinia";
import api from "../services/api";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: JSON.parse(localStorage.getItem("user")) || null,
        token: localStorage.getItem("token") || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
        isEmailVerified: (state) => state.user?.email_verified_at !== null,
    },

    actions: {
        async register(userData) {
            try {
                console.log("🚀 [Register] Mengirim data:", userData);

                const response = await api.post("/register", userData);
                console.log("✅ [Register] Response:", response.data);

                if (response.data.success) {
                    // ✅ JANGAN SIMPAN TOKEN!
                    // TAPI kita bisa simpen user kalo perlu
                    // this.user = response.data.data.user;

                    return {
                        success: true,
                        message: response.data.message,
                        email: userData.email,
                        // Kirim data user kalo ada
                        user: response.data.data?.user || null,
                    };
                } else {
                    return {
                        success: false,
                        message: response.data.message || "Registrasi gagal",
                        errors: response.data.errors,
                    };
                }
            } catch (error) {
                console.error("❌ [Register] Error:", error.response?.data);

                // Handle validation errors
                if (error.response?.status === 422) {
                    return {
                        success: false,
                        message: "Validasi gagal",
                        errors: error.response.data.errors,
                    };
                }

                return {
                    success: false,
                    message:
                        error.response?.data?.message || "Registrasi gagal",
                };
            }
        },

        async login(credentials) {
            try {
                console.log("🔵 [Login] Mencoba login:", credentials.email);

                const response = await api.post("/login", credentials);
                console.log("🔵 [Login] Response:", response.data);

                if (response.data.success) {
                    const { user, token } = response.data.data;

                    this.user = user;
                    this.token = token;

                    localStorage.setItem("user", JSON.stringify(user));
                    localStorage.setItem("token", token);

                    console.log("✅ [Login] Sukses, token:", token);
                    return {
                        success: true,
                        user,
                        token,
                    };
                } else {
                    console.log("🔴 [Login] Gagal:", response.data);
                    return {
                        success: false,
                        message: response.data.message,
                        data: response.data.data || null,
                    };
                }
            } catch (error) {
                console.log("🔴 [Login] Error:", error.response?.data);

                // Handle 403 (email belum verifikasi)
                if (error.response?.status === 403) {
                    return {
                        success: false,
                        message: error.response.data.message,
                        data: error.response.data.data || {
                            need_verification: true,
                        },
                    };
                }

                return {
                    success: false,
                    message: error.response?.data?.message || "Login gagal",
                    data: error.response?.data?.data || null,
                };
            }
        },

        async logout() {
            try {
                if (this.token) {
                    await api.post("/logout");
                }
            } catch (error) {
                console.error("Logout error:", error);
            } finally {
                this.user = null;
                this.token = null;
                localStorage.removeItem("user");
                localStorage.removeItem("token");
            }
        },

        async getProfile() {
            try {
                const response = await api.get("/me");
                if (response.data.success) {
                    this.user = response.data.data;
                    localStorage.setItem("user", JSON.stringify(this.user));
                    return { success: true, data: response.data.data };
                }
                return { success: false, message: response.data.message };
            } catch (error) {
                console.error("Get profile error:", error);
                return { success: false, message: "Gagal mengambil profil" };
            }
        },

        async checkVerificationStatus() {
            try {
                const response = await api.get("/email/verify-status");
                return response.data;
            } catch (error) {
                console.error("Check verification error:", error);
                return { success: false, verified: false };
            }
        },

        async resendVerification() {
            try {
                const response = await api.post(
                    "/email/verification-notification",
                );
                return response.data;
            } catch (error) {
                return (
                    error.response?.data || {
                        success: false,
                        message: "Gagal mengirim ulang",
                    }
                );
            }
        },

        // Method baru: update user setelah verifikasi
        async refreshUser() {
            try {
                const response = await api.get("/me");
                if (response.data.success) {
                    this.user = response.data.data;
                    localStorage.setItem("user", JSON.stringify(this.user));
                }
                return response.data;
            } catch (error) {
                console.error("Refresh user error:", error);
            }
        },
    },
});
