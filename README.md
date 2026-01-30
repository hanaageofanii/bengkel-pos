
# Bengkel POS

Aplikasi Point of Sale (POS) Bengkel berbasis Laravel untuk mengelola login pengguna, data karyawan, stok barang, pekerjaan/jasa, dan absensi karyawan.

---

## Instalasi

Jalankan perintah berikut secara berurutan:

```bash
git clone https://github.com/hanaageofanii/bengkel-pos.git
cd bengkel-pos
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
````

Akses aplikasi melalui browser:

```
http://localhost:8000
```

---

## Struktur Data Aplikasi

### Login Page (Users)

Digunakan untuk autentikasi pengguna.

Field:

1. id_user
2. nama
3. email
4. password

---

### Data Karyawan

Digunakan untuk menyimpan data pegawai bengkel.

Field:

1. id_karyawan
2. nama
3. no_telp
4. email

---

### Stok Barang

Digunakan untuk mengelola persediaan barang.

Field:

1. id_barang
2. nama_barang
3. harga_barang
4. jenis (`biasa`, `agent`)
5. jumlah_barang

---

### Pekerjaan / Jasa

Digunakan untuk menyimpan layanan bengkel.

Field:

1. id_pekerjaan
2. nama_jasa
3. harga_jasa
4. jenis (`biasa`, `agent`)

---

### Absensi Karyawan

Digunakan untuk mencatat kehadiran karyawan.

Field:

1. id_karyawan
2. jenis_absen
3. tanggal_absen
4. total_hari_kerja
