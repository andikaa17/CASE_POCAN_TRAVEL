import { defineStore } from "pinia";
import api from "../services/api";

export const useScheduleStore = defineStore("schedule", {
    state: () => ({
        schedules: [],
        loading: false,
    }),

    actions: {
        async getSchedules(params = {}) {
            this.loading = true;
            try {
                const response = await api.get("/schedules", { params });

                // 🔥 AMANIN DATA - KALO RESPONSE BEDA STRUCTURE
                if (response.data && response.data.data) {
                    this.schedules = response.data.data;
                } else if (Array.isArray(response.data)) {
                    this.schedules = response.data;
                } else {
                    this.schedules = [];
                }

                return {
                    success: true,
                    data: response.data,
                };
            } catch (error) {
                console.error("Schedule store error:", error);
                return {
                    success: false,
                    message:
                        error.response?.data?.message || "Gagal ambil jadwal",
                };
            } finally {
                this.loading = false;
            }
        },
    },
});
