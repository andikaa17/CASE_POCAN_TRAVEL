# CASE_POCAN_TRAVEL

# PO CAN Travel - Aplikasi Pemesanan Tiket Bus

## 🎯 Tentang Proyek
PO CAN Travel adalah aplikasi pemesanan tiket bus online yang memudahkan customer mencari dan memesan tiket bus.

## 📊 Struktur Database (7 Tabel)

### 1. Tabel users - Data pengguna
- id : nomor user
- name : nama lengkap
- email : untuk login
- password : kata sandi
- phone : nomor HP
- role : admin/customer

### 2. Tabel buses - Data bus
- id : nomor bus
- bus_number : plat nomor
- name : nama bus
- type : ekonomi/bisnis/elektrik
- total_seats : jumlah kursi
- facilities : AC, toilet, dll

### 3. Tabel routes - Rute perjalanan
- id : nomor rute
- origin : kota asal
- destination : kota tujuan
- distance_km : jarak tempuh
- base_price : harga dasar

### 4. Tabel schedules - Jadwal bus
- id : nomor jadwal
- bus_id : bus yang dipakai
- route_id : rute yang dilalui
- departure_date : tanggal berangkat
- departure_time : jam berangkat
- price : harga tiket
- available_seats : sisa kursi

### 5. Tabel bookings - Pemesanan
- id : nomor booking
- user_id : siapa yang pesan
- schedule_id : jadwal dipilih
- booking_code : kode booking
- total_passengers : jumlah penumpang
- total_price : total harga
- status : pending/paid/cancelled

### 6. Tabel payments - Pembayaran
- id : nomor pembayaran
- booking_id : booking terkait
- amount : jumlah bayar
- payment_method : transfer/cash
- status : pending/success/failed

### 7. Tabel seats - Kursi bus
- id : nomor kursi
- bus_id : bus pemilik kursi
- seat_number : nomor kursi (A1, A2)
- floor : lantai 1/2
- is_available : tersedia/tidak

## 🔗 Relasi Antar Tabel
- **users → bookings** : 1 user bisa banyak booking
- **buses → schedules** : 1 bus bisa banyak jadwal
- **routes → schedules** : 1 rute bisa banyak jadwal
- **schedules → bookings** : 1 jadwal bisa banyak booking
- **bookings → payments** : 1 booking punya 1 pembayaran
- **buses → seats** : 1 bus punya banyak kursi

## 📁 File ERD
File desain database bisa dilihat di folder `docs/erd/`

## 🚀 Cara Menjalankan
```bash
# Coming soon - akan diupdate dengan kode Laravel
