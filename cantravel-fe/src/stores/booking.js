import { defineStore } from "pinia";
import api from "../services/api";

export const useBookingStore = defineStore("booking", {
    state: () => ({
        schedules: [],
        myBookings: [],
        selectedSchedule: null,
        loading: false,
    }),

    actions: {
        async getSchedules(params = {}) {
            this.loading = true;
            try {
                const response = await api.get("/schedules", { params });
                this.schedules = response.data.data;
                return { success: true, data: response.data };
            } catch (error) {
                return {
                    success: false,
                    message:
                        error.response?.data?.message ||
                        "Gagal mengambil jadwal",
                };
            } finally {
                this.loading = false;
            }
        },

        async checkAvailability(scheduleId, date) {
            try {
                const response = await api.get("/check-availability", {
                    params: { schedule_id: scheduleId, date },
                });
                return { success: true, data: response.data.data };
            } catch (error) {
                return {
                    success: false,
                    message:
                        error.response?.data?.message ||
                        "Gagal cek ketersediaan",
                };
            }
        },

        async createBooking(bookingData) {
            try {
                const response = await api.post("/bookings", bookingData);
                return { success: true, data: response.data.data };
            } catch (error) {
                return {
                    success: false,
                    message: error.response?.data?.message || "Gagal booking",
                };
            }
        },

        async getMyBookings() {
            this.loading = true;
            try {
                const response = await api.get("/my-bookings");
                this.myBookings = response.data.data;
                return { success: true, data: response.data.data };
            } catch (error) {
                return {
                    success: false,
                    message:
                        error.response?.data?.message ||
                        "Gagal mengambil booking",
                };
            } finally {
                this.loading = false;
            }
        },
        async cancelBooking(id) {
            try {
                const response = await api.post(`/bookings/${id}/cancel`);
                return { success: true, data: response.data.data };
            } catch (error) {
                return {
                    success: false,
                    message:
                        error.response?.data?.message ||
                        "Gagal membatalkan booking",
                };
            }
        },
    },
});
