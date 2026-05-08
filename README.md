# Lanina Patisserie - E-Commerce System

Sistem e-commerce berbasis Laravel 11 untuk toko kue/patisserie dengan fitur pre-order, manajemen promosi, dan sistem rekomendasi produk.

## Fitur Utama

### 🛒 Pre-Order System
- Shopping cart dengan Livewire
- Multiple payment methods via Midtrans
- Order tracking (unpaid → processing → shipping → completed)
- Address management untuk customer

### 🎁 Promo Management
- Bundle promosi (multiple products)
- Status otomatis: active, scheduled, inactive
- Perhitungan diskon otomatis
- Stok management

### 📊 Sistem Rekomendasi Promosi
Lanina Patisserie menggunakan **Association Rules Mining** untuk menganalisis pola pembelian dan merekomendasikan kombinasi produk untuk bundle promosi.

**Metrik yang digunakan**:
- **Support**: Frekuensi co-occurrence produk dalam transaksi
- **Confidence**: Probabilitas kondisional pembelian
- **Lift**: Rasio co-occurrence vs random

**Dokumentasi lengkap**: Lihat [RECOMMENDATION_SYSTEM.md](RECOMMENDATION_SYSTEM.md)

**Akses**: `/admin/promo/rekomendasi` (Admin only)

### 📈 Dashboard Admin
- Total pendapatan & statistik pesanan
- Grafik penjualan (per hari/bulan)
- Dynamic filtering
- Tabel pesanan terbaru

### 📅 Jadwal Management
- Kalender ketersediaan tanggal
- Slot management untuk kapasitas produksi

### 👥 User Management
- Role-based access (Admin & Customer)
- Profile management
- Multiple address per user

## Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Tailwind CSS 4.2 + Livewire 4.2
- **Database**: MySQL/PostgreSQL
- **Payment**: Midtrans
- **Media**: Spatie Media Library
- **Permissions**: Spatie Laravel Permission

## Installation

### Requirements
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL/PostgreSQL

### Setup

1. Clone repository
```bash
git clone <repository-url>
cd lanina-patisserie
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lanina_patisserie
DB_USERNAME=root
DB_PASSWORD=
```

5. Configure Midtrans di `.env`
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

6. Run migrations
```bash
php artisan migrate
```

7. Create storage link
```bash
php artisan storage:link
```

8. Build assets
```bash
npm run dev  # development
npm run build  # production
```

9. Run server
```bash
php artisan serve
```

10. Access application
```
http://localhost:8000
```

## Default Credentials

Setelah seeding (jika ada):
- **Admin**: admin@lanina.com / password
- **Customer**: customer@lanina.com / password

## Project Structure

```
lanina-patisserie/
├── app/
│   ├── Http/Controllers/     # 16 controllers
│   ├── Livewire/             # 4 Livewire components
│   ├── Models/               # 8 models
│   └── Services/             # Business logic (future)
├── database/
│   └── migrations/           # 19 migrations
├── resources/
│   └── views/
│       ├── layouts/          # Admin & customer layouts
│       ├── pages/            # 16 page directories
│       └── livewire/         # Livewire views
├── routes/
│   └── web.php               # 122 lines routing
└── public/                   # Static assets
```

## Documentation

- [Sistem Rekomendasi](RECOMMENDATION_SYSTEM.md) - Association Rules Mining
- [API Documentation](#) - Coming soon
- [Deployment Guide](#) - Coming soon

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## License

This project is licensed under the MIT License.

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
