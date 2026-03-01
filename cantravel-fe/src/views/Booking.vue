<template>
    <div class="max-w-4xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold">Booking Tiket</h1>

        <!-- Loading State -->
        <div v-if="loading.init" class="text-center py-12">
            <div class="text-gray-500">Memuat data...</div>
        </div>

        <template v-else>
            <!-- Schedule Info -->
            <div v-if="schedule" class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Detail Perjalanan</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-500">Rute</span>
                        <div class="font-semibold">
                            {{ schedule.route?.origin || "Jakarta" }} →
                            {{ schedule.route?.destination || "Bandung" }}
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500">Tanggal</span>
                        <div class="font-semibold">
                            {{ formatDate(schedule.departure_date) }}
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500">Jam</span>
                        <div class="font-semibold">
                            {{ formatTime(schedule.departure_time) || "-" }} WIB
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500">Bus</span>
                        <div class="font-semibold">
                            {{ schedule.bus?.bus_number || "BUS-001" }} ({{
                                schedule.bus?.bus_type || "Executive"
                            }})
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500">Harga</span>
                        <div class="font-semibold text-blue-600">
                            Rp {{ formatPrice(schedule.price) }}/kursi
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seat Selection -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Pilih Kursi</h2>

                <!-- Loading Kursi -->
                <div v-if="loading.seats" class="text-center py-8">
                    <div class="text-gray-500">Loading kursi...</div>
                </div>

                <!-- Kursi Kosong -->
                <div
                    v-else-if="seats.length === 0"
                    class="text-center py-8 text-gray-500"
                >
                    Tidak ada kursi tersedia untuk jadwal ini
                </div>

                <!-- SEAT MAP -->
                <div v-else class="space-y-6">
                    <!-- Legend -->
                    <div class="flex justify-center gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-5 h-5 bg-green-100 border-2 border-green-200 rounded"
                            ></div>
                            <span>Tersedia</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 bg-blue-500 rounded"></div>
                            <span>Dipilih</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 bg-gray-200 rounded"></div>
                            <span>Terisi</span>
                        </div>
                    </div>

                    <!-- Bus Container -->
                    <div class="bg-gray-50 p-6 rounded-xl max-w-lg mx-auto">
                        <!-- Driver & Door -->
                        <div
                            class="flex justify-between items-center mb-6 text-sm"
                        >
                            <div
                                class="bg-gray-800 text-white px-4 py-1 rounded-full"
                            >
                                🚌 SOPIR
                            </div>
                            <div
                                class="border-2 border-gray-400 px-4 py-1 rounded-full text-gray-600"
                            >
                                PINTU
                            </div>
                        </div>

                        <!-- SEAT MAP SEDERHANA -->
                        <div class="grid grid-cols-4 gap-2 max-w-md mx-auto">
                            <div
                                v-for="seat in seats"
                                :key="seat.id"
                                @click="toggleSeat(seat)"
                                :class="[
                                    'p-3 text-center rounded-lg cursor-pointer transition',
                                    !seat.is_available
                                        ? 'bg-gray-200 cursor-not-allowed text-gray-500'
                                        : selectedSeats.includes(seat.id)
                                          ? 'bg-blue-500 text-white hover:bg-blue-600'
                                          : 'bg-green-100 hover:bg-green-200',
                                ]"
                            >
                                {{ seat.seat_number }}
                            </div>
                        </div>

                        <!-- Bus Back -->
                        <div class="text-center mt-6">
                            <div
                                class="inline-block bg-red-400 text-white px-6 py-1 rounded-full text-sm"
                            >
                                BELAKANG
                            </div>
                        </div>
                    </div>

                    <!-- Selected Count -->
                    <div class="text-sm text-gray-500 text-center">
                        Dipilih: {{ selectedSeats.length }} kursi
                    </div>
                </div>
            </div>

            <!-- Passenger Data -->
            <div
                v-if="selectedSeats.length > 0"
                class="bg-white rounded-lg shadow p-6"
            >
                <h2 class="text-lg font-semibold mb-4">Data Penumpang</h2>

                <div
                    v-for="(seat, index) in selectedSeats"
                    :key="seat"
                    class="mb-6 p-4 border rounded-lg"
                >
                    <h3 class="font-semibold mb-3">
                        Penumpang Kursi {{ getSeatNumber(seat) }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 mb-2"
                                >Nama Lengkap
                                <span class="text-red-500">*</span></label
                            >
                            <input
                                v-model="passengers[index].name"
                                type="text"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                :class="{
                                    'border-red-500': errors[index]?.name,
                                }"
                                required
                                maxlength="255"
                            />
                            <p
                                v-if="errors[index]?.name"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ errors[index].name }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2"
                                >Umur <span class="text-red-500">*</span></label
                            >
                            <input
                                v-model="passengers[index].age"
                                type="number"
                                min="1"
                                max="100"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                :class="{
                                    'border-red-500': errors[index]?.age,
                                }"
                                required
                            />
                            <p
                                v-if="errors[index]?.age"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ errors[index].age }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2"
                                >No. Identitas
                                <span class="text-red-500">*</span></label
                            >
                            <input
                                v-model="passengers[index].id_card"
                                type="text"
                                maxlength="16"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                :class="{
                                    'border-red-500': errors[index]?.id_card,
                                }"
                                required
                            />
                            <p
                                v-if="errors[index]?.id_card"
                                class="text-red-500 text-xs mt-1"
                            >
                                {{ errors[index].id_card }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2"
                                >No. HP</label
                            >
                            <input
                                v-model="passengers[index].phone"
                                type="text"
                                maxlength="14"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                    </div>
                </div>

                <!-- Total & Submit -->
                <div class="border-t pt-4 mt-4">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-semibold">Total Harga:</span>
                        <span class="text-2xl font-bold text-blue-600">
                            Rp {{ formatPrice(totalPrice) }}
                        </span>
                    </div>

                    <div
                        v-if="error"
                        class="bg-red-100 text-red-700 p-3 rounded-lg mb-4"
                    >
                        {{ error }}
                    </div>

                    <button
                        @click="submitBooking"
                        :disabled="bookingLoading"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{
                            bookingLoading
                                ? "Processing..."
                                : "Booking Sekarang"
                        }}
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useBookingStore } from "../stores/booking";
import { useAuthStore } from "../stores/auth";
import { format } from "date-fns";
import { id } from "date-fns/locale";

