# ⚡ PANDAWA DELIVERY & JASTIP

Aplikasi Web On-Demand berbasis **Laravel 13 & MySQL** untuk klien **Pandawa Delivery & Jastip**.
Mengusung desain elegan bernuansa **Deep Navy & Amber Gold**, terintegrasi penuh dengan database relasional, armada kurir dedicated, dan struk WhatsApp otomatis.

---

## 🌐 Cara Menjalankan di Laragon

Aplikasi berada di direktori:
```
D:\laragon\www\pandawa
```

### Langkah Mudah:
1. Buka aplikasi **Laragon** di Windows.
2. Klik tombol **Start All** (memastikan Apache & MySQL aktif).
3. Buka browser (Chrome / Edge / Firefox), kunjungi:
   ```
   http://localhost/pandawa/public
   ```
   *atau (jika Pretty URL virtual host Laragon aktif):*
   ```
   http://pandawa.test
   ```

---

## 📝 PANDUAN LENGKAP: Cara Mengubah Teks, Gambar, dan Data Website

Berikut panduan langkah demi langkah jika Anda ingin mengubah tulisan, logo, foto produk, harga, nomor WhatsApp, hingga data kurir:

---

### 1. ✏️ Cara Mengubah Teks & Tulisan di Website (Judul, Banner, Slogan)

Semua teks tampilan antarmuka (UI) berada di file Blade berikut:
📂 **Lokasi File:** `D:\laragon\www\pandawa\resources\views\pandawa\index.blade.php`

Anda bisa membukanya menggunakan text editor seperti **VS Code**, **Sublime Text**, atau **Notepad**:

- **Mengubah Nama Brand / Logo:**
  - Cari kata `PANDAWA` atau `Delivery & Jastip` (sekitar baris 80–95).
  - Anda bisa mengganti teks tersebut dengan nama lain jika diperlukan.
- **Mengubah Teks Banner Sambutan (Hero):**
  - Cari kata `Antar Barang, Belanja Katalog & Jastip Apa Saja Cepat Sampai!` (sekitar baris 130).
  - Anda bisa menggantinya dengan slogan promosi baru Anda.
- **Mengubah Teks Info Layanan & Footer:**
  - Cukup gunakan fitur pencarian (**Ctrl + F**) di text editor, ketik kata yang ingin Anda ubah, lalu ganti dengan kata baru.
  - Setelah selesai, tekan **Ctrl + S** (Simpan) dan refresh browser Anda (**F5**).

---

### 2. 🖼️ Cara Mengubah Gambar & Foto

Ada 2 cara untuk mengganti gambar di website:

#### Opsi A: Menggunakan Link Foto Online (Paling Praktis)
Anda bisa menggunakan URL gambar langsung dari internet (misal dari Unsplash, Pexels, atau Google Drive/Cloudinary):
1. Salin link gambar, contoh: `https://images.unsplash.com/photo-1585032226651-759b368d7246?w=600`
2. Tempelkan link tersebut ke dalam tag `<img src="...">` di file `index.blade.php`, atau masukkan ke kolom `image` pada database produk.

#### Opsi B: Menggunakan File Foto Sendiri dari Komputer
Jika Anda punya foto produk atau logo sendiri:
1. Buat folder baru (jika belum ada):
   ```
   D:\laragon\www\pandawa\public\images\
   ```
2. Salin file foto Anda ke dalam folder tersebut, contoh:
   `D:\laragon\www\pandawa\public\images\logo-pandawa.png`
   `D:\laragon\www\pandawa\public\images\ayam-bakar.jpg`
3. Untuk menampilkan foto di file `index.blade.php`, gunakan kode:
   ```html
   <img src="{{ asset('images/logo-pandawa.png') }}" alt="Logo">
   ```
4. Untuk foto produk di database, Anda cukup mengisi link gambar menjadi:
   `/images/ayam-bakar.jpg`

---

### 3. 🛒 Cara Mengubah Data Produk Katalog (Nama, Harga, Foto, Stok)

Produk katalog tersimpan di database MySQL (`pandawa_db`). Anda bisa mengubahnya dengan 2 cara:

#### Cara 1: Melalui phpMyAdmin (Sangat Mudah & Visual)
1. Buka browser dan buka: `http://localhost/phpmyadmin`
2. Di panel sebelah kiri, klik database **`pandawa_db`**.
3. Klik tabel **`products`**.
4. Di daftar produk, klik tombol **Edit (Ubah)** pada produk yang ingin diganti:
   - **`name`** : Nama makanan/produk (misal: *Ayam Bakar Madu Spesial*)
   - **`price`** : Harga jual (misal: *25000*)
   - **`original_price`** : Harga coret sebelum diskon (misal: *30000*)
   - **`description`** : Deskripsi lengkap makanan
   - **`image`** : Link foto produk
   - **`badge`** : Label promo seperti *Diskon 20%*, *Favorit*, *Best Seller*
5. Klik tombol **Kirim (Go)** di bagian bawah.
6. Refresh website Anda, produk akan langsung terupdate!

