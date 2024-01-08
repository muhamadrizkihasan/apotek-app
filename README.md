<h2>Apotek App</h2>

Website belajar laravel di sekolah

<h2 id="fitur">🧐 Fitur apa saja yang ada di website apotek ini?</h2>

-   Landing Page
    -   Halaman login
    -   Dashboard
    -   Data user
    -   Data obat
    -   Data stok
    -   Data pembelian
-   Authentication
    -   Login
    -   Logout
-   Multi User
    -   Admin
        -   Mengelola user (admin dan kasir)
        -   Mengelola data obat
        -   Mengelola data stok
        -   Mengelola data pembelian
    -   Kasir
        -   Menambah pembelian
-   Cari Data
-   Export PDF
-   Export Excel


<h2 id="installation">💻 Instalasi</h2>

1. Clone repository

```bash
git clone https://github.com/muhamadrizkihasan/apotek-app
cd apotek-app
composer install
cp .env.example .env
```

2. Konfigurasi database melalui file `.env`

```conf
DB_DATABASE=db_apotek
```

3. Migrasi and symlink

```bash
php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan db:seed
```

4. Jalankan website

```bash
php artisan serve
```

<h2 id="testing-account">👤 Akun default untuk testing</h2>

### Admin

-   Email : admin@gmail.com
-   Password : admin

### Kasir

-   Email : kasir@gmail.com
-   Password : kasir