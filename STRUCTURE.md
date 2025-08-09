# Struktur Proyek Pegadaianku

## 📁 Struktur Direktori

```
pegadaianku/
├── 📁 app/
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/
│   │   │   ├── 📄 DashboardController.php
│   │   │   ├── 📄 PawnTransactionController.php
│   │   │   ├── 📄 PaymentController.php
│   │   │   ├── 📄 CustomerController.php
│   │   │   ├── 📄 UserController.php
│   │   │   └── 📄 ReportController.php
│   │   └── 📁 Middleware/
│   │       └── 📄 RoleMiddleware.php
│   └── 📁 Models/
│       ├── 📄 User.php
│       ├── 📄 PawnTransaction.php
│       ├── 📄 Payment.php
│       └── 📄 Notification.php
├── 📁 database/
│   ├── 📁 migrations/
│   │   ├── 📄 0001_01_01_000000_create_users_table.php
│   │   ├── 📄 2024_01_01_000003_create_pawn_transactions_table.php
│   │   ├── 📄 2024_01_01_000004_create_payments_table.php
│   │   └── 📄 2024_01_01_000005_create_notifications_table.php
│   └── 📁 seeders/
│       ├── 📄 DatabaseSeeder.php
│       ├── 📄 UserSeeder.php
│       └── 📄 PawnTransactionSeeder.php
├── 📁 resources/
│   └── 📁 views/
│       ├── 📁 layouts/
│       │   └── 📄 app.blade.php
│       ├── 📁 auth/
│       │   └── 📄 login.blade.php
│       ├── 📁 dashboard/
│       │   ├── 📄 admin.blade.php
│       │   ├── 📄 officer.blade.php
│       │   └── 📄 customer.blade.php
│       ├── 📁 transactions/
│       │   ├── 📄 index.blade.php
│       │   ├── 📄 create.blade.php
│       │   ├── 📄 show.blade.php
│       │   └── 📄 edit.blade.php
│       └── 📁 payments/
│           ├── 📄 index.blade.php
│           ├── 📄 create.blade.php
│           ├── 📄 show.blade.php
│           └── 📄 receipt.blade.php
├── 📁 routes/
│   ├── 📄 web.php
│   └── 📄 auth.php
├── 📄 .env
├── 📄 composer.json
├── 📄 package.json
├── 📄 README.md
├── 📄 USAGE.md
├── 📄 setup.bat
├── 📄 run.bat
├── 📄 migrate.bat
├── 📄 artisan.bat
└── 📄 info.bat
```

## 🏗️ Arsitektur MVC

### Models (Data Layer)
- **User**: Manajemen pengguna dengan role system
- **PawnTransaction**: Transaksi gadai dengan perhitungan bunga
- **Payment**: Pembayaran dengan tracking bunga dan pokok
- **Notification**: Sistem notifikasi otomatis

### Views (Presentation Layer)
- **Layouts**: Template dasar dengan Tailwind CSS
- **Dashboard**: Dashboard khusus per role
- **Transactions**: CRUD transaksi gadai
- **Payments**: Manajemen pembayaran
- **Auth**: Halaman login dan registrasi

### Controllers (Business Logic)
- **DashboardController**: Logic dashboard multi-role
- **PawnTransactionController**: CRUD dan business logic transaksi
- **PaymentController**: Proses pembayaran dan kalkulasi
- **UserController**: Manajemen pengguna (Admin only)
- **CustomerController**: Manajemen nasabah

## 🔗 Relasi Database

```
Users (1) ----< PawnTransactions (M)
  |                    |
  |                    |
  |              (1) --< Payments (M)
  |                    |
  |                    |
  +----< Notifications (M)
```

### Relasi Detail:
- **User → PawnTransactions**: 1 user bisa punya banyak transaksi (sebagai customer)
- **User → HandledTransactions**: 1 petugas bisa handle banyak transaksi
- **PawnTransaction → Payments**: 1 transaksi bisa punya banyak pembayaran
- **User → Notifications**: 1 user bisa punya banyak notifikasi

## 🛡️ Security Layer

### Middleware
- **RoleMiddleware**: Kontrol akses berdasarkan role
- **Auth**: Laravel Breeze authentication
- **CSRF**: Protection untuk form submissions

### Authorization
```php
// Route protection
Route::middleware(['auth', 'role:admin,petugas'])->group(function () {
    // Admin & Officer routes
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin only routes
});
```

## 🎨 Frontend Architecture

### CSS Framework
- **Tailwind CSS**: Utility-first CSS framework
- **Font Awesome**: Icon library
- **Chart.js**: Interactive charts

### JavaScript
- **Alpine.js**: Lightweight reactive framework
- **Vanilla JS**: Custom calculations and interactions

### Components
- **Responsive Design**: Mobile-first approach
- **Interactive Forms**: Real-time calculations
- **Dynamic Charts**: Statistics visualization
- **Notification System**: Real-time alerts

## 📊 Data Flow

### Transaction Creation Flow:
```
User Input → Validation → Controller → Model → Database
                                    ↓
Notification Creation ← Business Logic ← Auto Calculations
```

### Payment Processing Flow:
```
Payment Input ��� Validation → Interest Calculation → Database Update
                                    ↓
Status Update ← Notification ← Transaction Update
```

## 🔧 Configuration Files

### Environment (.env)
- Database configuration
- Application settings
- Mail configuration
- Cache settings

### Composer (composer.json)
- PHP dependencies
- Laravel framework
- Development tools

### NPM (package.json)
- Frontend dependencies
- Build tools
- CSS frameworks

## 📱 Responsive Design

### Breakpoints:
- **Mobile**: < 640px
- **Tablet**: 640px - 1024px
- **Desktop**: > 1024px

### Grid System:
- **Dashboard**: Responsive card layout
- **Tables**: Horizontal scroll on mobile
- **Forms**: Stack on mobile, grid on desktop

## 🚀 Performance Optimizations

### Database:
- Proper indexing on foreign keys
- Eager loading for relationships
- Query optimization

### Frontend:
- CDN for external libraries
- Minified CSS/JS
- Optimized images

### Caching:
- Route caching
- View caching
- Configuration caching

## 🧪 Testing Structure

### Unit Tests:
- Model relationships
- Business logic calculations
- Helper functions

### Feature Tests:
- Authentication flow
- CRUD operations
- Role-based access

### Browser Tests:
- User interactions
- Form submissions
- Navigation flow

---

**Struktur ini dirancang untuk maintainability, scalability, dan best practices Laravel.**