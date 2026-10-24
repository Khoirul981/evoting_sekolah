================================================================================
LAPORAN ASESMEN MANDIRI LSP - UNIT KOMPETENSI 3, 9, 10
DOKUMEN: SOFTWARE TOOLS DAN LINGKUNGAN PENGEMBANGAN
Sistem E-Voting Sekolah Berbasis Web (VOTINGYUK)
================================================================================

Tanggal Penyusunan: 18 Oktober 2026
Penyusun: [Nama Peserta Uji]
Skema Sertifikasi: Pengembang Aplikasi Mobile/Web

================================================================================
DAFTAR ISI
================================================================================
1. Sistem Operasi (Operating System)
2. Aplikasi Bahasa Pemrograman dan Versinya
3. Aplikasi Database Management System
4. Aplikasi Package Installer & Dependency Manager
5. Text Editor dan Extension yang Digunakan
6. Tools Pendukung Lainnya
7. Kesimpulan

================================================================================
1. SISTEM OPERASI (OPERATING SYSTEM)
================================================================================

1.1. Platform Pengembangan
---------------------------
- **Nama OS**: Windows 11 Professional
- **Arsitektur**: x64 (64-bit)
- **Versi**: Build 26300
- **Alasan Pemilihan**:
  * Kompatibilitas tinggi dengan tools development modern
  * Dukungan native untuk PHP, Composer, Node.js
  * Integrasi terminal PowerShell untuk automation
  * Ekosistem pengembangan Laravel yang matang

1.2. Platform Target Deployment
--------------------------------
- **Server OS**: Linux Ubuntu 22.04 LTS atau Windows Server
- **Web Server**: Apache 2.4 / Nginx 1.18+
- **Protokol**: HTTPS dengan SSL/TLS Certificate
- **Arsitektur**: Cloud-ready (dapat di-deploy ke shared hosting, VPS, atau cloud platform)

1.3. Kompatibilitas Client (Browser)
-------------------------------------
Aplikasi berjalan di browser modern:
- Google Chrome 120+ ✅
- Mozilla Firefox 121+ ✅
- Microsoft Edge 120+ ✅
- Safari 17+ (macOS/iOS) ✅
- Opera 106+ ✅

Responsif untuk perangkat:
- Desktop (≥1024px)
- Tablet (768px - 1023px)
- Mobile (320px - 767px)

================================================================================
2. APLIKASI BAHASA PEMROGRAMAN DAN VERSINYA
================================================================================

2.1. PHP (Server-Side Language)
--------------------------------
**Versi**: PHP 8.3.33
**Fungsi**: Backend logic, routing, authentication, database operations

**Fitur PHP 8.3 yang Digunakan**:
- Typed Properties & Union Types untuk type safety
- Named Arguments untuk readability
- Constructor Property Promotion (clean syntax)
- Match Expression (pengganti switch)
- Nullsafe Operator (?->)
- Array Spread Operator

**Konfigurasi PHP**:
```ini
PHP Version: 8.3.33
Zend Engine: v4.3.33
Memory Limit: 256M
Max Execution Time: 300s
Upload Max Filesize: 20M
Extensions Aktif:
  - PDO (Database Abstraction)
  - PDO_MySQL (MySQL Driver)
  - OpenSSL (Enkripsi)
  - Mbstring (Multibyte String)
  - Tokenizer (Parsing)
  - XML (XML Processing)
  - Ctype (Character Type Checking)
  - JSON (JSON Handling)
  - BCMath (Arbitrary Precision Math)
  - Fileinfo (File Type Detection)
  - GD (Image Processing)
```

**Instalasi**:
```bash
# Download PHP 8.3.33 dari php.net
# Extract ke C:\php83
# Set PATH environment variable
# Verifikasi instalasi:
php -v
php -m  # List extensions
```

2.2. JavaScript (Client-Side Language)
--------------------------------------
**Versi**: ECMAScript 2023 (ES14)
**Runtime Node.js**: v22.23.1
**Fungsi**: Interactivity, AJAX, form validation, real-time updates