const route = useRoute();
const router = useRouter();
const bookingStore = useBookingStore();
const authStore = useAuthStore();

const schedule = ref(null);
const seats = ref([]);
const selectedSeats = ref([]);
const passengers = ref([]);
const errors = ref([]);
const loading = ref({
    init: false,
    seats: false,
});
const bookingLoading = ref(false);
const error = ref("");

const scheduleId = route.params.scheduleId;

// ==================== FORMAT HELPERS ====================

// Format tanggal lengkap (Senin, 02 Maret 2026)
const formatDate = (date) => {
    if (!date) return "-";
    try {
        const d = new Date(date);
        if (isNaN(d.getTime())) return "-";
        return format(d, "EEEE, dd MMM yyyy", { locale: id });
    } catch {
        return "-";
    }
};

// Format waktu
const formatTime = (time) => {
    if (!time) return "-";
    if (typeof time === "string" && time.includes(":")) {
        const parts = time.split(":");
        return `${parts[0]}:${parts[1]}`;
    }
    return time;
};

// Format harga
const formatPrice = (price) => {
    return new Intl.NumberFormat("id-ID").format(price || 0);
};

// ==================== HELPER FUNCTIONS ====================

// Get seat number by ID
const getSeatNumber = (seatId) => {
    const seat = seats.value.find((s) => s.id === seatId);
    return seat?.seat_number || "-";
};

// Validasi passengers
const validatePassengers = () => {
    errors.value = [];
    let isValid = true;

    passengers.value.forEach((p, index) => {
        const passengerErrors = {};

        if (!p.name || p.name.trim() === "") {
            passengerErrors.name = "Nama wajib diisi";
            isValid = false;
        } else if (p.name.length > 255) {
            passengerErrors.name = "Nama maksimal 255 karakter";
            isValid = false;
        }

        if (!p.age) {
            passengerErrors.age = "Umur wajib diisi";
            isValid = false;
        } else if (p.age < 1 || p.age > 100) {
            passengerErrors.age = "Umur harus antara 1-100 tahun";
            isValid = false;
        }

        if (!p.id_card || p.id_card.trim() === "") {
            passengerErrors.id_card = "No. Identitas wajib diisi";
            isValid = false;
        } else if (p.id_card.length > 16) {
            passengerErrors.id_card = "No. Identitas maksimal 16 karakter";
            isValid = false;
        }

        if (p.phone && p.phone.length > 14) {
            passengerErrors.phone = "No. HP maksimal 14 karakter";
            isValid = false;
        }

        errors.value[index] = passengerErrors;
    });

    return isValid;
};

