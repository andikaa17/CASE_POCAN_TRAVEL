<template>
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header dengan Gradient -->
        <div
            class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-8 text-white"
        >
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full -translate-x-20 -translate-y-20"
                ></div>
                <div
                    class="absolute bottom-0 right-0 w-60 h-60 bg-white rounded-full translate-x-20 translate-y-20"
                ></div>
            </div>

            <div class="relative flex items-center gap-6">
                <!-- Avatar dengan Animasi -->
                <div class="relative group">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center text-4xl font-bold shadow-xl group-hover:scale-105 transition-transform duration-300"
                    >
                        {{ userInitials }}
                    </div>
                    <div
                        class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-400 rounded-full border-4 border-white"
                    ></div>
                </div>

                <!-- User Info -->
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ user?.name }}</h1>
                    <p class="text-blue-100 flex items-center gap-2">
                        <svg
                            class="w-5 h-5"
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
                        {{ user?.email }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div
                v-for="stat in stats"
                :key="stat.label"
                class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">{{ stat.label }}</p>
                        <p class="text-3xl font-bold" :class="stat.color">
                            {{ stat.value }}
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl flex items-center justify-center"
                        :class="stat.bgColor"
                    >
                        <span class="text-2xl">{{ stat.icon }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- UPDATE PROFIL FORM -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center"
                >
                    <svg
                        class="w-5 h-5 text-white"
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
                <h2 class="text-xl font-bold text-gray-800">Update Profil</h2>
            </div>

            <div class="space-y-6">
                <!-- Nama Field -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2"
                        >Nama Lengkap</label
                    >
                    <div class="relative">
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full px-4 py-3 pl-12 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                            :class="{ 'border-red-500': errors.name }"
                            required
                        />
                        <svg
                            class="w-5 h-5 text-gray-400 absolute left-4 top-3.5"
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
                    <p v-if="errors.name" class="text-red-500 text-sm mt-1">
                        {{ errors.name }}
                    </p>
                </div>

                <!-- Email Field (Disabled) -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2"
                        >Email</label
                    >
                    <div class="relative">
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full px-4 py-3 pl-12 border border-gray-200 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed"
                            disabled
                        />
                        <svg
                            class="w-5 h-5 text-gray-400 absolute left-4 top-3.5"
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
                    <p class="text-sm text-gray-500 mt-1">
                        Email tidak bisa diubah
                    </p>
                </div>

                <!-- BUTTON UPDATE PROFIL (GEDE) -->
                <!-- Taruh di dalam form Update Profil, setelah input email -->
                <div class="flex justify-end">
                    <button
                        type="button"
                        @click="updateProfile"
                        :disabled="loading"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ loading ? "Menyimpan..." : "Update Profil" }}
                    </button>
                </div>
            </div>
        </div>

        <!-- GANTI PASSWORD FORM -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center"
                >
                    <svg
                        class="w-5 h-5 text-white"
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
                <h2 class="text-xl font-bold text-gray-800">
                    Ubah Password 
                </h2>
            </div>

            <div class="space-y-6">
                <!-- Current Password -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2"
                        >Password Lama</label
                    >
                    <div class="relative">
                        <input
                            v-model="form.current_password"
                            type="password"
                            class="w-full px-4 py-3 pl-12 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            placeholder="Masukkan password lama"
                        />
                        <svg
                            class="w-5 h-5 text-gray-400 absolute left-4 top-3.5"
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
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2"
                        >Password Baru</label
                    >
                    <div class="relative">
                        <input
                            v-model="form.new_password"
                            type="password"
                            class="w-full px-4 py-3 pl-12 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            placeholder="Minimal 8 karakter"
                        />
                        <svg
                            class="w-5 h-5 text-gray-400 absolute left-4 top-3.5"
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
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2"
                        >Konfirmasi Password Baru</label
                    >
                    <div class="relative">
                        <input
                            v-model="form.new_password_confirmation"
                            type="password"
                            class="w-full px-4 py-3 pl-12 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all"
                            placeholder="Ketik ulang password baru"
                        />
                        <svg
                            class="w-5 h-5 text-gray-400 absolute left-4 top-3.5"
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
                </div>

                <!-- BUTTON UBAH PASSWORD (GEDE) -->
                <div class="flex justify-end">
                    <button
                        type="button"
                        @click="changePassword"
                        :disabled="
                            loading ||
                            (!form.current_password && !form.new_password)
                        "
                        class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ loading ? "Memproses..." : "Ubah Password" }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div
            v-if="error"
            class="bg-red-50 text-red-600 p-4 rounded-xl flex items-center gap-3"
        >
            <svg
                class="w-5 h-5"
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

        <div
            v-if="success"
            class="bg-green-50 text-green-600 p-4 rounded-xl flex items-center gap-3"
        >
            <svg
                class="w-5 h-5"
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
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import { useBookingStore } from "../stores/booking";
import api from "../services/api";