**Fitur JavaScript yang Digunakan**:
- Arrow Functions
- Template Literals
- Async/Await
- Fetch API untuk AJAX
- DOM Manipulation
- Event Listeners
- Module System (ES6 Modules)

**NPM (Node Package Manager)**:
```bash
node -v   # v22.23.1
npm -v    # 10.9.8
```

2.3. SQL (Database Query Language)
----------------------------------
**Dialect**: MySQL 8.0
**Fungsi**: Data definition, manipulation, query, transaction

**Fitur SQL yang Digunakan**:
- DDL: CREATE, ALTER, DROP (schema management)
- DML: SELECT, INSERT, UPDATE, DELETE (data operations)
- Constraints: PRIMARY KEY, FOREIGN KEY, UNIQUE, NOT NULL
- Indexes untuk optimasi query
- Transactions (BEGIN, COMMIT, ROLLBACK)
- JOIN operations (INNER, LEFT)
- Aggregate Functions (COUNT, SUM, AVG)
- Subqueries

2.5. Templating Engine & Styling
---------------------------------

**A. Blade Templating Engine**
**Versi**: Laravel Blade 13.33.0 (mengikuti versi Laravel Framework)
**Kategori**: Server-side templating engine (bukan bahasa pemrograman)
**Fungsi**: Render HTML dinamis dengan sintaks ekspresif

**Fitur Blade yang Digunakan**:
- `@extends`, `@section`, `@yield` (layout inheritance)
- `@if`, `@foreach`, `@for` (control structures)
- `@auth`, `@guest` (authentication directives)
- `{{ }}` untuk escaped output
- `{!! !!}` untuk raw HTML
- `@csrf`, `@method` (security helpers)
- Components & Slots

**Catatan**: Blade bukan bahasa pemrograman independen, melainkan preprocessor yang mengkompilasi sintaks Blade menjadi PHP murni.

**B. Tailwind CSS (Styling Framework)**
**Versi**: Tailwind v4.0.0
**Fungsi**: Styling, layout, responsiveness, animations
**Versi**: Tailwind v4.0.0 (utility-first framework)
**Fungsi**: Styling, layout, responsiveness, animations

**Keterangan**: 
Project ini menggunakan Tailwind CSS v4 sebagai framework utama untuk styling.
Native CSS3 hanya digunakan untuk custom styling spesifik (seperti calendar picker dark mode).

================================================================================
3. APLIKASI DATABASE MANAGEMENT SYSTEM
================================================================================

3.1. MySQL Community Server
----------------------------
**Versi**: 8.0.30
**Vendor**: Oracle Corporation
**Fungsi**: Relational Database Management System (RDBMS)

**Spesifikasi**:
- Storage Engine: InnoDB (default, support transactions & foreign keys)
- Character Set: utf8mb4 (full Unicode support, termasuk emoji)
- Collation: utf8mb4_unicode_ci (case-insensitive sorting)
- Max Connections: 151 (default)
- Port: 3306 (default)

**Fitur MySQL yang Digunakan**:
- ACID Transactions (Atomicity, Consistency, Isolation, Durability)
- Foreign Key Constraints (referential integrity)
- Indexes (B-Tree) untuk performa query
- Auto Increment untuk Primary Key
- TIMESTAMP untuk audit trail
- ON DELETE CASCADE untuk cascading deletes

**Instalasi**:
```bash
# MySQL terinstall melalui Laragon (lokasi: C:\laragon\bin\mysql\mysql-8.0.30-winx64)
# Verifikasi
mysql --version
# Output: mysql Ver 8.0.30 for Win64 on x86_64 (MySQL Community Server - GPL)

# Create database
mysql -u root -p
CREATE DATABASE evoting_sekolah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'evoting_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON evoting_sekolah.* TO 'evoting_user'@'localhost';
FLUSH PRIVILEGES;
```

