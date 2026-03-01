<template>
    <div
        class="min-h-[80vh] flex items-center justify-center px-4 py-8 md:py-12"
    >
        <div class="w-full max-w-md">
            <!-- Card dengan efek glassmorphism -->
            <div
                class="bg-white/80 backdrop-blur-md rounded-2xl md:rounded-3xl shadow-2xl border border-gray-100 overflow-hidden"
            >
                <!-- Header Gradient -->
                <div
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 md:px-8 py-6 md:py-8 text-center relative overflow-hidden"
                >
                    <!-- Decorative Elements -->
                    <div
                        class="absolute top-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full -translate-x-10 -translate-y-10"
                    ></div>
                    <div
                        class="absolute bottom-0 right-0 w-20 h-20 bg-white opacity-5 rounded-full translate-x-10 translate-y-10"
                    ></div>

                    <!-- Icon Bus -->
                    <div
                        class="inline-block bg-white/20 p-3 md:p-4 rounded-xl md:rounded-2xl mb-3 md:mb-4 backdrop-blur-sm"
                    >
                        <svg
                            class="w-6 h-6 md:w-8 md:h-8 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7h8m-8 4h8m-8 4h8M4 5h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2z"
                            ></path>
                        </svg>
                    </div>

                    <h2 class="text-xl md:text-2xl font-bold text-white mb-1">
                        Selamat Datang Kembali
                    </h2>
                    <p class="text-xs md:text-sm text-blue-100">
                        Silakan login ke akun Anda
                    </p>
                </div>

                <!-- Form Login -->
                <div class="p-6 md:p-8">
                    <!-- ===== NOTIFIKASI VERIFIKASI ===== -->
                    <div
                        v-if="verifMessage"
                        class="mb-4 p-3 md:p-4 rounded-lg md:rounded-xl text-xs md:text-sm"
                        :class="
                            verifMessageType === 'success'
                                ? 'bg-green-50 text-green-600'
                                : 'bg-blue-50 text-blue-600'
                        "
                    >
                        <div class="flex items-center gap-2">
                            <svg
                                v-if="verifMessageType === 'success'"
                                class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            <svg
                                v-else
                                class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            {{ verifMessage }}
                        </div>
                    </div>

                    <form
                        @submit.prevent="handleLogin"
                        class="space-y-4 md:space-y-5"
                    >
                        <!-- Email Field -->
                        <div class="space-y-1 md:space-y-2">
                            <label
                                class="block text-xs md:text-sm font-medium text-gray-700"
                            >
                                Email
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <svg
                                        class="h-4 w-4 md:h-5 md:w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors"
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
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="block w-full pl-9 md:pl-10 pr-3 py-2 md:py-2.5 text-sm md:text-base border border-gray-200 rounded-lg md:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                    placeholder="Masukkan email"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-1 md:space-y-2">
                            <label
                                class="block text-xs md:text-sm font-medium text-gray-700"
                            >
                                Password
                            </label>
                            <div class="relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <svg
                                        class="h-4 w-4 md:h-5 md:w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                        ></path>
                                    </svg>
                                </div>
                                <input
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    class="block w-full pl-9 md:pl-10 pr-10 py-2 md:py-2.5 text-sm md:text-base border border-gray-200 rounded-lg md:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                    placeholder="Masukkan password"
                                    required
                                />
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <svg
                                        v-if="!showPassword"
                                        class="h-4 w-4 md:h-5 md:w-5 text-gray-400 hover:text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        ></path>
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        ></path>
                                    </svg>
                                    <svg
                                        v-else
                                        class="h-4 w-4 md:h-5 md:w-5 text-gray-400 hover:text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                        ></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div
                            v-if="error"
                            class="bg-red-50 text-red-600 p-3 md:p-4 rounded-lg md:rounded-xl text-xs md:text-sm flex items-center gap-2"
                        >
                            <svg
                                class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            {{ error }}
                        </div>

                        <!-- Forgot Password Link -->
                        <div class="text-right">
                            <a
                                href="#"
                                class="text-xs md:text-sm text-blue-600 hover:text-blue-700 hover:underline"
                            >
                                Lupa password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button
                            type="submit"
                            :disabled="loading"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2.5 md:py-3 px-4 rounded-lg md:rounded-xl font-semibold text-sm md:text-base hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 group"
                        >
                            <svg
                                v-if="!loading"
                                class="w-4 h-4 md:w-5 md:h-5 group-hover:translate-x-1 transition-transform"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 16l-4-4m0 0l4-4m-4 4h14"
                                ></path>
                            </svg>
                            <span>{{
                                loading ? "Memproses..." : "Login"
                            }}</span>
                        </button>

                        <!-- Register Link -->
                        <p class="text-center text-xs md:text-sm text-gray-600">
                            Belum punya akun?
                            <router-link
                                to="/register"
                                class="text-blue-600 hover:text-blue-700 font-semibold hover:underline"
                            >
                                Daftar
                            </router-link>
                        </p>
                    </form>
                </div>
            </div>

            <!-- Footer Note -->
            <p class="text-center text-xs text-gray-500 mt-4 md:mt-6">
                © 2024 CAN TRAVEL. All rights reserved.
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter, useRoute } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();

const form = ref({
    email: "",
    password: "",
});

const loading = ref(false);
const error = ref("");
const showPassword = ref(false);

// ===== NOTIFIKASI VERIFIKASI =====
const verifMessage = ref("");
const verifMessageType = ref("success"); // 'success' atau 'info'

onMounted(() => {
    // Cek parameter dari URL (hasil verifikasi dari email)
    if (route.query.verified === "success") {
        verifMessage.value = "Email berhasil diverifikasi! Silakan login.";
        verifMessageType.value = "success";

        // Hapus query dari URL biar ga muncul terus
        router.replace({ query: {} });
    } else if (route.query.verified === "already") {
        verifMessage.value =
            "Email sudah diverifikasi sebelumnya. Silakan login.";
        verifMessageType.value = "success";
        router.replace({ query: {} });
    }
});

const handleLogin = async () => {
    loading.value = true;
    error.value = "";
    verifMessage.value = ""; // Hilangin notifikasi pas login

    const result = await authStore.login(form.value);

    if (result.success) {
        router.push("/");
    } else {
        // ===== CEK APAKAH ERROR KARENA BELUM VERIFIKASI =====
        if (result.data?.need_verification) {
            // Redirect ke halaman verifikasi
            router.push({
                path: "/verify-email",
                query: { email: form.value.email },
            });
        } else {
            error.value = result.message || "Login gagal";
        }
    }

    loading.value = false;
};
</script>
