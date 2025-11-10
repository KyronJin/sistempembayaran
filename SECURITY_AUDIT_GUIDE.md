# 🔒 Panduan Audit & Keamanan Transaksi

## ⚠️ PENTING: Pencegahan Fraud & Korupsi

Sistem ini telah dilengkapi dengan mekanisme keamanan berlapis untuk mencegah kecurangan dan menjamin akuntabilitas setiap transaksi.

---

## 📋 Fitur Keamanan yang Telah Diimplementasikan

### 1. **Audit Trail Lengkap**
Setiap transaksi menyimpan informasi berikut:
- ✅ **Nama Kasir** (`cashier_name`) - Nama lengkap kasir yang melakukan transaksi
- ✅ **ID Kasir** (`cashier_id`) - ID unik kasir di sistem
- ✅ **IP Address** (`ip_address`) - Alamat IP perangkat kasir saat transaksi
- ✅ **User Agent** (`user_agent`) - Informasi browser/device yang digunakan
- ✅ **Timestamp Lengkap** (`transaction_date`) - Tanggal dan waktu hingga detik
- ✅ **Catatan Transaksi** (`transaction_notes`) - Catatan khusus jika ada

### 2. **Validasi Transaksi Ketat**
- ❌ **Transaksi Rp 0 DIBLOKIR** - Sistem otomatis menolak transaksi dengan total Rp 0
- ❌ **Harga 0 DIBLOKIR** - Item dengan harga 0 tidak dapat diproses
- ❌ **Total Negatif DIBLOKIR** - Sistem mencegah total transaksi negatif
- ✅ **Validasi Payment** - Pembayaran harus >= total transaksi

### 3. **Logging Aktivitas Mencurigakan**
Sistem mencatat ke log file (`storage/logs/laravel.log`) untuk aktivitas berikut:

#### 🚨 Level CRITICAL (Sangat Serius)
```
- Percobaan transaksi dengan total Rp 0
- Transaksi dengan total negatif
- Split payment dengan perhitungan tidak valid
```

#### ⚠️ Level WARNING (Perlu Perhatian)
```
- Void transaksi (pembatalan)
- Percobaan transaksi dengan item kosong
```

#### ℹ️ Level INFO (Informasi)
```
- Transaksi berhasil diproses
- Split payment berhasil
```

---

## 🔍 Cara Memonitor Transaksi Mencurigakan

### 1. **Melalui View Transaction History**
- Buka halaman **Riwayat Transaksi** di dashboard kasir
- Transaksi dengan total Rp 0 akan ditandai dengan:
  - ⚠️ Background merah terang
  - Badge "INVALID" berwarna merah
  - Border merah di sisi kiri

### 2. **Melalui Log File**
Buka file: `storage/logs/laravel.log`

**Contoh log aktivitas mencurigakan:**
```
[2025-11-07 03:20:00] local.WARNING: SECURITY ALERT: Percobaan transaksi Rp 0 terdeteksi
{
    "alert_type": "ZERO_TRANSACTION_ATTEMPT",
    "cashier_id": 5,
    "cashier_name": "John Doe",
    "cashier_email": "john@example.com",
    "ip_address": "192.168.1.100",
    "user_agent": "Mozilla/5.0...",
    "items_attempted": [...],
    "timestamp": "2025-11-07 03:20:00",
    "severity": "HIGH"
}
```

### 3. **Melalui Database Query**
```sql
-- Cek transaksi dengan total Rp 0
SELECT 
    transaction_code,
    cashier_name,
    cashier_id,
    total,
    payment_method,
    ip_address,
    transaction_date,
    created_at
FROM transactions 
WHERE total = 0 
ORDER BY created_at DESC;

-- Cek transaksi hari ini per kasir
SELECT 
    cashier_name,
    cashier_id,
    COUNT(*) as total_transaksi,
    SUM(total) as total_penjualan,
    MIN(total) as transaksi_terkecil,
    MAX(total) as transaksi_terbesar
FROM transactions 
WHERE DATE(transaction_date) = CURDATE()
GROUP BY cashier_id, cashier_name
ORDER BY total_penjualan DESC;
```

---

## 👥 Tanggung Jawab & Akuntabilitas

### 🎯 Kasir
**Bertanggung jawab atas:**
- Semua transaksi yang diproses menggunakan akun mereka
- Menjaga kerahasiaan password login
- Melaporkan jika ada aktivitas mencurigakan

**Data yang tercatat:**
- Nama lengkap di setiap transaksi
- IP address perangkat yang digunakan
- Waktu transaksi hingga detik

### 👨‍💼 Supervisor/Manager
**Bertanggung jawab untuk:**
- Mereview log aktivitas mencurigakan setiap hari
- Melakukan verifikasi transaksi yang ditandai
- Menindaklanjuti kasir yang terlibat aktivitas mencurigakan
- Melakukan audit berkala

**Tools yang tersedia:**
- Kolom `verified_by` dan `verified_at` untuk menandai transaksi yang sudah diverifikasi
- Akses ke log file lengkap
- Dashboard dengan filter berdasarkan kasir

---

## 📊 Rekomendasi Audit Rutin

### Harian
- [ ] Review transaksi dengan total Rp 0 (jika ada)
- [ ] Cek log file untuk alert CRITICAL
- [ ] Review transaksi void/batal

### Mingguan
- [ ] Bandingkan total penjualan per kasir
- [ ] Review transaksi dengan nilai anomali (terlalu kecil/besar)
- [ ] Cek pattern pembayaran yang tidak wajar

### Bulanan
- [ ] Full audit semua transaksi
- [ ] Review kinerja kasir
- [ ] Export data untuk analisis

---

## 🛡️ Proteksi yang Telah Diterapkan

### 1. **Validasi di Frontend**
- Form tidak dapat submit dengan nilai 0
- Validasi JavaScript sebelum kirim data

### 2. **Validasi di Backend**
- Double-check di controller
- Database constraints
- Business logic validation

### 3. **Database Level**
- Foreign key untuk cashier_id
- Timestamping otomatis
- Audit columns

### 4. **Logging**
- Application logs (Laravel)
- Database transactions
- IP tracking

---

## 🚨 Tindakan Jika Ditemukan Kecurangan

1. **Identifikasi** - Cek log dan data transaksi
2. **Investigasi** - Review semua transaksi kasir terkait dalam periode tertentu
3. **Dokumentasi** - Screenshot log, export data transaksi
4. **Tindakan** - Sesuai kebijakan perusahaan (suspend akun, peringatan, dll)
5. **Pencegahan** - Update policy dan training

---

## 📞 Kontak

Jika menemukan bug atau celah keamanan:
1. Jangan eksploitasi
2. Segera lapor ke IT/Developer
3. Dokumentasikan cara reproduksi

---

## 🔐 Kesimpulan

Dengan sistem audit trail yang komprehensif ini:
- ✅ Setiap transaksi dapat dilacak ke kasir yang bertanggung jawab
- ✅ Aktivitas mencurigakan tercatat dan dapat di-review
- ✅ Sistem otomatis menolak transaksi invalid
- ✅ Log file menyimpan bukti untuk investigasi
- ✅ Akuntabilitas jelas untuk mencegah fraud

**Sistem ini dirancang untuk transparansi dan akuntabilitas penuh.**

---

*Terakhir diperbarui: 7 November 2025*
