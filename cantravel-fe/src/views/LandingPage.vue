<template>
    <div class="min-h-screen bg-white">
        <!-- NAVBAR -->
        <nav
            class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md z-50 border-b border-gray-100"
        >
            <div class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center gap-2">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center"
                        >
                            <span class="text-white text-xl font-bold">🚌</span>
                        </div>
                        <span
                            class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                        >
                            CAN TRAVEL
                        </span>
                    </div>

                    <!-- Menu -->
                    <div class="hidden md:flex items-center gap-8">
                        <a
                            href="#home"
                            class="text-gray-600 hover:text-blue-600 transition-colors"
                            @click="scrollToSection('home')"
                            >Beranda</a
                        >
                        <a
                            href="#features"
                            class="text-gray-600 hover:text-blue-600 transition-colors"
                            @click="scrollToSection('features')"
                            >Fitur</a
                        >
                        <a
                            href="#routes"
                            class="text-gray-600 hover:text-blue-600 transition-colors"
                            @click="scrollToSection('routes')"
                            >Rute</a
                        >
                        <a
                            href="#testimonials"
                            class="text-gray-600 hover:text-blue-600 transition-colors"
                            @click="scrollToSection('testimonials')"
                            >Testimoni</a
                        >
                        <a
                            href="#contact"
                            class="text-gray-600 hover:text-blue-600 transition-colors"
                            @click="scrollToSection('contact')"
                            >Kontak</a
                        >
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center gap-3">
                        <router-link
                            to="/login"
                            class="px-4 py-2 text-gray-600 hover:text-blue-600 transition-colors"
                        >
                            Masuk
                        </router-link>
                        <router-link
                            to="/register"
                            class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold hover:shadow-lg hover:scale-105 transition-all"
                        >
                            Daftar
                        </router-link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- HERO SECTION - FULL HEIGHT -->
        <section
            id="home"
            class="relative min-h-screen flex items-center pt-20"
        >
            <!-- Background Pattern -->
            <div class="absolute inset-0 overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-96 h-96 bg-blue-100 rounded-full blur-3xl opacity-30 translate-x-1/2 -translate-y-1/2"
                ></div>
                <div
                    class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-100 rounded-full blur-3xl opacity-30 -translate-x-1/2 translate-y-1/2"
                ></div>
            </div>

            <div class="container mx-auto px-6 relative">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="space-y-8">
                        <div
                            class="inline-block px-4 py-2 bg-blue-50 rounded-full"
                        >
                            <span class="text-sm font-semibold text-blue-600"
                                >#1 Transportasi Online di Indonesia</span
                            >
                        </div>

                        <h1
                            class="text-5xl md:text-6xl font-bold leading-tight"
                        >
                            <span class="text-gray-900">Perjalanan Nyaman</span>
                            <span
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent block"
                                >Bersama CAN TRAVEL</span
                            >
                        </h1>

                        <p class="text-xl text-gray-600">
                            Aplikasi pemesanan tiket bus online terpercaya
                            dengan ribuan rute dan jutaan pelanggan setia.
                        </p>

                        <!-- Search Box (BISA DIKLIK) -->
                        <div
                            class="bg-white p-4 rounded-2xl shadow-xl border border-gray-100"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <!-- Dari -->
                                <div class="col-span-1">
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Dari</label
                                    >
                                    <select
                                        v-model="search.from"
                                        class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="">Pilih Kota</option>
                                        <option
                                            v-for="city in cities"
                                            :key="city"
                                            :value="city"
                                        >
                                            {{ city }}
                                        </option>
                                    </select>
                                </div>
                                <!-- Ke -->
                                <div class="col-span-1">
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Ke</label
                                    >
                                    <select
                                        v-model="search.to"
                                        class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="">Pilih Kota</option>
                                        <option
                                            v-for="city in cities"
                                            :key="city"
                                            :value="city"
                                        >
                                            {{ city }}
                                        </option>
                                    </select>
                                </div>
                                <!-- Tanggal -->
                                <div class="col-span-1">
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >Tanggal</label
                                    >
                                    <input
                                        type="date"
                                        v-model="search.date"
                                        class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500"
                                    />
                                </div>
                                <!-- Button Cari -->
                                <div class="col-span-1 flex items-end">
                                    <button
                                        @click="searchTickets"
                                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-3 rounded-xl font-semibold hover:shadow-lg transition-all hover:scale-105 active:scale-95"
                                    >
                                        Cari Tiket
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Stats (BISA DIKLIK) -->
                        <div class="grid grid-cols-4 gap-4 pt-4">
                            <div
                                v-for="(stat, index) in stats"
                                :key="index"
                                @click="router.push(stat.link)"
                                class="cursor-pointer hover:scale-105 transition-transform p-2 rounded-lg hover:bg-gray-50"
                            >
                                <div class="text-3xl font-bold text-gray-900">
                                    {{ stat.value }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ stat.label }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Image dengan Rating (BISA DIKLIK) -->
                    <div class="relative">
                        <div class="relative z-10">
                            <img
                                src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1469&q=80"
                                alt="Bus"
                                class="rounded-3xl shadow-2xl"
                            />
                            <!-- Rating yang bisa diklik -->
                            <div
                                @click="router.push('/testimonials')"
                                class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl cursor-pointer hover:shadow-2xl transition-all hover:scale-105"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center"
                                    >
                                        <span class="text-2xl">⭐</span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">
                                            4.8/5.0
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Dari 10rb+ ulasan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES SECTION -->
        <section id="features" class="py-24 bg-gray-50">
            <div class="container mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        Kenapa Pilih CAN TRAVEL?
                    </h2>
                    <p class="text-xl text-gray-600">
                        Nikmati pengalaman pemesanan tiket bus terbaik dengan
                        berbagai keunggulan
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        v-for="(feature, index) in features"
                        :key="index"
                        class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all cursor-pointer hover:scale-105"
                        @click="router.push(feature.link)"
                    >
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6"
                        >
                            <span class="text-3xl">{{ feature.icon }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            {{ feature.title }}
                        </h3>
                        <p class="text-gray-600">
                            {{ feature.description }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- POPULAR ROUTES (BISA DIKLIK) -->
        <section id="routes" class="py-24">
            <div class="container mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        Rute Populer
                    </h2>
                    <p class="text-xl text-gray-600">
                        Rute favorit yang paling banyak dipesan pelanggan
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="(route, index) in popularRoutes"
                        :key="index"
                        @click="selectRoute(route)"
                        class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all border border-gray-100 cursor-pointer hover:scale-105"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                                >
                                    <span class="text-xl">🚏</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">
                                        {{ route.from }} → {{ route.to }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        Mulai dari Rp {{ route.price }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-sm font-semibold"
                            >
                                {{ route.trips }}x/hari
                            </span>
                        </div>
                        <div
                            class="flex items-center justify-between text-sm text-gray-500"
                        >
                            <span> {{ route.duration }}</span>
                            <span>🚌 {{ route.busCount }} BUS</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- HOW IT WORKS -->
        <section class="py-24 bg-gray-50">
            <div class="container mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        Cara Pesan Tiket
                    </h2>
                    <p class="text-xl text-gray-600">
                        Hanya 3 langkah mudah, perjalanan Anda siap dimulai
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="relative">
                            <div
                                class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6"
                            >
                                1
                            </div>
                            <div
                                class="absolute top-10 left-[60%] w-full h-0.5 bg-blue-200 hidden md:block"
                            ></div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Cari Jadwal
                        </h3>
                        <p class="text-gray-600">
                            Pilih rute keberangkatan dan tanggal perjalanan Anda
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="relative">
                            <div
                                class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6"
                            >
                                2
                            </div>
                            <div
                                class="absolute top-10 left-[60%] w-full h-0.5 bg-blue-200 hidden md:block"
                            ></div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Pilih Kursi
                        </h3>
                        <p class="text-gray-600">
                            Pilih kursi favorit Anda dan lakukan pembayaran
                        </p>
                    </div>

                    <div class="text-center">
                        <div
                            class="w-20 h-20 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold mx-auto mb-6"
                        >
                            3
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">
                            Dapatkan E-Tiket
                        </h3>
                        <p class="text-gray-600">
                            E-tiket dikirim ke email, siap untuk perjalanan
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- TESTIMONI -->
        <section id="testimonials" class="py-24">
            <div class="container mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        Apa Kata Mereka
                    </h2>
                    <p class="text-xl text-gray-600">
                        Pengalaman nyata dari pelanggan setia CAN TRAVEL
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div
                        v-for="(testimonial, index) in testimonials"
                        :key="index"
                        class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all cursor-pointer hover:scale-105"
                        @click="router.push('/testimonials')"
                    >
                        <div class="flex items-center gap-2 mb-4">
                            <span
                                v-for="i in 5"
                                :key="i"
                                class="text-yellow-400"
                                >⭐</span
                            >
                        </div>
                        <p class="text-gray-700 mb-6">
                            "{{ testimonial.text }}"
                        </p>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold"
                            >
                                {{ testimonial.name.charAt(0) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">
                                    {{ testimonial.name }}
                                </h4>
                                <p class="text-sm text-gray-500">
                                    {{ testimonial.city }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA BANNER -->
        <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-600">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Siap untuk bepergian?
                </h2>
                <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                    Pesan tiket bus sekarang dan nikmati perjalanan nyaman
                    bersama CAN TRAVEL
                </p>
                <router-link
                    to="/schedules"
                    class="inline-block bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:shadow-xl hover:scale-105 transition-all"
                >
                    Pesan Tiket Sekarang →
                </router-link>
            </div>
        </section>

        <!-- CONTACT SECTION -->
        <section id="contact" class="py-24 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        Hubungi Kami
                    </h2>
                    <p class="text-xl text-gray-600">
                        Ada pertanyaan? Kami siap membantu 24/7
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Telepon -->
                    <div
                        class="text-center p-8 bg-gray-50 rounded-2xl hover:shadow-xl transition-all cursor-pointer hover:scale-105"
                        @click="
                            copyToClipboard('+6281234567890', 'Nomor telepon')
                        "
                    >
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-4"
                        >
                            📞
                        </div>
                        <h3 class="text-xl font-bold mb-2">Telepon</h3>
                        <p class="text-gray-600">+62 812-3456-7890</p>
                        <p class="text-sm text-gray-500 mt-2">24 Jam</p>
                    </div>

                    <!-- Email -->
                    <div
                        class="text-center p-8 bg-gray-50 rounded-2xl hover:shadow-xl transition-all cursor-pointer hover:scale-105"
                        @click="copyToClipboard('info@cantravel.com', 'Email')"
                    >
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-4"
                        >
                            ✉️
                        </div>
                        <h3 class="text-xl font-bold mb-2">Email</h3>
                        <p class="text-gray-600">info@cantravel.com</p>
                        <p class="text-gray-600">cs@cantravel.com</p>
                    </div>

                    <!-- Alamat -->
                    <div
                        class="text-center p-8 bg-gray-50 rounded-2xl hover:shadow-xl transition-all cursor-pointer hover:scale-105"
                        @click="openMaps()"
                    >
                        <div
                            class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-4"
                        >
                            📍
                        </div>
                        <h3 class="text-xl font-bold mb-2">Alamat</h3>
                        <p class="text-gray-600">Jl. Imam Bonjol No. 123</p>
                        <p class="text-gray-600">Semarang</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-gray-900 text-white py-16">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Logo & Sosmed -->
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-2xl font-bold">Can Travel</span>
                        </div>
                        <p class="text-gray-400 mb-4">
                            Aplikasi pemesanan tiket bus online #1 di Indonesia
                        </p>
                    </div>

                    <!-- Perusahaan -->
                    <div>
                        <h4 class="font-bold text-lg mb-4">Perusahaan</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Tentang Kami</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Karir</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Blog</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Kebijakan Privasi</a
                                >
                            </li>
                        </ul>
                    </div>

                    <!--  Kontak Kami -->
                    <div>
                        <h4 class="font-bold text-lg mb-4">Bantuan</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >FAQ</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#contact"
                                    @click.prevent="scrollToSection('contact')"
                                    class="hover:text-white transition-colors cursor-pointer"
                                >
                                    Kontak Kami
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Syarat & Ketentuan</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-white transition-colors"
                                    >Pengaduan</a
                                >
                            </li>
                        </ul>
                    </div>

                    <!-- Download App -->
                    <div>
                        <h4 class="font-bold text-lg mb-4">Download App</h4>
                        <p class="text-gray-400 mb-4">
                            Dapatkan aplikasi CAN TRAVEL di smartphone Anda
                        </p>
                        <div class="space-y-3">
                            <a
                                href="#"
                                class="block bg-gray-800 p-3 rounded-xl hover:bg-gray-700 transition-colors"
                            >
                                <span class="font-semibold"> Google Play</span>
                            </a>
                            <a
                                href="#"
                                class="block bg-gray-800 p-3 rounded-xl hover:bg-gray-700 transition-colors"
                            >
                                <span class="font-semibold"> App Store</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div
                    class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400"
                >
                    <p>&copy; 2026 CAN TRAVEL. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

// Data pencarian
const search = ref({
    from: "",
    to: "",
    date: new Date().toISOString().split("T")[0],
});

// Daftar kota
const cities = [
    "Jakarta",
    "Bandung",
    "Surabaya",
    "Yogyakarta",
    "Bali",
    "Lampung",
    "Semarang",
    "Malang",
];

// Data stats dengan link
const stats = ref([
    { label: "Total Pengguna", value: "999+", link: "/about" },
    { label: "Total Pemesanan", value: "99", link: "/statistik" },
    { label: "Total Bus", value: "77", link: "/buses" },
    { label: "Total Rute", value: "7", link: "/routes" },
]);

// Data fitur
const features = ref([
    {
        icon: "🚌",
        title: "Banyak Pilihan Bus",
        description:
            "Berbagai kelas bus dengan fasilitas terbaik untuk kenyamanan perjalanan Anda",
        link: "/features/buses",
    },
    {
        icon: "💳",
        title: "Pembayaran Mudah",
        description:
            "Support berbagai metode pembayaran termasuk transfer bank, e-wallet, dan kartu kredit",
        link: "/features/payment",
    },
    {
        icon: "🔒",
        title: "Aman & Terpercaya",
        description:
            "Data anda aman dengan sistem kami yang sudah tersertifikasi keamanan",
        link: "/features/security",
    },
]);

// Data rute populer
const popularRoutes = ref([
    {
        from: "Jakarta",
        to: "Bandung",
        price: "100K",
        trips: 12,
        duration: "3 jam",
        busCount: 8,
    },
    {
        from: "Jakarta",
        to: "Surabaya",
        price: "350K",
        trips: 8,
        duration: "12 jam",
        busCount: 6,
    },
    {
        from: "Bandung",
        to: "Jakarta",
        price: "100K",
        trips: 10,
        duration: "3 jam",
        busCount: 7,
    },
    {
        from: "Surabaya",
        to: "Jakarta",
        price: "350K",
        trips: 8,
        duration: "12 jam",
        busCount: 6,
    },
    {
        from: "Jakarta",
        to: "Yogyakarta",
        price: "300K",
        trips: 6,
        duration: "8 jam",
        busCount: 5,
    },
    {
        from: "Bandung",
        to: "Yogyakarta",
        price: "200K",
        trips: 5,
        duration: "6 jam",
        busCount: 4,
    },
]);

// Data testimonial
const testimonials = ref([
    {
        name: "Andi Pratama",
        city: "Jakarta",
        text: "Pelayanannya sangat memuaskan, bus nyaman dan tepat waktu. Pasti akan pakai lagi!",
    },
    {
        name: "Siti Rahma",
        city: "Bandung",
        text: "Aplikasinya mudah digunakan, pembayaran cepat, e-tiket langsung masuk email.",
    },
    {
        name: "Budi Santoso",
        city: "Surabaya",
        text: "Harga bersaing, banyak pilihan bus, customer service responsif. Recomended!",
    },
]);

// Fungsi scroll ke section
const scrollToSection = (sectionId) => {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: "smooth" });
    }
};

// Fungsi cari tiket
const searchTickets = () => {
    if (!search.value.from || !search.value.to) {
        alert("Pilih kota asal dan tujuan");
        return;
    }

    router.push({
        name: "Schedules",
        query: {
            from: search.value.from,
            to: search.value.to,
            date: search.value.date,
        },
    });
};

// Fungsi pilih route
const selectRoute = (route) => {
    search.value.from = route.from;
    search.value.to = route.to;
    scrollToSection("home");
};

// Fungsi copy ke clipboard
const copyToClipboard = (text, type) => {
    navigator.clipboard.writeText(text);
    alert(`${type} berhasil disalin!`);
};

// Fungsi buka maps
const openMaps = () => {
    window.open("https://maps.google.com/?q=Jakarta+Pusat", "_blank");
};
</script>