3.2. phpMyAdmin (Database GUI Tool)
-----------------------------------
**Versi**: 5.2.1
**Fungsi**: Web-based GUI untuk administrasi MySQL

**Fitur yang Digunakan**:
- Browse & search data
- Execute SQL queries
- Export database (SQL, CSV, Excel)
- Import data
- Table structure visualization
- User & privilege management

**Alternatif Tools**:
- MySQL Workbench 8.0 (desktop GUI)
- DBeaver Community Edition
- HeidiSQL

================================================================================
4. APLIKASI PACKAGE INSTALLER & DEPENDENCY MANAGER
================================================================================

4.1. Composer (PHP Dependency Manager)
---------------------------------------
**Versi**: 2.4.1
**Fungsi**: Mengelola library PHP, autoloading, dependencies Laravel

**Instalasi**:
```bash
# Download composer-setup.php dari getcomposer.org
# Install globally
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Verifikasi
composer --version  # Composer version 2.4.1
```

**Penggunaan dalam Project**:
```bash
# Install dependencies dari composer.json
composer install

# Update dependencies
composer update

# Add package baru
composer require vendor/package

# Autoload regeneration
composer dump-autoload
```

**Dependencies yang Dikelola**:
```json
{
  "require": {
    "php": "^8.3",
    "laravel/framework": "^13.17",
    "barryvdh/laravel-dompdf": "^3.1",
    "intervention/image": "^4.3",
    "maatwebsite/excel": "^4.0"
  },
  "require-dev": {
    "laravel/pint": "^1.27",
    "phpunit/phpunit": "^12.5.12"
  }
}
```

4.2. NPM (Node Package Manager)
--------------------------------
**Versi**: 10.9.8
**Fungsi**: Mengelola library JavaScript, build tools, asset compilation

**Instalasi**:
```bash
# Included with Node.js installation
# Verifikasi
npm -v  # 10.9.8
```

**Penggunaan dalam Project**:
```bash
# Install dependencies dari package.json
npm install

# Build assets untuk production
npm run build

# Development mode dengan hot reload
npm run dev
```

**Dependencies yang Dikelola**:
```json
{
  "devDependencies": {
    "@tailwindcss/vite": "^4.0.0",
    "tailwindcss": "^4.0.0",
    "vite": "^8.0.0",
    "laravel-vite-plugin": "^3.1"
  }
}
```

4.3. Vite (Frontend Build Tool)
--------------------------------
**Versi**: 8.3.1
**Fungsi**: Fast build tool, hot module replacement (HMR), asset bundling

**Konfigurasi** (`vite.config.js`):
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

**Fitur Vite yang Digunakan**:
- Lightning-fast cold start
- Hot Module Replacement (HMR)
- Optimized build output
- CSS preprocessing
- Asset optimization

================================================================================
5. TEXT EDITOR DAN EXTENSION YANG DIGUNAKAN
================================================================================

5.1. Visual Studio Code (Primary IDE)
--------------------------------------
**Versi**: 1.142.0
**Platform**: Windows x64
**Vendor**: Microsoft Corporation

**Alasan Pemilihan**:
- Lightweight namun powerful
- IntelliSense untuk PHP, JavaScript, Blade
- Integrated Terminal (PowerShell, Git Bash)
- Git integration built-in
- Debugging support
- Massive extension ecosystem
- Free & open source

5.2. Extension yang Digunakan
-------------------------------------

**A. PHP Development (Essential):**

1. **PHP Intelephense**
   - ID: `bmewburn.vscode-intelephense-client`
   - Fungsi: PHP code intelligence, autocomplete, go to definition, namespace import
   - Versi: v1.12.6
   - **Status**: Wajib untuk development PHP

**B. Laravel Development (Essential):**

2. **Laravel Blade Snippets**
   - ID: `onecentlin.laravel-blade`
   - Fungsi: Syntax highlighting dan snippets untuk Blade templates
   - Versi: v1.38.0
   - **Status**: Wajib untuk file .blade.php