// ==================== DATA LOADING ====================

// Load schedule & seats
const loadSchedule = async () => {
    console.log("Loading schedule dengan ID:", scheduleId);

    loading.value.seats = true;
    try {
        const result = await bookingStore.checkAvailability(
            scheduleId,
            format(new Date(), "yyyy-MM-dd"),
        );
        console.log("Hasil checkAvailability:", result);

        if (result.success) {
            console.log("Data schedule:", result.data.schedule);
            console.log("Data seats:", result.data.seats);

            schedule.value = result.data.schedule;
            seats.value = result.data.seats || [];

            console.log("Departure time:", schedule.value?.departure_time);
        } else {
            console.error("Gagal load schedule:", result.message);
            error.value = "Gagal memuat data kursi";
        }
    } catch (err) {
        console.error("Error:", err);
        error.value = "Terjadi kesalahan saat memuat data";
    } finally {
        loading.value.seats = false;
    }
};

// ==================== SEAT SELECTION ====================

// Toggle seat selection
const toggleSeat = (seat) => {
    if (!seat.is_available) return;

    const index = selectedSeats.value.indexOf(seat.id);
    if (index === -1) {
        if (selectedSeats.value.length >= 5) {
            alert("Maksimal 5 kursi per booking");
            return;
        }
        selectedSeats.value.push(seat.id);
        passengers.value.push({
            seat_id: seat.id,
            seat_number: seat.seat_number,
            name: "",
            age: "",
            id_card: "",
            phone: "",
        });
    } else {
        selectedSeats.value.splice(index, 1);
        passengers.value.splice(index, 1);
        // Hapus error untuk index yang dihapus
        errors.value.splice(index, 1);
    }
};

// ==================== COMPUTED ====================

// Total harga
const totalPrice = computed(() => {
    return (schedule.value?.price || 0) * selectedSeats.value.length;
});

// ==================== SUBMIT ====================

// Submit booking
const submitBooking = async () => {
    console.log("📋 Data passengers:", passengers.value);
    console.log("✅ selectedSeats:", selectedSeats.value);

    // Reset error
    error.value = "";

    // Validasi
    if (!validatePassengers()) {
        error.value = "Mohon lengkapi data penumpang dengan benar";
        return;
    }

    // Validasi tambahan
    if (selectedSeats.value.length === 0) {
        error.value = "Pilih minimal 1 kursi";
        return;
    }

    bookingLoading.value = true;

    const bookingData = {
        schedule_id: parseInt(scheduleId),
        seat_ids: selectedSeats.value,
        passengers_data: passengers.value.map((p) => ({
            name: p.name.trim(),
            age: parseInt(p.age),
            id_card: p.id_card.trim(),
            phone: p.phone?.trim() || null, // Gunakan null bukan "-"
        })),
    };

    console.log("🚀 Mengirim data:", JSON.stringify(bookingData, null, 2));

    try {
        const result = await bookingStore.createBooking(bookingData);
        console.log("📨 Hasil booking:", result);

        if (result.success) {
            router.push("/my-bookings");
        } else {
            error.value = result.message || "Gagal booking";

            // Tampilkan error validasi dari backend
            if (result.errors) {
                console.error("Validation errors:", result.errors);

                // Parse error untuk seat_ids
                if (result.errors.seat_ids) {
                    error.value =
                        "Kursi yang dipilih tidak valid atau sudah dipesan";
                }

                // Parse error untuk passengers_data
                if (result.errors.passengers_data) {
                    error.value = "Data penumpang tidak valid";
                }
            }
        }
    } catch (err) {
        console.error("❌ Error:", err);
        error.value = "Terjadi kesalahan saat booking";
    } finally {
        bookingLoading.value = false;
    }
};

// Watch untuk reset error saat selectedSeats berubah
watch(selectedSeats, () => {
    error.value = "";
});

// ==================== LIFECYCLE ====================

onMounted(() => {
    loadSchedule();
});
</script>
