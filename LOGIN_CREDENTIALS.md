# 🔐 Login Credentials - Sistem POS

## Dual Login System

Sistem ini menggunakan **2 halaman login terpisah**:

### 1️⃣ Member Login
**URL**: http://127.0.0.1:8000/member/login

**Test Credentials**:
- Untuk member yang sudah terdaftar
- Status member harus: `active`
- Role: `member`

---

### 2️⃣ Staff Login (Admin & Kasir)
**URL**: http://127.0.0.1:8000/staff/login

**Test Credentials**:

#### 👨‍💼 Admin
```
Email: admin@gmail.com
Password: 12345
Role: admin
```

#### 💰 Kasir
```
Email: kasir@gmail.com
Password: 12345
Role: kasir
```

> **⚠️ PENTING**: Password default untuk semua akun adalah **12345**

---

## 🎨 Desain Baru

### Warna Theme
- **Primary**: Cream (#DDD0C8)
- **Secondary**: Dark Gray (#323232)
- **Background**: Light Cream (#F5F1EE)
- **Accent**: Accent Cream (#C8BAB0)

### Fitur Login
- ✅ Validasi role otomatis
- ✅ Member tidak bisa login di Staff Portal
- ✅ Staff (Admin/Kasir) tidak bisa login di Member Portal
- ✅ Redirect otomatis sesuai role
- ✅ Design elegant & premium

---

## 🚀 Quick Start

1. **Jalankan Laravel Server**:
   ```bash
   php artisan serve
   ```

2. **Clear Cache** (jika ada masalah):
   ```bash
   php artisan cache:clear
   php artisan view:clear
   php artisan config:clear
   ```

3. **Seed Database** (jika belum ada user):
   ```bash
   php artisan db:seed --class=AdminGmailSeeder
   php artisan db:seed --class=KasirGmailSeeder
   ```

4. **Test Login**:
   - Admin: http://127.0.0.1:8000/staff/login → login dengan admin@gmail.com
   - Kasir: http://127.0.0.1:8000/staff/login → login dengan kasir@gmail.com

---

## ⚠️ Troubleshooting

### Error: "Akun ini bukan member"
- **Penyebab**: Anda login sebagai Admin/Kasir di Member Portal
- **Solusi**: Gunakan Staff Login → http://127.0.0.1:8000/staff/login

### Error: "Akun ini bukan staff"
- **Penyebab**: Anda login sebagai Member di Staff Portal
- **Solusi**: Gunakan Member Login → http://127.0.0.1:8000/member/login

### Desain masih lama (purple/indigo)
- **Solusi**: 
  1. Clear browser cache (Ctrl + Shift + Del)
  2. Hard refresh (Ctrl + F5)
  3. Run: `php artisan view:clear`

---

## 📱 URL Lengkap

| Page | URL | Untuk |
|------|-----|-------|
| Homepage | http://127.0.0.1:8000 | Public |
| Member Login | http://127.0.0.1:8000/member/login | Member |
| Staff Login | http://127.0.0.1:8000/staff/login | Admin & Kasir |
| Register | http://127.0.0.1:8000/register | Daftar Member Baru |
| Admin Dashboard | http://127.0.0.1:8000/admin/dashboard | Admin |
| Kasir Dashboard | http://127.0.0.1:8000/kasir/dashboard | Kasir |
| Member Dashboard | http://127.0.0.1:8000/member/dashboard | Member |

---

## 🎯 Redirect Flow

```
Staff Login (admin@gmail.com) → Admin Dashboard
Staff Login (kasir@gmail.com) → Kasir Dashboard
Member Login (member@example.com) → Member Dashboard
```

---

Dibuat: Oktober 2025
Update Terakhir: 29 Oktober 2025