3. **Laravel Artisan**
   - ID: `ryannaddy.laravel-artisan`
   - Fungsi: Run artisan commands dari VS Code command palette
   - Versi: v0.0.33
   - **Status**: Sangat membantu untuk run migrations, make commands, dll

**C. Frontend Development (Essential):**

4. **Tailwind CSS IntelliSense**
   - ID: `bradlc.vscode-tailwindcss`
   - Fungsi: Autocomplete class Tailwind, syntax highlighting, color preview
   - Versi: v0.12.4
   - **Status**: Wajib untuk Tailwind CSS v4

**D. Code Quality (Essential):**

5. **EditorConfig for VS Code**
   - ID: `EditorConfig.EditorConfig`
   - Fungsi: Maintain consistent coding styles (indentation, charset, line endings)
   - Versi: v0.16.4
   - **Status**: Project memiliki file `.editorconfig`

**Catatan Penting:**
- VS Code 1.142.0 sudah memiliki fitur built-in untuk: Git integration, path autocomplete, bracket colorization, dan terminal integration
- Extension seperti PHP Debug (Xdebug), ESLint, Prettier tidak digunakan karena tidak ada file konfigurasi di project
- Database management menggunakan phpMyAdmin (web-based) sehingga tidak perlu MySQL extension di VS Code
- Extension visual seperti icon theme bersifat opsional dan tidak mempengaruhi development

5.3. User Settings (settings.json)
-----------------------------------
```json
{
  "editor.fontSize": 14,
  "editor.tabSize": 4,
  "editor.insertSpaces": true,
  "editor.formatOnSave": true,
  "editor.wordWrap": "on",
  "files.autoSave": "afterDelay",
  "files.autoSaveDelay": 1000,
  "php.validate.executablePath": "C:/php83/php.exe",
  "intelephense.environment.phpVersion": "8.3.0",
  "blade.format.enable": true,
  "tailwindCSS.experimental.classRegex": [
    ["class: ['\"`]([^'\"`]*)['\"`]"]
  ]
}
```

5.4. Keyboard Shortcuts Produktif
----------------------------------
- `Ctrl+P`: Quick file open
- `Ctrl+Shift+P`: Command palette
- `Ctrl+B`: Toggle sidebar
- `Ctrl+\``: Toggle terminal
- `F12`: Go to definition
- `Shift+F12`: Find all references
- `Ctrl+Space`: Trigger IntelliSense
- `Alt+Shift+F`: Format document

================================================================================
6. TOOLS PENDUKUNG LAINNYA
================================================================================

6.1. Git (Version Control System)
----------------------------------
**Versi**: 2.55.0
**Fungsi**: Source code version control, collaboration, branching

**Instalasi**:
```bash
# Download Git for Windows dari git-scm.com
git --version  # git version 2.55.0.windows.2
```

**Konfigurasi Global**:
```bash
git config --global user.name "Nama Developer"
git config --global user.email "email@example.com"
git config --global core.autocrlf true
```

**Workflow dalam Project**:
```bash
# Initialize repository
git init

# Add remote
git remote add origin https://github.com/username/evoting_sekolah.git

# Commit workflow
git add .
git commit -m "feat: implement voter authentication"
git push origin main
```

6.2. Laragon (Local Development Environment)
---------------------------------------------
**Versi**: 6.0 Full (Build 220916)
**Komponen**:
- Apache 2.4.68-Win64
- PHP 8.3.33
- MySQL 8.0.30-winx64
- Node.js 22.23.1
- Redis (optional)

**Catatan**: Stack development dikelola melalui Laragon (lokasi: C:\laragon)

**Fitur Laragon**:
- Auto virtual host (evoting.test)
- SSL certificate generation
- Mail catcher (testing email)
- Quick create Laravel project
- One-click start/stop services

**Alternatif**:
- XAMPP 8.2.12
- WAMP Server
- Docker + Laravel Sail

