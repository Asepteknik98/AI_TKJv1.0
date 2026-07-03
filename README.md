# AI Assistant TKJ - SMK Jaya Buana

Simple PHP native project to help teachers and students of TKJ.

Setup:

1. Put project in `C:/xampp/htdocs/AI_TKJ`.
2. Create database and run `database/schema.sql` in MySQL.
3. Edit `config/config.php` to set DB credentials and `openai_api_key`.
4. Run `php database/seed_admin.php` to create initial admin user.
5. Open `http://localhost/AI_TKJ` in browser.

Notes:
- Uses OpenAI Chat Completions (set your API key in `config/config.php`).
- Simple OOP for Database and AI helper. Expand as needed.

# 🤖 AI Assistant TKJ - SMK Jaya Buana

> AI Assistant TKJ adalah aplikasi berbasis web yang dikembangkan menggunakan **PHP Native** dan **MySQL** untuk membantu proses pembelajaran di Jurusan **Teknik Komputer dan Jaringan (TKJ)** SMK Jaya Buana.

Website ini dirancang agar dapat digunakan oleh guru maupun siswa sebagai media pembelajaran interaktif, pusat materi, serta asisten AI yang dapat menjawab pertanyaan seputar jaringan komputer dan teknologi informasi.

---

# 📸 Preview

Halaman yang tersedia:

- Login
- Dashboard
- AI Chat Assistant
- Materi Pembelajaran
- AI Pembuat Soal
- Riwayat Chat
- Manajemen Pengguna
- Profil Pengguna

---

# 🚀 Fitur

## 👨‍🏫 Guru

- Login
- Dashboard
- Mengelola Materi
- Upload PDF Materi
- Menggunakan AI Chat
- Membuat Soal dengan AI
- Melihat Riwayat Chat
- Mengubah Profil
- Mengubah Password

---

## 👨‍🎓 Siswa

- Login
- Dashboard
- Membaca Materi
- Download Materi
- Bertanya kepada AI
- Melihat Riwayat Chat
- Mengubah Profil

---

## 👨‍💼 Admin

- Login
- Dashboard
- Kelola Guru
- Kelola Siswa
- Kelola Materi
- Kelola Chat AI
- Kelola Riwayat
- Kelola Hak Akses
- Backup Database (Opsional)

---

# 🤖 AI Assistant

Website memiliki fitur AI yang dapat membantu pengguna menjawab pertanyaan seperti:

- Apa itu IP Address?
- Apa fungsi Router?
- Jelaskan DHCP.
- Jelaskan DNS.
- Apa itu VLAN?
- Apa itu Mikrotik?
- Apa fungsi Switch?
- Apa itu Routing?
- Apa itu NAT?
- Apa itu Firewall?

AI juga dapat membantu membuat:

- Ringkasan Materi
- Soal Pilihan Ganda
- Soal Essay
- Pembahasan Soal
- Kesimpulan Materi

---

# 🖥️ Teknologi

- PHP Native
- MySQL
- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- AJAX
- Font Awesome
- SweetAlert2
- Chart.js
- DataTables
- OpenAI API *(Opsional)*
- XAMPP

---

# 📁 Struktur Folder

```
AI_TKJ/
│
├── admin/
├── guru/
├── siswa/
├── assets/
│   ├── css/
│   ├── js/
│   ├── img/
│   └── uploads/
│
├── config/
│   ├── config.php
│   ├── Database.php
│   └── AI.php
│
├── database/
│   ├── schema.sql
│   ├── seed_admin.php
│   └── sample_data.sql
│
├── includes/
├── templates/
├── vendor/
├── login.php
├── logout.php
├── dashboard.php
├── index.php
└── README.md
```

---

# ⚙️ Persyaratan Sistem

### Software

- Windows 10 / Windows 11
- XAMPP 8.x
- PHP 8.1+
- MySQL 8+
- Apache
- Composer *(Opsional)*

---

# 📦 Cara Instalasi

## 1. Clone Repository

```bash
git clone https://github.com/username/AI_TKJ.git
```

atau download ZIP.

---

## 2. Pindahkan Project

Salin folder project ke:

```
C:\xampp\htdocs\
```

Sehingga menjadi:

```
C:\xampp\htdocs\AI_TKJ
```

---

## 3. Jalankan XAMPP

Aktifkan:

- Apache
- MySQL

---

## 4. Membuat Database

Buka phpMyAdmin:

```
http://localhost/phpmyadmin
```

Buat database baru:

```
ai_tkj
```

Import file:

```
database/schema.sql
```

Jika tersedia, import juga:

```
database/sample_data.sql
```

---

## 5. Konfigurasi Database

Buka file:

```
config/config.php
```

Contoh konfigurasi:

```php
<?php

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "ai_tkj";

$openai_api_key = "";
```

Jika tidak menggunakan OpenAI, biarkan:

```php
$openai_api_key = "";
```

Website tetap dapat berjalan menggunakan data lokal.

---

## 6. Membuat Admin

Jalankan:

```bash
php database/seed_admin.php
```

atau melalui browser sesuai implementasi proyek.

---

## 7. Jalankan Website

```
http://localhost/AI_TKJ
```

---

# 🔑 Default Login

## Admin

Username

```
admin
```

Password

```
admin123
```

**Segera ubah password setelah login pertama.**

---

# 🗄️ Database

Tabel utama:

- users
- guru
- siswa
- materi
- chat_ai
- history_chat
- soal
- kategori
- settings

---

# 📱 Responsive

Website dapat digunakan pada:

- Desktop
- Laptop
- Tablet
- Android
- iPhone

---

# 🔒 Keamanan

- Password Hash
- Session Login
- SQL Injection Protection (Prepared Statement)
- Validasi Input
- Upload File Validation
- Role Based Access

---

# 📈 Pengembangan Selanjutnya

Beberapa fitur yang dapat ditambahkan:

- Gemini API
- OpenAI GPT
- OCR
- Voice Assistant
- Face Recognition
- QR Code Absensi
- Export PDF
- Export Excel
- AI Analisis Nilai
- AI Rekomendasi Materi
- Bank Soal AI
- Dashboard Statistik

---

# 🎯 Tujuan Proyek

Membantu guru dan siswa Jurusan Teknik Komputer dan Jaringan dalam:

- Pembelajaran
- Konsultasi Materi
- Pembuatan Soal
- Penyimpanan Materi
- Penggunaan AI dalam pendidikan

---

# 👨‍💻 Developer

**SMK Jaya Buana**

Jurusan Teknik Komputer dan Jaringan (TKJ)

Developed with ❤️ using PHP Native & MySQL.

---

# 📄 Lisensi

Project ini dibuat untuk tujuan pendidikan dan pengembangan di lingkungan SMK Jaya Buana.

Silakan gunakan, modifikasi, dan kembangkan sesuai kebutuhan sekolah dengan tetap mencantumkan atribusi kepada pengembang jika didistribusikan kembali.