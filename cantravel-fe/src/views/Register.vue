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

                    <!-- Icon User Plus -->
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
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                            ></path>
                        </svg>
                    </div>

                    <h2 class="text-xl md:text-2xl font-bold text-white mb-1">
                        Buat Akun Baru!
                    </h2>
                    <p class="text-xs md:text-sm text-blue-100">
                        Daftar sekarang dan nikmati perjalanan nyaman
                    </p>
                </div>

                <!-- Form Register (TAMPIL KALO BELUM REGISTER) -->
                <div v-if="!showVerification" class="p-6 md:p-8">
                    <form
                        @submit.prevent="handleRegister"
                        class="space-y-4 md:space-y-5"
                    >
                        <!-- Nama Lengkap Field -->
                        <div class="space-y-1 md:space-y-2">
                            <label
                                class="block text-xs md:text-sm font-medium text-gray-700"
                            >
                                Nama Lengkap
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
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        ></path>
                                    </svg>
                                </div>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="block w-full pl-9 md:pl-10 pr-3 py-2 md:py-2.5 text-sm md:text-base border border-gray-200 rounded-lg md:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                />
                            </div>
                        </div>

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

                        <!-- PHONE FIELD -->
                        <div class="space-y-1 md:space-y-2">
                            <label
                                class="block text-xs md:text-sm font-medium text-gray-700"
                            >
                                No. Telepon
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
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                        ></path>
                                    </svg>
                                </div>
                                <input
                                    v-model="form.phone"
                                    type="tel"
                                    class="block w-full pl-9 md:pl-10 pr-3 py-2 md:py-2.5 text-sm md:text-base border border-gray-200 rounded-lg md:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                    placeholder="Contoh: 08123456789"
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
                                    placeholder="Minimal 8 karakter"
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
                            <!-- Password Strength Indicator -->
                            <div v-if="form.password" class="mt-2">
                                <div class="flex gap-1 h-1">
                                    <div
                                        class="flex-1 h-full rounded-full"
                                        :class="passwordStrengthColor[0]"
                                    ></div>
                                    <div
                                        class="flex-1 h-full rounded-full"
                                        :class="passwordStrengthColor[1]"
                                    ></div>
                                    <div
                                        class="flex-1 h-full rounded-full"
                                        :class="passwordStrengthColor[2]"
                                    ></div>
                                </div>
                                <p
                                    class="text-xs mt-1"
                                    :class="passwordStrengthText.color"
                                >
                                    {{ passwordStrengthText.text }}
                                </p>
                            </div>
                        </div>

                        <!-- Konfirmasi Password Field -->
                        <div class="space-y-1 md:space-y-2">
                            <label
                                class="block text-xs md:text-sm font-medium text-gray-700"
                            >
                                Konfirmasi Password
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
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                        ></path>
                                    </svg>
                                </div>
                                <input
                                    v-model="form.password_confirmation"
                                    :type="
                                        showConfirmPassword
                                            ? 'text'
                                            : 'password'
                                    "
                                    class="block w-full pl-9 md:pl-10 pr-10 py-2 md:py-2.5 text-sm md:text-base border border-gray-200 rounded-lg md:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                    :class="{
                                        'border-green-500': passwordsMatch,
                                        'border-red-500':
                                            !passwordsMatch &&
                                            form.password_confirmation,
                                    }"
                                    placeholder="Ketik ulang password"
                                    required
                                />
                                <button
                                    type="button"
                                    @click="
                                        showConfirmPassword =
                                            !showConfirmPassword
                                    "
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <svg
                                        v-if="!showConfirmPassword"
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
                            <!-- Password Match Indicator -->
                            <p
                                v-if="
                                    form.password_confirmation &&
                                    !passwordsMatch
                                "
                                class="text-xs text-red-500 mt-1"
                            >
                                Password tidak cocok
                            </p>
                            <p
                                v-if="
                                    passwordsMatch && form.password_confirmation
                                "
                                class="text-xs text-green-500 mt-1"
                            >
                                ✓ Password cocok
                            </p>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="flex items-start gap-2">
                            <input
                                type="checkbox"
                                v-model="agreeTerms"
                                id="terms"
                                class="mt-1 w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                required
                            />
                            <label for="terms" class="text-xs text-gray-600">
                                Saya menyetujui
                                <a
                                    href="#"
                                    class="text-blue-600 hover:underline"
                                    >Syarat & Ketentuan</a
                                >
                                dan
                                <a
                                    href="#"
                                    class="text-blue-600 hover:underline"
                                    >Kebijakan Privasi</a
                                >
                            </label>
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

                        <!-- Success Message -->
                        <div
                            v-if="success"
                            class="bg-green-50 text-green-600 p-3 md:p-4 rounded-lg md:rounded-xl text-xs md:text-sm flex items-center gap-2"
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
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            {{ success }}
                        </div>

                        <!-- Register Button -->
                        <button
                            type="submit"
                            :disabled="loading || !isFormValid"
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
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                                ></path>
                            </svg>
                            <span>{{
                                loading ? "Memproses..." : "Daftar"
                            }}</span>
                        </button>

                        <!-- Login Link -->
                        <p class="text-center text-xs md:text-sm text-gray-600">
                            Sudah punya akun?
                            <router-link
                                to="/login"
                                class="text-blue-600 hover:text-blue-700 font-semibold hover:underline"
                            >
                                Login
                            </router-link>
                        </p>
                    </form>
                </div>

                <!-- ===== VERIFICATION MODAL (TAMPIL SETELAH REGISTER) ===== -->
                <div v-else class="p-6 md:p-8">
                    <div class="text-center mb-6">
                        <div
                            class="inline-block bg-green-100 p-4 rounded-full mb-4"
                        >
                            <svg
                                class="w-12 h-12 text-green-600"
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

                        <h3 class="text-xl font-bold text-gray-800 mb-2">
                            Verifikasi Email Anda
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">
                            Kami telah mengirimkan link verifikasi ke:
                        </p>

                        <div class="bg-blue-50 p-3 rounded-lg mb-4">
                            <p class="font-semibold text-blue-700">
                                {{ registeredEmail }}
                            </p>
                        </div>

                        <p class="text-sm text-gray-600 mb-6">
                            Silakan cek email Anda (termasuk folder spam) dan
                            klik link verifikasi untuk mengaktifkan akun.
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

                        <!-- Resend Button -->
                        <button
                            @click="resendVerification"
                            :disabled="resendTimer > 0 || resendLoading"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed mb-3"
                        >
                            {{
                                resendLoading
                                    ? "Mengirim..."
                                    : "Kirim Ulang Email Verifikasi"
                            }}
                        </button>

                        <!-- Kembali ke Login -->
                        <router-link
                            to="/login"
                            class="text-sm text-blue-600 hover:underline"
                        >
                            Kembali ke Login
                        </router-link>

                        <!-- Success/Error Message -->
                        <div
                            v-if="verifMessage"
                            class="mt-4 p-3 rounded-lg text-sm"
                            :class="
                                verifMessageType === 'success'
                                    ? 'bg-green-50 text-green-600'
                                    : 'bg-red-50 text-red-600'
                            "
                        >
                            {{ verifMessage }}
                        </div>
                    </div>
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
import { ref, computed, onUnmounted } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";
import axios from "axios";