6.3. Browser Developer Tools
-----------------------------
**Google Chrome DevTools**:
**Versi**: Google Chrome 155.0.8059.82
- Elements inspector
- Console (JavaScript debugging)
- Network monitor (AJAX requests)
- Application (storage, cookies, cache)
- Lighthouse (performance audit)
- Responsive design mode

6.4. Postman (API Testing)
---------------------------
**Versi**: 12.31.3
**Platform**: Windows x64 (Desktop platform version 12.31.3)
**Fungsi**: Testing API endpoints, HTTP requests
- Support GET, POST, PUT, DELETE
- Collections untuk organize requests
- Environment variables
- Response validation

6.5. Figma (UI Design - Optional)
----------------------------------
**Platform**: Web-based
**Fungsi**: Mockup design, prototyping
- Collaborative design
- Component library
- Export assets (PNG, SVG)
- Design handoff to developers

6.6. TablePlus (Database Client - Optional)
--------------------------------------------
**Versi**: 6.2.0
**Fungsi**: Modern database GUI
- Native performance
- Multi-tab interface
- SQL query builder
- Data export/import

================================================================================
7. KESIMPULAN
================================================================================

7.1. Ringkasan Stack Teknologi
-------------------------------

| Kategori                  | Tool/Software              | Versi        |
|---------------------------|----------------------------|--------------|
| Operating System          | Windows 11 Pro             | Build 26300  |
| Server-Side Language      | PHP                        | 8.3.33       |
| Client-Side Language      | JavaScript (Node.js)       | 22.23.1      |
| Database                  | MySQL Community Server     | 8.0.30       |
| Database GUI              | phpMyAdmin                 | 5.2.x        |
| PHP Package Manager       | Composer                   | 2.4.1        |
| Node Package Manager      | NPM                        | 10.9.8       |
| Build Tool                | Vite                       | 8.3.1        |
| CSS Framework             | Tailwind CSS               | 4.0.0        |
| Text Editor/IDE           | Visual Studio Code         | 1.142.0      |
| Version Control           | Git                        | 2.55.0       |
| Laravel Framework         | Laravel                    | 13.33.0      |
| Local Dev Environment     | Laragon                    | 6.0 (220916) |
| Web Server                | Apache                     | 2.4.68       |
| API Testing               | Postman                    | 12.31.3      |
| Browser                   | Google Chrome              | 155.0.8059   |

7.2. Alasan Pemilihan Tools
----------------------------

**A. PHP 8.3** dipilih karena:
- Performance improvement dibanding versi sebelumnya
- Type safety yang lebih baik (typed properties, union types)
- Security fixes terbaru
- Kompatibilitas penuh dengan Laravel 11

**B. Laravel 11** (framework) dipilih karena:
- Ecosystem matang (authentication, validation, ORM)
- Blade templating engine yang ekspresif
- Artisan CLI untuk automation
- Security features built-in (CSRF, SQL injection prevention)
- Dokumentasi lengkap

**C. MySQL 8.0** dipilih karena:
- Reliability tinggi untuk production
- ACID compliance
- Foreign key constraints untuk data integrity
- Performance optimization (indexing, query cache)
- Komunitas besar

**D. Tailwind CSS v4** dipilih karena:
- Utility-first approach (rapid development)
- Responsive design dengan breakpoints
- Customizable design system
- Small production bundle size
- No unused CSS (tree-shaking)

**E. Visual Studio Code** dipilih karena:
- Gratis dan open source
- Extension marketplace luas
- IntelliSense untuk PHP & JavaScript
- Integrated terminal & debugger
- Cross-platform

7.3. Kesesuaian dengan KUK LSP
-------------------------------

Dokumentasi ini memenuhi kriteria:

**Unit 3 (J.620100.001.01): Menganalisis tools**
✅ 1.1. Kebutuhan tools perangkat lunak diidentifikasi
✅ 1.2. Kemungkinan penggunaan tools diidentifikasi
✅ 2.1. Tools pengembangan dipilih sesuai kebutuhan
✅ 2.2. Penggunaan tools diuji coba (installed & configured)
✅ 2.3. Risiko pengembangan dengan tools diidentifikasi