const authStore = useAuthStore();
const bookingStore = useBookingStore();
const user = computed(() => authStore.user);

const userInitials = computed(() => {
    if (!user.value?.name) return "U";
    return user.value.name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
        .substring(0, 2);
});

// Stats data
const stats = ref([
    {
        label: "Total Booking",
        value: "0",
        icon: "🎫",
        color: "text-blue-600",
        bgColor: "bg-blue-50",
    },
    {
        label: "Selesai",
        value: "0",
        icon: "✅",
        color: "text-green-600",
        bgColor: "bg-green-50",
    },
    {
        label: "Pending",
        value: "0",
        icon: "⏳",
        color: "text-yellow-600",
        bgColor: "bg-yellow-50",
    },
    {
        label: "Dibatalkan",
        value: "0",
        icon: "❌",
        color: "text-red-600",
        bgColor: "bg-red-50",
    },
]);

// Form data
const form = ref({
    name: "",
    email: "",
    current_password: "",
    new_password: "",
    new_password_confirmation: "",
});

const loading = ref(false);
const error = ref("");
const success = ref("");
const errors = ref({});

// Load user bookings untuk statistik
const loadUserStats = async () => {
    const result = await bookingStore.getMyBookings();
    if (result.success) {
        const bookings = result.data;
        stats.value[0].value = bookings.length;
        stats.value[1].value = bookings.filter(
            (b) => b.status === "completed" || b.status === "paid",
        ).length;
        stats.value[2].value = bookings.filter(
            (b) => b.status === "pending",
        ).length;
        stats.value[3].value = bookings.filter(
            (b) => b.status === "cancelled",
        ).length;
    }
};

// UPDATE PROFIL
const updateProfile = async () => {
    loading.value = true;
    error.value = "";
    success.value = "";
    errors.value = {};

    try {
        const response = await api.put("/profile", {
            name: form.value.name,
        });

        success.value = "Profil berhasil diupdate!";
        await authStore.getProfile();

        setTimeout(() => {
            success.value = "";
        }, 3000);
    } catch (err) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors || {};
            error.value = "Validasi gagal";
        } else {
            error.value = err.response?.data?.message || "Gagal update profil";
        }
    } finally {
        loading.value = false;
    }
};

// GANTI PASSWORD
const changePassword = async () => {
    // CEK APAKAH FIELD PASSWORD DIISI
    if (
        !form.value.current_password &&
        !form.value.new_password &&
        !form.value.new_password_confirmation
    ) {
        error.value = "Harap isi form password";
        setTimeout(() => {
            error.value = "";
        }, 3000);
        return;
    }

    // VALIDASI SEDERHANA
    if (form.value.new_password !== form.value.new_password_confirmation) {
        error.value = "Konfirmasi password tidak cocok";
        setTimeout(() => {
            error.value = "";
        }, 3000);
        return;
    }

    if (form.value.new_password && form.value.new_password.length < 8) {
        error.value = "Password minimal 8 karakter";
        setTimeout(() => {
            error.value = "";
        }, 3000);
        return;
    }

    loading.value = true;
    error.value = "";

    try {
        const response = await api.post("/profile/change-password", {
            current_password: form.value.current_password,
            new_password: form.value.new_password,
            new_password_confirmation: form.value.new_password_confirmation,
        });

        success.value = response.data.message || "Password berhasil diubah!";

        // Reset form password
        form.value.current_password = "";
        form.value.new_password = "";
        form.value.new_password_confirmation = "";

        setTimeout(() => {
            success.value = "";
        }, 3000);
    } catch (err) {
        console.error("Error change password:", err.response);

        if (err.response?.data?.message) {
            error.value = err.response.data.message;
        } else if (err.response?.data?.errors) {
            const firstError = Object.values(err.response.data.errors)[0];
            error.value = Array.isArray(firstError)
                ? firstError[0]
                : firstError;
        } else {
            error.value = "Gagal mengubah password";
        }

        setTimeout(() => {
            error.value = "";
        }, 5000);
    } finally {
        loading.value = false;
    }
};

onMounted(async () => {
    form.value.name = user.value?.name || "";
    form.value.email = user.value?.email || "";
    await loadUserStats();
});
</script>