#### Cara 2: Menambah Produk Baru Lewat phpMyAdmin
- Di tabel **`products`**, klik tab **Tambahkan (Insert)** di bagian atas.
- Isi data produk baru (pilih `category_id` 1 untuk Makanan, 2 Minuman, 3 Sembako, 4 Buah/Snack, 5 Apotek).
- Klik **Kirim (Go)**.

#### Cara 3: Lewat File Seeder Laravel
Anda juga bisa mengubah data bawaan di file:
📂 **Lokasi File:** `D:\laragon\www\pandawa\database\seeders\DatabaseSeeder.php`
Cari bagian `// 3. Products`, ubah nama, harga, atau foto produk, lalu jalankan perintah di terminal:
```bash
cd D:\laragon\www\pandawa
php artisan db:seed
```

---

### 4. 🛵 Cara Mengubah Data Kurir & Nomor WhatsApp Tujuan (PENTING!)

Agar konfirmasi pesanan pelanggan langsung masuk ke WhatsApp asli Anda atau tim kurir Anda:

1. **Ubah di Database (phpMyAdmin):**
   - Buka `http://localhost/phpmyadmin` ➔ pilih `pandawa_db` ➔ tabel **`couriers`**.
   - Klik **Edit** pada kurir (misal *Budi Santoso*).
   - Ubah kolom **`phone`** menjadi nomor WhatsApp Anda (format: `08xxxxxxxxxx`).
   - Ubah kolom **`name`** (nama kurir), **`vehicle`** (jenis motor), dan **`plate_number`** (nomor plat).
   - Klik **Kirim (Go)**.

2. **Ubah Nomor Fallback di Controller:**
   - Buka file: `D:\laragon\www\pandawa\app\Http\Controllers\PandawaController.php`
   - Cari nomor `6281234567890` (sekitar baris 120, 205, 290).
   - Ganti nomor tersebut dengan nomor WhatsApp admin utama Anda (gunakan awalan `62` tanpa tanda `+`).

---

### 5. 💰 Cara Mengubah Tarif Ongkir & Biaya Jastip

Jika Anda ingin menaikkan atau menurunkan tarif pengantaran:

1. **Ubah Tarif di Backend (Logika Simpan Pesanan):**
   📂 **Lokasi File:** `D:\laragon\www\pandawa\app\Http\Controllers\PandawaController.php`
   - **Tarif Delivery KM (GoSend):** Cari fungsi `createDeliveryOrder` (sekitar baris 150):
     - `$baseRate = 10000;` (Tarif dasar motor 3 km pertama)
     - `$perKmRate = 2500;` (Tarif per km berikutnya)
   - **Tarif Jastip:** Cari fungsi `createJastipOrder` (sekitar baris 230):
     - `$jastipFee = 10000;` (Biaya jasa titip)
     - `$shippingCost = 12000;` (Ongkir antar jastip ke rumah)

2. **Ubah Tarif di Frontend (Tampilan Slider & Hitungan Realtime):**
   📂 **Lokasi File:** `D:\laragon\www\pandawa\public\js\pandawa.js`
   - Cari baris `delivery: { baseRate: 10000, perKmRate: 2500 }`.
   - Samakan angkanya dengan tarif yang Anda tentukan di controller agar hitungan slider dan total bayar selalu sinkron.

---

### 6. 🎟️ Cara Mengubah / Menambah Kode Voucher Diskon

Voucher promo tersimpan di tabel `vouchers`.
- Buka `http://localhost/phpmyadmin` ➔ pilih `pandawa_db` ➔ tabel **`vouchers`**.
- Voucher bawaan:
  - `ONGKIRGRATIS` : Potongan ongkir Rp 8.000 (tipe: `fixed`).
  - `PANDAWA10` : Diskon 10% (tipe: `percent`).
  - `PANDAWABARU` : Potongan Rp 15.000 (tipe: `fixed`).
- Anda bisa mengubah nominal diskon (`discount_amount`), minimal belanja (`min_order`), atau menambah kode promo baru!

---

## 🗄️ Struktur Database MySQL (`pandawa_db`)

Semua data telah termigrasi dan diisi data awal (*seeded*):
- **`categories`** : Kategori produk (*Makanan Siap Saji*, *Kopi & Minuman*, *Sembako*, *Buah & Snack*, *Kesehatan*).
- **`couriers`** : Data armada kurir resmi Pandawa (*Budi Santoso*, *Rian Hidayat*, *Deni Saputra*).
- **`products`** : Data katalog produk lengkap dengan harga, foto, rating, dan badge.
- **`orders`** : Rekap semua transaksi pemesanan (*Katalog*, *Delivery*, *Jastip*), metode bayar, dan status langkah pengantaran.
- **`order_items`** : Rincian item per pesanan.
- **`deliveries`** : Detail pengantaran paket antar-titik.
- **`jastips`** : Detail layanan titip belanja toko/pasar.
- **`vouchers`** : Kode promo aktif database.

---

## 🛠️ Perintah Berguna (Opsional)
Jika Anda ingin mereset ulang seluruh database ke kondisi awal bawaan pabrik:
```bash
cd D:\laragon\www\pandawa
php artisan migrate:fresh --seed
```
*(Perhatian: Perintah di atas akan mengosongkan database dan mengisinya kembali dengan data default awal).*
