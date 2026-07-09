<div align="center">
  <h1>RentFlow</h1>
  <p><strong>Sistem Manajemen Rental Barang</strong></p>
  <p>
    Aplikasi web berbasis Laravel untuk mengelola penyewaan barang seperti kendaraan, kamera, alat camping, sound system, dan lainnya.
  </p>
</div>

---

## Fitur

### Pelanggan
- Landing page & katalog unit rental
- Detail unit (foto, deskripsi, harga harian/mingguan/bulanan, ulasan)
- Pemesanan multi-unit dengan keranjang belanja
- Kalkulasi harga otomatis (weekend pricing, diskon mingguan/bulanan)
- Pembayaran via **Midtrans Snap** (QRIS, VA, kartu kredit, e-wallet)
- Invoice PDF otomatis
- Dashboard pemesanan, invoice, dan profil
- Rating & ulasan setelah sewa

### Admin
- Dashboard statistik (total booking, pendapatan, unit aktif, pelanggan)
- CRUD unit, kategori, pelanggan
- Manajemen pemesanan & status (pending → confirmed → active → completed/cancelled)
- Manajemen pembayaran & invoice
- Laporan pendapatan dengan ekspor CSV
- Kalender booking bulanan
- Moderasi ulasan
- Notifikasi

---

## Tech Stack

| Lapisan | Teknologi |
|---|---|
| **Backend** | Laravel 12, PHP ^8.2 |
| **Frontend** | Blade, Tailwind CSS 4, Alpine.js, Flatpickr |
| **Database** | SQLite (default) |
| **Payment** | Midtrans Snap API |
| **PDF** | barryvdh/laravel-dompdf |
| **Testing** | PHPUnit 11 (142 tests) |
| **Dev Tools** | Vite, Laravel Pint, Concurrently |

---

## Persyaratan Sistem

- PHP ^8.2 (extensions: bcmath, ctype, curl, dom, fileinfo, gd, iconv, mbstring, openssl, pdo, pdo_sqlite, tokenizer, xml)
- Composer 2.x
- Node.js 18+ & npm
- SQLite (built-in di PHP)

---

## Instalasi

### Quick Install (satu perintah)

```bash
composer setup
```

Perintah di atas akan menjalankan:
1. `composer install`
2. Copy `.env.example` ke `.env`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `npm install`
6. `npm run build`

### Manual

```bash
# 1. Clone repositori
git clone https://github.com/shabianay/rentflow.git
cd rentflow

# 2. Install dependensi PHP
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Setup database SQLite
touch database/database.sqlite
php artisan migrate
php artisan db:seed   # membuat admin@rentflow.test / password

# 5. Install & build frontend
npm install
npm run build

# 6. Konfigurasi Midtrans (edit .env)
# MIDTRANS_SERVER_KEY=your_sandbox_server_key
# MIDTRANS_CLIENT_KEY=your_sandbox_client_key
# MIDTRANS_IS_PRODUCTION=false
```

---

## Menjalankan Aplikasi

### Development

```bash
composer dev
```

Menjalankan secara bersamaan:
- `php artisan serve` → http://localhost:8000
- `php artisan queue:listen` (queue worker)
- `npm run dev` (Vite HMR)

### Production

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Testing

```bash
composer test
# atau
php artisan test
```

Menggunakan SQLite in-memory — **142 tests, 324 assertions**.

---

## Struktur Direktori

```
app/
├── Http/Controllers/       # 16 controller (fitur-based)
├── Http/Middleware/         # EnsureAdmin, EnsureCustomer
├── Jobs/                    # Email queue jobs
├── Mail/                    # Mailable classes
├── Models/                  # 9 Eloquent models
├── Services/                # PricingService, MidtransService, dll.
config/
├── midtrans.php             # Konfigurasi Midtrans
database/
├── migrations/              # 19 migration
├── seeders/                 # DatabaseSeeder
resources/
├── css/app.css              # Tailwind 4 + custom components
├── js/app.js                # Alpine.js + Flatpickr
├── views/                   # 50+ Blade template
routes/
└── web.php                  # Semua route (single file)
tests/
├── Feature/                 # 15 file test fitur
└── Unit/                    # 4 file test unit
```

---

## Alur Pembayaran

1. Booking dibuat → record `Payment` dengan status `pending`
2. `PaymentController@process` → call **Midtrans Snap API** → dapat `snap_token` & `snap_redirect_url`
3. Pelanggan bayar via popup Snap atau redirect
4. Midtrans kirim notifikasi ke `/payments/notification` (webhook, tanpa CSRF)
5. Redirect kembali ke `/payments/result/{status}/{booking}`
6. Jika sukses: booking → `active`, unit → `on_rent`, invoice dibuat otomatis

---

## Keamanan

- **CSRF Protection** — Semua route POST/PUT/PATCH/DELETE otomatis
- **Midtrans Webhook** — Route `/payments/notification` CSRF-exempt; validasi signature `SHA512(order_id + status_code + gross_amount + server_key)`
- **Role Middleware** — `EnsureAdmin` (role=admin), `EnsureCustomer` (role≠customer → 403)
- **XSS Prevention** — Blade `{{ }}` auto-escaping + DOMPurify
- **Password** — bcrypt (rounds=12)

---

## Lisensi

[MIT License](LICENSE)
