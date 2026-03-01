<template>
    <div class="space-y-8 md:space-y-16">
        <!-- Hero Section dengan Parallax Effect -->
        <section
            class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-700 to-indigo-800 text-white rounded-xl md:rounded-3xl shadow-2xl"
        >
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute top-0 left-0 w-48 md:w-96 h-48 md:h-96 bg-white rounded-full -translate-x-24 md:-translate-x-48 -translate-y-24 md:-translate-y-48"
                ></div>
                <div
                    class="absolute bottom-0 right-0 w-48 md:w-96 h-48 md:h-96 bg-white rounded-full translate-x-24 md:translate-x-48 translate-y-24 md:translate-y-48"
                ></div>
            </div>

            <!-- Animated Bus - hidden di mobile -->
            <div
                class="absolute bottom-0 left-0 w-32 md:w-64 h-16 md:h-32 opacity-20 hidden md:block"
            >
                <svg
                    class="w-full h-full animate-bounce-slow"
                    viewBox="0 0 100 50"
                    fill="white"
                >
                    <rect x="10" y="15" width="80" height="25" rx="5" />
                    <circle cx="25" cy="40" r="8" />
                    <circle cx="75" cy="40" r="8" />
                </svg>
            </div>

            <div
                class="relative px-4 md:px-8 py-10 md:py-20 lg:py-28 text-center"
            >
                <!-- Floating Badge -->
                <div
                    class="inline-block bg-white/20 backdrop-blur-sm px-3 md:px-4 py-1 md:py-2 rounded-full text-xs md:text-sm mb-4 md:mb-8 animate-pulse"
                >
                    #1 Travel App in Indonesia 2024
                </div>

                <!-- Main Title dengan Animasi -->
                <h1
                    class="text-3xl md:text-5xl lg:text-7xl font-black mb-4 md:mb-6 tracking-tight"
                >
                    <span class="inline-block animate-float">C</span>
                    <span class="inline-block animate-float animation-delay-100"
                        >A</span
                    >
                    <span class="inline-block animate-float animation-delay-200"
                        >N</span
                    >
                    <span
                        class="inline-block mx-1 md:mx-2 text-yellow-300 animate-float animation-delay-300"
                    ></span>
                    <span class="inline-block animate-float animation-delay-400"
                        >T</span
                    >
                    <span class="inline-block animate-float animation-delay-500"
                        >R</span
                    >
                    <span class="inline-block animate-float animation-delay-600"
                        >A</span
                    >
                    <span class="inline-block animate-float animation-delay-700"
                        >V</span
                    >
                    <span class="inline-block animate-float animation-delay-800"
                        >E</span
                    >
                    <span class="inline-block animate-float animation-delay-900"
                        >L</span
                    >
                </h1>

                <p
                    class="text-sm md:text-xl lg:text-2xl text-blue-100 mb-6 md:mb-12 max-w-3xl mx-auto leading-relaxed px-2"
                >
                    Aplikasi Pemesanan Tiket Bus Online
                    <span class="relative inline-block">
                        <span class="relative z-10 font-bold text-white"
                            >#1 di Indonesia</span
                        >
                        <span
                            class="absolute bottom-0 left-0 w-full h-2 md:h-3 bg-yellow-400/30 -rotate-1"
                        ></span>
                    </span>
                </p>

                <!-- CTA Buttons -->
                <div
                    class="flex flex-col sm:flex-row justify-center gap-3 md:gap-4 px-4"
                >
                    <router-link
                        to="/schedules"
                        class="group relative bg-white text-blue-600 px-6 md:px-8 py-3 md:py-4 rounded-lg md:rounded-xl font-bold text-sm md:text-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 overflow-hidden"
                    >
                        <span
                            class="absolute inset-0 bg-gradient-to-r from-yellow-400 to-yellow-500 translate-y-full group-hover:translate-y-0 transition-transform duration-300"
                        ></span>
                        <span
                            class="relative z-10 flex items-center gap-1 md:gap-2 group-hover:text-white"
                        >
                            Cari Tiket
                            <svg
                                class="w-4 h-4 md:w-5 md:h-5 group-hover:translate-x-1 transition-transform"
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
                        </span>
                    </router-link>

                    <router-link
                        to="/register"
                        class="group border-2 border-white text-white px-6 md:px-8 py-3 md:py-4 rounded-lg md:rounded-xl font-bold text-sm md:text-lg hover:bg-white hover:text-blue-600 hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center gap-1 md:gap-2"
                    >
                        Daftar Sekarang
                        <svg
                            class="w-4 h-4 md:w-5 md:h-5 group-hover:translate-x-1 transition-transform"
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
                    </router-link>
                </div>

                <!-- Stats Counter -->
                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mt-8 md:mt-16"
                >
                    <div
                        v-for="stat in stats"
                        :key="stat.label"
                        class="bg-white/10 backdrop-blur-sm rounded-xl md:rounded-2xl p-3 md:p-6 hover:bg-white/20 transition-all duration-300"
                    >
                        <div
                            class="text-xl md:text-3xl font-bold text-yellow-300"
                        >
                            {{ stat.value }}
                        </div>
                        <div class="text-xs md:text-sm text-blue-100 mt-1">
                            {{ stat.label }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section dengan 3D Cards -->
        <section class="py-8 md:py-16">
            <div class="text-center mb-6 md:mb-12 px-4">
                <h2
                    class="text-xl md:text-3xl font-bold text-gray-800 mb-2 md:mb-4"
                >
                    Kenapa Pilih CAN TRAVEL?
                </h2>
                <p class="text-xs md:text-base text-gray-500 max-w-2xl mx-auto">
                    Nikmati pengalaman pemesanan tiket bus terbaik dengan
                    berbagai keunggulan
                </p>
            </div>

            <div
                class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8 px-4 md:px-0"
            >
                <div
                    v-for="(feature, index) in features"
                    :key="index"
                    class="group relative bg-white rounded-xl md:rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 md:hover:-translate-y-2 overflow-hidden"
                >
                    <!-- Background Pattern -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                    ></div>

                    <!-- Icon Container -->
                    <div class="relative p-4 md:p-8">
                        <div
                            class="w-12 h-12 md:w-20 md:h-20 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg md:rounded-2xl flex items-center justify-center mb-3 md:mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300"
                        >
                            <span class="text-2xl md:text-4xl">{{
                                feature.icon
                            }}</span>
                        </div>

                        <h3
                            class="text-base md:text-xl font-bold text-gray-800 mb-2 md:mb-3 group-hover:text-blue-600 transition-colors"
                        >
                            {{ feature.title }}
                        </h3>

                        <p
                            class="text-xs md:text-base text-gray-500 leading-relaxed"
                        >
                            {{ feature.description }}
                        </p>

                        <!-- Animated Line -->
                        <div
                            class="absolute bottom-0 left-0 w-full h-0.5 md:h-1 bg-gradient-to-r from-blue-500 to-indigo-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-500"
                        ></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial Section -->
        <section
            class="bg-gradient-to-br from-gray-800 to-gray-900 text-white rounded-xl md:rounded-3xl p-4 md:p-12"
        >
            <div class="text-center mb-6 md:mb-10">
                <h2 class="text-xl md:text-3xl font-bold mb-2 md:mb-4">
                    Apa Kata Mereka?
                </h2>
                <p class="text-xs md:text-base text-gray-300 px-4">
                    Sudah ribuan orang merasakan kemudahan memesan tiket bus
                    bersama CAN TRAVEL
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <div
                    v-for="testimonial in testimonials"
                    :key="testimonial.name"
                    class="bg-white/10 backdrop-blur-sm rounded-lg md:rounded-xl p-4 md:p-6 hover:bg-white/20 transition-all duration-300"
                >
                    <div class="flex items-center gap-1 md:gap-2 mb-2 md:mb-4">
                        <svg
                            v-for="n in 5"
                            :key="n"
                            class="w-3 h-3 md:w-5 md:h-5"
                            :class="
                                n <= testimonial.rating
                                    ? 'text-yellow-400'
                                    : 'text-gray-500'
                            "
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                            />
                        </svg>
                    </div>
                    <p class="text-xs md:text-base text-gray-300 mb-3 md:mb-4">
                        "{{ testimonial.comment }}"
                    </p>
                    <div class="flex items-center gap-2 md:gap-3">
                        <div
                            class="w-6 h-6 md:w-10 md:h-10 bg-gradient-to-br from-blue-400 to-indigo-400 rounded-full flex items-center justify-center text-xs md:text-base font-bold"
                        >
                            {{ testimonial.name.charAt(0) }}
                        </div>
                        <div>
                            <p class="text-xs md:text-sm font-semibold">
                                {{ testimonial.name }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ testimonial.role }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Banner -->
        <section
            class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl md:rounded-3xl p-6 md:p-12 text-center"
        >
            <div class="absolute inset-0 opacity-10">
                <div
                    class="absolute top-0 left-0 w-20 md:w-40 h-20 md:h-40 bg-white rounded-full -translate-x-10 md:-translate-x-20 -translate-y-10 md:-translate-y-20 animate-pulse"
                ></div>
                <div
                    class="absolute bottom-0 right-0 w-30 md:w-60 h-30 md:h-60 bg-white rounded-full translate-x-15 md:translate-x-30 translate-y-15 md:translate-y-30 animate-pulse animation-delay-1000"
                ></div>
            </div>

            <div class="relative">
                <h2 class="text-xl md:text-4xl font-bold mb-2 md:mb-4">
                    Siap untuk bepergian?
                </h2>
                <p
                    class="text-sm md:text-xl text-blue-100 mb-4 md:mb-8 max-w-2xl mx-auto px-4"
                >
                    Pesan tiket bus sekarang dan nikmati perjalanan nyaman
                    bersama CAN TRAVEL
                </p>
                <router-link
                    to="/schedules"
                    class="inline-block bg-white text-blue-600 px-6 md:px-8 py-2 md:py-4 rounded-lg md:rounded-xl font-bold text-sm md:text-lg hover:shadow-2xl hover:scale-105 transition-all duration-300"
                >
                    Pesan Sekarang
                </router-link>
            </div>
        </section>
    </div>
</template>

<script setup>
const stats = [
    { label: "Total Pengguna", value: "999+" },
    { label: "Total Pemesanan", value: "99" },
    { label: "Total Bus", value: "77" },
    { label: "Total Rute", value: "7" },
];

const features = [
    {
        icon: "🚌",
        title: "Banyak Pilihan Bus",
        description:
            "Berbagai kelas bus dengan fasilitas terbaik untuk kenyamanan perjalanan Anda.",
    },
    {
        icon: "💳",
        title: "Pembayaran Mudah",
        description:
            "Support metode pembayaran termasuk transfer bank, e-wallet, dan kartu kredit.",
    },
    {
        icon: "🔒",
        title: "Aman & Terpercaya",
        description:
            "Data anda aman dengan sistem kami yang sudah tersertifikasi keamanan.",
    },
];

const testimonials = [
    {
        name: "Andi Pratama",
        role: "Pengusaha",
        comment:
            "Pelayanannya cepat dan mudah. Tinggal pilih kursi, bayar, langsung dapet tiket!",
        rating: 5,
    },
    {
        name: "Siti Rahayu",
        role: "Mahasiswa",
        comment:
            "Aplikasinya keren banget! Gak ribet dan banyak pilihan busnya.",
        rating: 5,
    },
    {
        name: "Budi Santoso",
        role: "Tour Guide",
        comment:
            "Sudah 2 tahun pake CAN TRAVEL, selalu puas dengan pelayanannya.",
        rating: 5,
    },
];
</script>

<style scoped>
@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes bounce-slow {
    0%,
    100% {
        transform: translateX(0);
    }
    50% {
        transform: translateX(10px);
    }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animate-bounce-slow {
    animation: bounce-slow 4s ease-in-out infinite;
}

.animation-delay-100 {
    animation-delay: 0.1s;
}
.animation-delay-200 {
    animation-delay: 0.2s;
}
.animation-delay-300 {
    animation-delay: 0.3s;
}
.animation-delay-400 {
    animation-delay: 0.4s;
}
.animation-delay-500 {
    animation-delay: 0.5s;
}
.animation-delay-600 {
    animation-delay: 0.6s;
}
.animation-delay-700 {
    animation-delay: 0.7s;
}
.animation-delay-800 {
    animation-delay: 0.8s;
}
.animation-delay-900 {
    animation-delay: 0.9s;
}
.animation-delay-1000 {
    animation-delay: 1s;
}
</style>