**Unit 9 (J.620100.011.01): Melakukan instalasi software tools**
✅ 1.1. Platform yang akan digunakan diidentifikasi (Windows)
✅ 1.2. Tools bahasa pemrograman dipilih (PHP, JavaScript)
✅ 2.1. Tools ter-install sesuai prosedur
✅ 2.2. Tools bisa dijalankan di lingkungan pengembangan
✅ 3.1. Script sederhana dibuat (Laravel routes, Blade views)
✅ 3.2. Script dijalankan dengan benar

**Unit 10 (J.620100.012.01): Melakukan pengaturan software tools**
✅ 1.1. Target hasil konfigurasi ditentukan
✅ 1.2. Tools tetap bisa digunakan setelah dikonfigurasikan
✅ 2.1. Fitur dasar untuk mendukung pembuatan program diidentifikasi
✅ 2.2. Fitur dasar tools dikuasai

7.4. Verifikasi Instalasi
--------------------------

Untuk memverifikasi semua tools terinstall dengan benar:

```bash
# Check PHP
php -v
# Output: PHP 8.3.33 (cli) (built: Jul 28 2026 18:10:28) (ZTS Visual C++ 2019 x64)

# Check Composer
composer --version
# Output: Composer version 2.4.1 2022-08-20 11:44:50

# Check Node.js & NPM
node -v
# Output: v22.23.1

npm -v
# Output: 10.9.8

# Check MySQL
mysql --version
# Output: mysql Ver 8.0.30 for Win64 on x86_64 (MySQL Community Server - GPL)

# Check Git
git --version
# Output: git version 2.55.0.windows.2

# Check Laravel Artisan
php artisan --version
# Output: Laravel Framework 13.33.0

# Check VS Code
code --version
# Output: 1.142.0
```

================================================================================
LAMPIRAN A: SCREENSHOT INSTALASI TOOLS
================================================================================

[Tempat untuk menambahkan screenshot dari:]
- PHP Info (php -v)
- Composer version
- MySQL Server status
- VS Code dengan extension list
- Laragon control panel
- Git config --list

================================================================================
LAMPIRAN B: FILE KONFIGURASI
================================================================================

**composer.json** (Root Project):
```json
{
    "name": "laravel/laravel",
    "type": "project",
    "require": {
        "php": "^8.3",
        "laravel/framework": "^13.17",
        "laravel/tinker": "^3.0",
        "barryvdh/laravel-dompdf": "^3.1",
        "intervention/image": "^4.3",
        "maatwebsite/excel": "^4.0"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.27",
        "phpunit/phpunit": "^12.5.12"
    }
}
```

**package.json**:
```json
{
    "private": true,
    "type": "module",
    "devDependencies": {
        "@tailwindcss/vite": "^4.0.0",
        "tailwindcss": "^4.0.0",
        "vite": "^8.0.0",
        "laravel-vite-plugin": "^3.1"
    }
}
```

**php.ini** (Key Settings):
```ini
max_execution_time = 300
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 20M
extension=pdo_mysql
extension=openssl
extension=mbstring
extension=gd
```

================================================================================
PENUTUP
================================================================================

Dokumen ini disusun untuk memenuhi persyaratan asesmen LSP pada Unit Kompetensi:
- J.620100.001.01 (Menganalisis tools)
- J.620100.011.01 (Melakukan instalasi software tools pemrograman)
- J.620100.012.01 (Melakukan pengaturan software tools pemrograman)

Semua tools yang tercantum telah terinstall, terkonfigurasi, dan diuji coba 
untuk pengembangan Sistem E-Voting Sekolah (VOTINGYUK).

---
Disusun oleh: [Nama Peserta Uji]
Tanggal: 18 Oktober 2026
Tempat: [Nama Sekolah/Institusi]

================================================================================
