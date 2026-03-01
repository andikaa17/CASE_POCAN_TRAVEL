import { format } from "date-fns";
import { id } from "date-fns/locale";

export const formatScheduleDate = (datetime) => {
    if (!datetime) return "-";
    try {
        // Handle format "2026-02-28 00:00:00 2026-02-28 06:00:00"
        const parts = datetime.split(" ");
        if (parts.length > 4) {
            // Format: "2026-02-28 06:00:00"
            const cleanDate = `${parts[0]} ${parts[4]}`;
            return format(new Date(cleanDate), "EEEE, dd MMM yyyy HH:mm", {
                locale: id,
            });
        }
        return format(new Date(datetime), "EEEE, dd MMM yyyy HH:mm", {
            locale: id,
        });
    } catch (error) {
        console.error("Error format date:", datetime, error);
        return "-";
    }
};

export const formatTime = (datetime) => {
    if (!datetime) return "-";
    try {
        const parts = datetime.split(" ");
        if (parts.length > 4) {
            return parts[4].substring(0, 5); // "HH:MM"
        }
        return format(new Date(datetime), "HH:mm");
    } catch (error) {
        return "-";
    }
};
