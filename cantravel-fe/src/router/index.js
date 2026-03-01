import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../stores/auth";

const routes = [
    {
        path: "/",
        name: "LandingPage", // <-- UBAH JADI LANDINGPAGE
        component: () => import("../views/LandingPage.vue"),
        meta: { guest: true }, // <-- TAMBAH INI! HANYA UNTUK YANG BELUM LOGIN
    },
    {
        path: "/home", // <-- TAMBAH ROUTE HOME UNTUK YANG SUDAH LOGIN
        name: "Home",
        component: () => import("../views/Home.vue"),
        meta: { requiresAuth: true }, // <-- HARUS LOGIN
    },
    {
        path: "/login",
        name: "Login",
        component: () => import("../views/Login.vue"),
        meta: { guest: true },
    },
    {
        path: "/register",
        name: "Register",
        component: () => import("../views/Register.vue"),
        meta: { guest: true },
    },
    {
        path: "/schedules",
        name: "Schedules",
        component: () => import("../views/Schedules.vue"),
        // TANPA META -> BISA SEMUA ORANG (SESUAI KEINGINAN ANDA)
    },
    {
        path: "/booking/:scheduleId",
        name: "Booking",
        component: () => import("../views/Booking.vue"),
        meta: { requiresAuth: true }, // <-- HARUS LOGIN UNTUK BOOKING
    },
    {
        path: "/my-bookings",
        name: "MyBookings",
        component: () => import("../views/MyBookings.vue"),
        meta: { requiresAuth: true },
    },
    {
        path: "/payment/:bookingId",
        name: "Payment",
        component: () => import("../views/Payment.vue"),
        meta: { requiresAuth: true },
    },
    {
        path: "/profile",
        name: "Profile",
        component: () => import("../views/Profile.vue"),
        meta: { requiresAuth: true },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation Guard
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    const isAuthenticated = authStore.isAuthenticated;

    // KALO SUDAH LOGIN, GA BISA KE HALAMAN GUEST
    if (to.meta.guest && isAuthenticated) {
        next("/home"); // <-- ARAHKAN KE HOME
    }
    // KALO BELUM LOGIN, GA BISA KE HALAMAN YANG BUTUH AUTH
    else if (to.meta.requiresAuth && !isAuthenticated) {
        next("/"); // <-- ARAHKAN KE LANDING PAGE
    } else {
        next();
    }
});

export default router;
