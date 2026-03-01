<template>
    <div class="space-y-8">
        <!-- Header dengan Statistik -->
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
        >
            <div>
                <h1
                    class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                >
                    Booking
                </h1>
                <p class="text-gray-500 mt-1">
                    Kelola semua pemesanan tiket Anda
                </p>
            </div>

            <!-- Stat Cards - SEMUA BISA DIKLIK -->
            <div class="flex gap-3">
                <button
                    @click="setFilter('active')"
                    class="px-4 py-2 rounded-xl transition-all"
                    :class="
                        activeFilter === 'active'
                            ? 'bg-blue-100 ring-2 ring-blue-500'
                            : 'bg-blue-50 hover:bg-blue-100'
                    "
                >
                    <span class="text-sm text-gray-600">Active</span>
                    <p class="text-xl font-bold text-blue-600">
                        {{ activeBookings.length }}
                    </p>
                </button>

                <button
                    @click="setFilter('pending')"
                    class="px-4 py-2 rounded-xl transition-all"
                    :class="
                        activeFilter === 'pending'
                            ? 'bg-yellow-100 ring-2 ring-yellow-500'
                            : 'bg-yellow-50 hover:bg-yellow-100'
                    "
                >
                    <span class="text-sm text-gray-600">Pending</span>
                    <p class="text-xl font-bold text-yellow-600">
                        {{ pendingCount }}
                    </p>
                </button>

                <button
                    @click="setFilter('paid')"
                    class="px-4 py-2 rounded-xl transition-all"
                    :class="
                        activeFilter === 'paid'
                            ? 'bg-green-100 ring-2 ring-green-500'
                            : 'bg-green-50 hover:bg-green-100'
                    "
                >
                    <span class="text-sm text-gray-600">Paid</span>
                    <p class="text-xl font-bold text-green-600">
                        {{ paidCount }}
                    </p>
                </button>

                <button
                    @click="setFilter('cancelled')"
                    class="px-4 py-2 rounded-xl transition-all"
                    :class="
                        activeFilter === 'cancelled'
                            ? 'bg-red-100 ring-2 ring-red-500'
                            : 'bg-red-50 hover:bg-red-100'
                    "
                >
                    <span class="text-sm text-gray-600">Cancelled</span>
                    <p class="text-xl font-bold text-red-600">
                        {{ cancelledCount }}
                    </p>
                </button>
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
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- SECTION BOOKINGS -->
        <div v-if="filteredBookings.length > 0" class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-700">
                {{ sectionTitle }}
            </h3>

            <!-- CANCELLED SECTION (khusus filter cancelled) -->
            <div v-if="activeFilter === 'cancelled'" class="space-y-4">
                <div
                    v-for="booking in filteredBookings"
                    :key="booking.id"
                    class="group bg-gray-50 rounded-2xl shadow border border-gray-200 overflow-hidden opacity-75"
                >
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-gray-300 rounded-xl flex items-center justify-center text-gray-600"
                                >
                                    <svg
                                        class="w-6 h-6"
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
                                    <p class="text-sm text-gray-500">
                                        Booking Code
                                    </p>
                                    <p
                                        class="font-mono font-bold text-gray-600"
                                    >
                                        {{ booking.booking_code }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-600"
                            >
                                DIBATALKAN
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-xs text-gray-400">Rute</p>
                                <p class="font-medium text-gray-600">
                                    {{ booking.schedule?.route?.origin }} →
                                    {{ booking.schedule?.route?.destination }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Total</p>
                                <p class="font-medium text-gray-600">
                                    Rp {{ formatPrice(booking.total_price) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <router-link
                                :to="`/booking/${booking.schedule_id}`"
                                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300 transition-colors"
                            >
                                Booking Lagi
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACTIVE BOOKINGS SECTION (untuk filter active, pending, paid) -->
            <div v-else class="space-y-6">
                <transition-group name="fade" tag="div" class="space-y-6">
                    <div
                        v-for="booking in filteredBookings"
                        :key="booking.id"
                        class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:-translate-y-1"
                    >
                        <!-- Status Bar -->
                        <div
                            class="h-2 w-full"
                            :class="statusColor(booking.status)"
                        ></div>

                        <div class="p-6">
                            <!-- Header -->
                            <div
                                class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6"
                            >
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center text-white shadow-lg"
                                    >
                                        <svg
                                            class="w-6 h-6"
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
                                        <p class="text-sm text-gray-500">
                                            Booking Code
                                        </p>
                                        <p
                                            class="font-mono font-bold text-gray-800 text-lg"
                                        >
                                            {{ booking.booking_code }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span
                                        class="px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2"
                                        :class="
                                            statusBadgeClass(booking.status)
                                        "
                                    >
                                        <span
                                            class="w-2 h-2 rounded-full"
                                            :class="
                                                statusDotClass(booking.status)
                                            "
                                        ></span>
                                        {{ getStatusText(booking.status) }}
                                    </span>
                                    <span class="text-sm text-gray-400">
                                        {{ formatDate(booking.created_at) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Main Content Grid -->
                            <div
                                class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6"
                            >
                                <div
                                    class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl group-hover:bg-blue-50 transition-colors duration-300"
                                >
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600"
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
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            Rute
                                        </p>
                                        <p class="font-semibold text-gray-800">
                                            {{
                                                booking.schedule?.route?.origin
                                            }}
                                            <span class="text-blue-500 mx-1"
                                                >→</span
                                            >
                                            {{
                                                booking.schedule?.route
                                                    ?.destination
                                            }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{
                                                formatTime(
                                                    booking.schedule
                                                        ?.departure_time,
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl group-hover:bg-green-50 transition-colors duration-300"
                                >
                                    <div
                                        class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600"
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
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            Tanggal
                                        </p>
                                        <p class="font-semibold text-gray-800">
                                            {{
                                                formatDate(
                                                    booking.schedule
                                                        ?.departure_date,
                                                )
                                            }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{
                                                formatDay(
                                                    booking.schedule
                                                        ?.departure_date,
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl group-hover:bg-purple-50 transition-colors duration-300"
                                >
                                    <div
                                        class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600"
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
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            Total
                                        </p>
                                        <p
                                            class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                                        >
                                            Rp
                                            {{
                                                formatPrice(booking.total_price)
                                            }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ booking.total_passengers }}
                                            penumpang
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Seats Section -->
                            <div class="mb-6">
                                <p class="text-sm text-gray-500 mb-2">
                                    Kursi yang dipesan
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="seat in booking.seats"
                                        :key="seat.id"
                                        class="inline-flex items-center gap-1 bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium border border-blue-100"
                                    >
                                        <svg
                                            class="w-4 h-4"
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
                                        Kursi {{ seat.seat_number }}
                                    </span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div
                                class="flex flex-wrap gap-3 pt-4 border-t border-gray-100"
                            >
                                <router-link
                                    v-if="booking.status === 'pending'"
                                    :to="`/payment/${booking.id}`"
                                    class="group/btn relative overflow-hidden bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-xl transition-all duration-300 flex items-center gap-2"
                                >
                                    <span
                                        class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover/btn:translate-x-[100%] transition-transform duration-700"
                                    ></span>
                                    <span
                                        class="relative z-10 flex items-center gap-2"
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
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            ></path>
                                        </svg>
                                        Bayar Sekarang
                                    </span>
                                </router-link>

                                <button
                                    v-if="booking.status === 'pending'"
                                    @click="cancelBooking(booking.id)"
                                    class="group/btn relative overflow-hidden bg-gradient-to-r from-red-500 to-rose-500 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-xl transition-all duration-300 flex items-center gap-2"
                                >
                                    <span
                                        class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover/btn:translate-x-[100%] transition-transform duration-700"
                                    ></span>
                                    <span
                                        class="relative z-10 flex items-center gap-2"
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
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            ></path>
                                        </svg>
                                        Batalkan
                                    </span>
                                </button>

                                <button
                                    v-if="booking.status === 'paid'"
                                    @click="downloadInvoice(booking.id)"
                                    class="group/btn relative overflow-hidden bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-xl transition-all duration-300 flex items-center gap-2"
                                >
                                    <span
                                        class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover/btn:translate-x-[100%] transition-transform duration-700"
                                    ></span>
                                    <span
                                        class="relative z-10 flex items-center gap-2"
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
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                            ></path>
                                        </svg>
                                        Download Invoice
                                    </span>
                                </button>

                                <router-link
                                    :to="`/booking/${booking.schedule_id}`"
                                    class="group/btn bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 hover:shadow-lg transition-all duration-300 flex items-center gap-2"
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
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        ></path>
                                    </svg>
                                    Booking Lagi
                                </router-link>
                            </div>
                        </div>
                    </div>
                </transition-group>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="myBookings.length === 0"
            class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100"
        >
            <div
                class="w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6"
            >
                <svg
                    class="w-12 h-12 text-blue-500"
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
            <p class="text-gray-500 text-lg font-medium">Belum ada booking</p>
            <p class="text-gray-400 text-sm mt-1">
                Yuk, pesan tiket bus pertama Anda!
            </p>
            <router-link
                to="/schedules"
                class="inline-block mt-6 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all"
            >
                Cari Tiket
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useBookingStore } from "../stores/booking";
import { usePaymentStore } from "../stores/payment";
import { format } from "date-fns";
import { id } from "date-fns/locale";
import { formatTime } from "../utils/dateHelper";

const bookingStore = useBookingStore();
const paymentStore = usePaymentStore();

const loading = ref(false);
const myBookings = ref([]);
const downloading = ref({});
const activeFilter = ref("active"); // 'active', 'pending', 'paid', 'cancelled'

// Status text helper
const getStatusText = (status) => {
    switch (status) {
        case "pending":
            return "MENUNGGU PEMBAYARAN";
        case "paid":
            return "LUNAS";
        case "cancelled":
            return "DIBATALKAN";
        case "completed":
            return "SELESAI";
        default:
            return status.toUpperCase();
    }
};

// Cek apakah booking expired (pending lebih dari 30 menit)
const isExpired = (booking) => {
    if (booking.status !== "pending") return false;

    const createdTime = new Date(booking.created_at);
    const expiryTime = new Date(createdTime.getTime() + 30 * 60 * 1000);
    const now = new Date();

    return now > expiryTime;
};

// Filter active bookings (pending yang belum expired + paid)
const activeBookings = computed(() => {
    return myBookings.value.filter((b) => {
        // Paid selalu masuk active
        if (b.status === "paid") return true;

        // Pending cek expired
        if (b.status === "pending") {
            return !isExpired(b);
        }

        // Status lain (completed) masuk active
        if (b.status === "completed") return true;

        return false;
    });
});

// Filter cancelled bookings (HANYA YANG CANCELLED)
const cancelledBookings = computed(() => {
    return myBookings.value.filter((b) => b.status === "cancelled");
});

// Computed stats
const pendingCount = computed(() => {
    return myBookings.value.filter(
        (b) => b.status === "pending" && !isExpired(b),
    ).length;
});

const paidCount = computed(() => {
    return myBookings.value.filter((b) => b.status === "paid").length;
});

const cancelledCount = computed(() => {
    return cancelledBookings.value.length;
});

// Filtered bookings berdasarkan activeFilter
const filteredBookings = computed(() => {
    if (activeFilter.value === "active") return activeBookings.value;
    if (activeFilter.value === "pending") {
        return myBookings.value.filter(
            (b) => b.status === "pending" && !isExpired(b),
        );
    }
    if (activeFilter.value === "paid") {
        return myBookings.value.filter((b) => b.status === "paid");
    }
    if (activeFilter.value === "cancelled") {
        return cancelledBookings.value;
    }
    return myBookings.value;
});

// Section title berdasarkan filter
const sectionTitle = computed(() => {
    switch (activeFilter.value) {
        case "active":
            return "Booking Aktif";
        case "pending":
            return "Booking Menunggu Pembayaran";
        case "paid":
            return "Booking Lunas";
        case "cancelled":
            return "Booking Dibatalkan";
        default:
            return "Booking";
    }
});

// Set filter
const setFilter = (filter) => {
    activeFilter.value = filter;
};

// Status styling functions
const statusColor = (status) => {
    switch (status) {
        case "pending":
            return "bg-gradient-to-r from-yellow-400 to-yellow-500";
        case "paid":
            return "bg-gradient-to-r from-green-400 to-green-500";
        case "cancelled":
            return "bg-gradient-to-r from-red-400 to-red-500";
        case "completed":
            return "bg-gradient-to-r from-blue-400 to-blue-500";
        default:
            return "bg-gray-400";
    }
};

const statusBadgeClass = (status) => {
    switch (status) {
        case "pending":
            return "bg-yellow-50 text-yellow-700";
        case "paid":
            return "bg-green-50 text-green-700";
        case "cancelled":
            return "bg-red-50 text-red-700";
        case "completed":
            return "bg-blue-50 text-blue-700";
        default:
            return "bg-gray-50 text-gray-700";
    }
};

const statusDotClass = (status) => {
    switch (status) {
        case "pending":
            return "bg-yellow-500";
        case "paid":
            return "bg-green-500";
        case "cancelled":
            return "bg-red-500";
        case "completed":
            return "bg-blue-500";
        default:
            return "bg-gray-500";
    }
};

// Format helpers
const formatDate = (date) => {
    if (!date) return "-";
    return format(new Date(date), "dd MMM yyyy", { locale: id });
};

const formatDay = (date) => {
    if (!date) return "-";
    return format(new Date(date), "EEEE", { locale: id });
};

const formatPrice = (price) => {
    return new Intl.NumberFormat("id-ID").format(price);
};

const loadBookings = async () => {
    loading.value = true;
    const result = await bookingStore.getMyBookings();
    if (result.success) {
        myBookings.value = result.data;
    }
    loading.value = false;
};

const cancelBooking = async (id) => {
    if (!confirm("Yakin ingin membatalkan booking ini?")) return;

    const result = await bookingStore.cancelBooking(id);
    if (result.success) {
        await loadBookings();
    }
};

const downloadInvoice = async (bookingId) => {
    console.log("📥 Downloading invoice for booking:", bookingId);

    if (!bookingId) {
        console.error("❌ Booking ID is undefined!");
        alert("ID booking tidak valid");
        return;
    }

    downloading.value[bookingId] = true;

    try {
        const result = await paymentStore.downloadInvoice(bookingId);
        console.log("📥 Download result:", result);

        if (result && result.success === false) {
            alert(result.message || "Gagal mengunduh invoice");
            console.error("❌ Download failed:", result);
        } else if (result && result.success === true) {
            console.log("✅ Invoice downloaded successfully");
            alert("Invoice berhasil diunduh");
        }
    } catch (error) {
        console.error("❌ Download error:", error);
        alert("Terjadi kesalahan saat mengunduh invoice");
    } finally {
        downloading.value[bookingId] = false;
    }
};

// Timer untuk auto-refresh
let refreshInterval;
onMounted(() => {
    loadBookings();
    refreshInterval = setInterval(() => {
        loadBookings();
    }, 60000);
});

onUnmounted(() => {
    if (refreshInterval) clearInterval(refreshInterval);
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
