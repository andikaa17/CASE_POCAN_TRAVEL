<template>
    <div class="space-y-8">
        <!-- Header dengan gradient -->
        <div class="flex justify-between items-center">
            <h1
                class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
            >
                Jadwal Keberangkatan
            </h1>
            <div
                v-if="availableSchedules.length > 0"
                class="bg-blue-50 px-4 py-2 rounded-full text-sm text-blue-600 font-medium"
            >
                {{ availableSchedules.length }} jadwal tersedia
            </div>
        </div>

        <!-- Search Filters dengan efek glassmorphism -->
        <div
            class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-gray-100"
        >
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <label class="block text-gray-600 text-sm font-medium mb-2"
                        >Dari</label
                    >
                    <div class="relative">
                        <input
                            v-model="filters.origin"
                            type="text"
                            placeholder="Jakarta"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all pl-10"
                        />
                        <svg
                            class="w-5 h-5 text-gray-400 absolute left-3 top-3.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                            ></path>
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            ></path>
                        </svg>
                    </div>
                </div>
                <div class="relative">
                    <label class="block text-gray-600 text-sm font-medium mb-2"
                        >Ke</label
                    >
                    <div class="relative">
                        <input
                            v-model="filters.destination"
                            type="text"
                            placeholder="Bandung"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all pl-10"
                        />
                        <svg
                            class="w-5 h-5 text-gray-400 absolute left-3 top-3.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                            ></path>
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                            ></path>
                        </svg>
                    </div>
                </div>
                <div class="relative">
                    <label class="block text-gray-600 text-sm font-medium mb-2"
                        >Tanggal</label
                    >
                    <div class="relative">
                        <input
                            v-model="filters.date"
                            type="date"
                            :min="today"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all pl-10"
                        />
                        <svg
                            class="w-5 h-5 text-gray-400 absolute left-3 top-3.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            ></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end">
                    <button
                        @click="searchSchedules"
                        :disabled="loading"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-blue-500/25 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg
                            v-if="!loading"
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            ></path>
                        </svg>
                        <svg
                            v-else
                            class="w-5 h-5 animate-spin"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            ></path>
                        </svg>
                        {{ loading ? "Mencari..." : "Cari" }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading Animation -->
        <div v-if="loading" class="flex justify-center py-16">
            <div class="relative">
                <div
                    class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"
                ></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg
                        class="w-6 h-6 text-blue-600 animate-pulse"
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
            </div>
        </div>

        <!-- Results dengan Card Modern -->
        <div v-else class="space-y-4">
            <transition-group name="fade" tag="div" class="space-y-4">
                <div
                    v-for="schedule in availableSchedules"
                    :key="schedule.id"
                    class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden hover:-translate-y-1"
                >
                    <div class="p-6">
                        <!-- Header Card dengan Route -->
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="bg-gradient-to-br from-blue-500 to-indigo-500 text-white px-3 py-1 rounded-full text-sm font-medium"
                            >
                                #{{ schedule.id }}
                            </div>
                            <div
                                class="flex items-center gap-2 text-lg font-semibold text-gray-800"
                            >
                                <span>{{ schedule.route?.origin || "-" }}</span>
                                <svg
                                    class="w-5 h-5 text-blue-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"
                                    ></path>
                                </svg>
                                <span>{{
                                    schedule.route?.destination || "-"
                                }}</span>
                            </div>
                        </div>

                        <!-- Detail Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="bg-blue-50 p-2 rounded-lg group-hover:bg-blue-100 transition-colors"
                                >
                                    <svg
                                        class="w-5 h-5 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">
                                        Keberangkatan
                                    </p>
                                    <p class="font-medium text-gray-800">
                                        {{
                                            formatTime(
                                                schedule.departure_time || "",
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div
                                    class="bg-green-50 p-2 rounded-lg group-hover:bg-green-100 transition-colors"
                                >
                                    <svg
                                        class="w-5 h-5 text-green-600"
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
                                <div>
                                    <p class="text-xs text-gray-500">Bus</p>
                                    <p class="font-medium text-gray-800">
                                        {{ schedule.bus?.name || "-" }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div
                                    class="bg-purple-50 p-2 rounded-lg group-hover:bg-purple-100 transition-colors"
                                >
                                    <svg
                                        class="w-5 h-5 text-purple-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                        ></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">
                                        Kursi Tersedia
                                    </p>
                                    <p class="font-medium text-gray-800">
                                        {{ schedule.available_seats || 0 }}
                                        kursi
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Card -->
                        <div
                            class="flex justify-between items-center pt-4 border-t border-gray-100"
                        >
                            <div>
                                <p class="text-xs text-gray-500">
                                    Harga per kursi
                                </p>
                                <p
                                    class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                                >
                                    Rp {{ formatPrice(schedule.price) }}
                                </p>
                            </div>
                            <router-link
                                :to="
                                    authStore.isAuthenticated
                                        ? `/booking/${schedule.id}`
                                        : '/login'
                                "
                                class="group/btn bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center gap-2 shadow-lg shadow-blue-500/25"
                            >
                                <span>Pilih</span>
                                <svg
                                    class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6"
                                    ></path>
                                </svg>
                            </router-link>
                        </div>
                    </div>
                </div>
            </transition-group>

            <!-- Empty State -->
            <div
                v-if="availableSchedules.length === 0"
                class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100"
            >
                <svg
                    class="w-20 h-20 text-gray-300 mx-auto mb-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1"
                        d="M8 7h8m-8 4h8m-8 4h8M4 5h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2z"
                    ></path>
                </svg>
                <p class="text-gray-500 text-lg">Tidak ada jadwal tersedia</p>
                <p class="text-gray-400 text-sm mt-1">
                    Coba ubah filter pencarian Anda
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { useScheduleStore } from "../stores/schedule";
import { useAuthStore } from "../stores/auth";
import { format } from "date-fns";

const scheduleStore = useScheduleStore();
const authStore = useAuthStore();

const loading = ref(false);
const schedules = ref([]);
const now = ref(new Date());

const today = format(new Date(), "yyyy-MM-dd");

const filters = ref({
    origin: "",
    destination: "",
    date: format(new Date(), "yyyy-MM-dd"),
});

// 🔥 PERBAIKAN 1: Filter berdasarkan departure_datetime
const availableSchedules = computed(() => {
    console.log("Filtering schedules:", schedules.value.length);
    return schedules.value.filter((schedule) => {
        // Cek apakah schedule punya departure_datetime
        if (!schedule.departure_datetime) {
            console.warn("Schedule missing departure_datetime:", schedule);
            return false;
        }

        const departureDateTime = new Date(schedule.departure_datetime);
        const isValid = departureDateTime > now.value;

        if (!isValid) {
            console.log(
                `Schedule ${schedule.id} expired:`,
                schedule.departure_datetime,
            );
        }

        return isValid;
    });
});

// Fungsi format jam
const formatTime = (time) => {
    if (!time) return "-";
    if (typeof time === "string" && time.includes(":")) {
        const parts = time.split(":");
        return `${parts[0]}:${parts[1]}`;
    }
    return time;
};

// Fungsi format harga
const formatPrice = (price) => {
    return new Intl.NumberFormat("id-ID").format(price || 0);
};

// 🔥 PERBAIKAN 2: Tambahin log untuk debug
const searchSchedules = async () => {
    loading.value = true;
    try {
        console.log("🔍 Searching with filters:", filters.value);

        const result = await scheduleStore.getSchedules(filters.value);
        console.log("📦 Raw result:", result);

        if (result && result.success) {
            // 🔥 PERBAIKAN 3: Handle response structure yang berbeda
            if (result.data && Array.isArray(result.data)) {
                schedules.value = result.data;
            } else if (
                result.data &&
                result.data.data &&
                Array.isArray(result.data.data)
            ) {
                schedules.value = result.data.data;
            } else {
                schedules.value = [];
            }

            console.log("✅ Loaded schedules:", schedules.value.length);
        } else {
            console.warn("❌ Failed to load schedules:", result?.message);
            schedules.value = [];
        }
    } catch (error) {
        console.error("❌ Error loading schedules:", error);
        schedules.value = [];
    } finally {
        loading.value = false;
    }
};

// 🔥 PERBAIKAN 4: Auto-refresh setiap 30 detik
let timer;
let refreshTimer;
onMounted(() => {
    searchSchedules();

    // Update waktu setiap menit
    timer = setInterval(() => {
        now.value = new Date();
    }, 60000);

    // Refresh data setiap 30 detik (biar dapet update terbaru)
    refreshTimer = setInterval(() => {
        if (!loading.value) {
            console.log("🔄 Auto-refreshing schedules...");
            searchSchedules();
        }
    }, 30000);
});

// Bersihin timer
onUnmounted(() => {
    if (timer) clearInterval(timer);
    if (refreshTimer) clearInterval(refreshTimer);
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(20px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-20px);
}
</style>