const authStore = useAuthStore();
const router = useRouter();

const form = ref({
    name: "",
    email: "",
    phone: "",
    password: "",
    password_confirmation: "",
});

const loading = ref(false);
const error = ref("");
const success = ref("");
const showPassword = ref(false);
const showConfirmPassword = ref(false);
const agreeTerms = ref(false);

// ===== VERIFICATION STATE =====
const showVerification = ref(false);
const registeredEmail = ref("");
const resendTimer = ref(60);
const resendLoading = ref(false);
const verifMessage = ref("");
const verifMessageType = ref("success");
let timerInterval = null;

// Password Strength
const passwordStrength = computed(() => {
    const password = form.value.password;
    if (!password) return 0;

    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;

    return strength;
});

const passwordStrengthColor = computed(() => {
    const strength = passwordStrength.value;
    const colors = ["bg-gray-200", "bg-gray-200", "bg-gray-200"];

    if (strength >= 3) colors[0] = "bg-red-500";
    if (strength >= 4) colors[1] = "bg-yellow-500";
    if (strength >= 5) colors[2] = "bg-green-500";

    return colors;
});

const passwordStrengthText = computed(() => {
    const strength = passwordStrength.value;
    if (strength <= 2) return { text: "Lemah", color: "text-red-500" };
    if (strength <= 4) return { text: "Sedang", color: "text-yellow-600" };
    return { text: "Kuat", color: "text-green-600" };
});

const passwordsMatch = computed(() => {
    return form.value.password === form.value.password_confirmation;
});

const isFormValid = computed(() => {
    return (
        form.value.name &&
        form.value.email &&
        form.value.phone &&
        form.value.password &&
        passwordsMatch.value &&
        agreeTerms.value
    );
});

// ===== HANDLE REGISTER =====
const handleRegister = async () => {
    if (!isFormValid.value) return;

    loading.value = true;
    error.value = "";
    success.value = "";

    try {
        console.log("1️⃣ MULAI REGISTER, data:", form.value);

        const result = await authStore.register(form.value);

        console.log("2️⃣ HASIL DARI STORE:", result);
        console.log("3️⃣ SHOW VERIFICATION (sebelum):", showVerification.value);

        if (result.success) {
            console.log("4️⃣ REGISTER SUKSES!");

            registeredEmail.value = form.value.email;
            showVerification.value = true;
            console.log(
                "5️⃣ SHOW VERIFICATION (sesudah):",
                showVerification.value,
            );

            startResendTimer();
            success.value = result.message;
        } else {
            console.log("4️⃣ REGISTER GAGAL:", result.message);
            error.value = result.message;

            if (result.errors) {
                const firstError = Object.values(result.errors)[0];
                error.value = Array.isArray(firstError)
                    ? firstError[0]
                    : firstError;
            }
        }
    } catch (err) {
        console.log("4️⃣ ERROR CATCH:", err);
        error.value = "Terjadi kesalahan saat registrasi";
    }

    loading.value = false;
};

// ===== RESEND VERIFICATION =====
const resendVerification = async () => {
    if (resendTimer.value > 0) return;

    resendLoading.value = true;
    verifMessage.value = "";

    try {
        const response = await axios.post(
            "/api/email/verification-notification",
        );
        verifMessage.value =
            response.data.message || "Email verifikasi telah dikirim ulang";
        verifMessageType.value = "success";
        startResendTimer();
    } catch (error) {
        verifMessage.value =
            error.response?.data?.message || "Gagal mengirim ulang";
        verifMessageType.value = "error";
    } finally {
        resendLoading.value = false;
    }
};

// ===== TIMER =====
const startResendTimer = () => {
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

// Cleanup timer
onUnmounted(() => {
    if (timerInterval) clearInterval(timerInterval);
});
</script>
