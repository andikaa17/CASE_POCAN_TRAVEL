# CASE_POCAN_TRAVEL

Aplikasi pemesanan tiket bus online.

## Database

| Tabel   | Keterangan |
|-------  |------------|
| users   | Data pengguna |
| buses   | Data bus |
| routes  | Rute perjalanan |
| schedules| Jadwal bus |
| bookings | Pemesanan tiket |
| payments | Pembayaran |
| seats    | Kursi bus |

## Cara Hubungin Tabel

- 1 user bisa punya banyak booking
- 1 bus bisa punya banyak jadwal
- 1 rute bisa punya banyak jadwal
- 1 jadwal bisa banyak dipesan
- 1 booking punya 1 pembayaran
- 1 bus punya banyak kursi

## File ERD

`docs/erd/desain-erd.drawio`

---

Dibuat oleh: [Andika Apriyanto]
