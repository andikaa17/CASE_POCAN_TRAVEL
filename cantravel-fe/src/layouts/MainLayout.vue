<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Navbar -->
        <nav
            class="bg-white/80 backdrop-blur-md shadow-lg sticky top-0 z-50 border-b border-gray-200"
        >
            <div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
                <div class="flex justify-between h-14 md:h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <router-link
                            to="/"
                            class="flex items-center space-x-1 md:space-x-2 group"
                        >
                            <div
                                class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white p-1.5 md:p-2 rounded-lg md:rounded-xl"
                            >
                                <svg
                                    class="w-4 h-4 md:w-5 md:h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7h8m-8 4h8m-8 4h8M4 5h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2z"
                                    />
                                </svg>
                            </div>
                            <span
                                class="text-sm md:text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent"
                            >
                                CAN TRAVEL
                            </span>
                        </router-link>

                        <!-- Desktop Menu -->
                        <div class="hidden md:flex ml-10 space-x-1">
                            <router-link
                                to="/schedules"
                                class="px-4 py-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all"
                            >
                                Jadwal
                            </router-link>
                            <router-link
                                v-if="authStore.isAuthenticated"
                                to="/my-bookings"
                                class="px-4 py-2 rounded-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-all"
                            >
                                Booking
                            </router-link>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex items-center md:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="p-2 text-gray-600 hover:text-blue-600"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    v-if="!mobileMenuOpen"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    v-else
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Desktop User Menu -->
                    <div class="hidden md:flex items-center space-x-3">
                        <template v-if="authStore.isAuthenticated">
                            <router-link
                                to="/profile"
                                class="flex items-center space-x-3 bg-gradient-to-r from-blue-50 to-indigo-50 px-3 py-1.5 rounded-full"
                            >
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-semibold text-sm"
                                >
                                    {{ userInitials }}
                                </div>
                                <span class="text-gray-700 font-medium">{{
                                    authStore.user?.name || "User"
                                }}</span>
                            </router-link>
                            <button
                                @click="logout"
                                class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100"
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
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    />
                                </svg>
                            </button>
                        </template>
                        <template v-else>
                            <router-link
                                to="/login"
                                class="px-4 py-2 rounded-lg text-gray-600 hover:text-blue-600"
                                >Login</router-link
                            >
                            <router-link
                                to="/register"
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg"
                                >Register</router-link
                            >
                        </template>
                    </div>
                </div>

                <!-- Mobile Menu Dropdown -->
                <div
                    v-if="mobileMenuOpen"
                    class="md:hidden py-3 border-t border-gray-100"
                >
                    <router-link
                        to="/schedules"
                        class="block px-4 py-2 text-gray-600 hover:bg-blue-50"
                        @click="mobileMenuOpen = false"
                        >Jadwal</router-link
                    >
                    <router-link
                        v-if="authStore.isAuthenticated"
                        to="/my-bookings"
                        class="block px-4 py-2 text-gray-600 hover:bg-blue-50"
                        @click="mobileMenuOpen = false"
                        >My Bookings</router-link
                    >

                    <template v-if="authStore.isAuthenticated">
                        <router-link
                            to="/profile"
                            class="block px-4 py-2 text-gray-600 hover:bg-blue-50 border-t border-gray-100"
                            @click="mobileMenuOpen = false"
                        >
                            Profile ({{ authStore.user?.name }})
                        </router-link>
                        <button
                            @click="logoutMobile"
                            class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50"
                        >
                            Logout
                        </button>
                    </template>
                    <template v-else>
                        <router-link
                            to="/login"
                            class="block px-4 py-2 text-gray-600 hover:bg-blue-50 border-t border-gray-100"
                            @click="mobileMenuOpen = false"
                            >Login</router-link
                        >
                        <router-link
                            to="/register"
                            class="block px-4 py-2 text-gray-600 hover:bg-blue-50"
                            @click="mobileMenuOpen = false"
                            >Register</router-link
                        >
                    </template>
                </div>
            </div>
        </nav>

        <!-- Main Content - Padding Responsif -->
        <main
            class="max-w-7xl mx-auto py-4 md:py-8 px-3 sm:px-4 md:px-6 lg:px-8"
        >
            <router-view />
        </main>

        <!-- Footer -->
        <footer
            class="bg-white border-t border-gray-200 mt-8 md:mt-12 py-4 md:py-8"
        >
            <div class="max-w-7xl mx-auto px-3 md:px-4 text-center">
                <p class="text-xs md:text-sm text-gray-500">
                    © 2024 CAN TRAVEL. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();
const mobileMenuOpen = ref(false);

const userInitials = computed(() => {
    if (!authStore.user?.name) return "U";
    return authStore.user.name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
        .substring(0, 2);
});

const logout = async () => {
    await authStore.logout();
    router.push("/");
};

const logoutMobile = async () => {
    mobileMenuOpen.value = false;
    await logout();
};
</script>
