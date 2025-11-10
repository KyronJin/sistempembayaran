# 📱 Panduan QR Code Member

## ✅ Fitur QR Code Otomatis

Setiap member yang daftar akan **OTOMATIS** mendapatkan QR Code unik untuk memudahkan transaksi di kasir!

---

## 🚀 Cara Kerja QR Code Member

### **1. Auto-Generate Saat Registrasi**

Ketika member registrasi di: `http://127.0.0.1:8000/register`

**Proses Otomatis**:
1. User mengisi form registrasi (nama, email, password, phone, address)
2. Sistem membuat akun User dengan role='member'
3. Sistem membuat profil Member dengan:
   - Member Code (format: MBR00001, MBR00002, dst)
   - Status: pending (menunggu approval admin)
   - Points: 0
4. **Sistem OTOMATIS generate QR Code** berisi:
   ```json
   {
     "type": "member",
     "id": 9,
     "member_code": "MBR00009",
     "name": "Test Member"
   }
   ```
5. QR Code disimpan sebagai **SVG** di:
   ```
   storage/app/public/qrcodes/members/member_9_1699200000.svg
   ```
6. Path QR Code disimpan di database kolom `members.qr_code`

---

## 📊 Data dalam QR Code

### **Format JSON**:
```json
{
  "type": "member",           // Identifier bahwa ini QR member
  "id": 9,                    // ID member di database
  "member_code": "MBR00009",  // Kode member unik
  "name": "Test Member"       // Nama member
}
```

### **Kegunaan**:
- Kasir scan QR Code → otomatis load data member
- Tidak perlu ketik manual nama/kode member
- Mencegah kesalahan input
- Mempercepat proses transaksi (5-10 detik)

---

## 🎯 Cara Menggunakan di Kasir

### **Opsi 1: Scan QR Code Member**

1. **Kasir buka POS**: http://127.0.0.1:8000/kasir/pos

2. **Klik tombol "Scan QR Member"**
   - Ada di sidebar kanan, bagian atas cart
   - Icon: 📷 kamera + text "Scan QR Member"

3. **Izinkan Akses Kamera**
   - Browser minta permission
   - Klik "Allow"

4. **Member tunjukkan QR Code**
   - Dari kartu member fisik (print)
   - Atau dari HP (buka halaman member dashboard → QR Code saya)

5. **Kamera Deteksi QR Code**
   - Otomatis scan dalam 1-2 detik
   - Data member muncul di sidebar:
     - Nama member
     - Member code
     - Total poin
     - Status member

6. **Mulai Transaksi**
   - Scan produk atau input manual
   - Member otomatis dapat diskon
   - Member otomatis dapat poin reward

---

### **Opsi 2: Search by Nomor HP**

Jika QR Code tidak bisa di-scan (rusak/lupa bawa):

1. **Klik tombol "Pilih Member"**
   - Di sidebar kanan POS

2. **Modal Member Search terbuka**

3. **Ketik Nomor HP member**
   - Format: 081234567890
   - Atau ketik nama/member code

4. **Klik member yang muncul**
   - Otomatis load data member
   - Sama seperti hasil scan QR

---

### **Opsi 3: Input Member Code Manual**

1. Modal member search
2. Ketik member code (contoh: MBR00009)
3. Select member dari hasil search

---

## 💳 Member Card (Physical)

### **Cara Print QR Code Member**:

1. **Login sebagai Admin**
   - Email: admin@gmail.com
   - Password: 12345

2. **Buka Kelola Member**
   - Menu: Admin → Kelola Member
   - URL: http://127.0.0.1:8000/admin/members

3. **Klik tombol "Detail" pada member**
   - Icon mata (👁️) orange

4. **Modal Detail Member terbuka**
   - Lihat Member Code
   - *Note: Fitur print QR akan ditambahkan*

### **Alternatif: Member Lihat Sendiri**

1. **Member login**
   - URL: http://127.0.0.1:8000/member/login
   - Email: member@test.com
   - Password: 12345

2. **Buka Dashboard Member**

3. **Klik menu "QR Code Saya"**
   - URL: http://127.0.0.1:8000/member/qr-code
   - QR Code besar tampil di layar

4. **Screenshot atau Print**
   - Screenshot QR Code
   - Atau klik tombol print (Ctrl+P)

### **Rekomendasi Physical Card**:

- **Bahan**: PVC card (seperti kartu ATM)
- **Ukuran**: CR80 standard (85.6mm x 53.98mm)
- **Design**:
  ```
  ┌────────────────────────┐
  │   LOGO TOKO            │
  │                        │
  │   [QR CODE 3x3cm]      │
  │                        │
  │   MBR00009             │
  │   Test Member          │
  │   Member sejak 2025    │
  └────────────────────────┘
  ```
- **Print**: Printer ID card (FARGO, Zebra)
- **Harga**: Rp 3.000 - 5.000/card
- **Atau**: Print stiker glossy (Rp 500/lembar), tempel di kartu

---

## 🔧 Troubleshooting

### ❌ **QR Code Tidak Generate Saat Registrasi**

**Cek**:
1. Folder `storage/app/public/qrcodes/members/` ada?
   ```bash
   cd storage/app/public
   mkdir -p qrcodes/members
   chmod 775 qrcodes/members
   ```

2. Symlink storage sudah dibuat?
   ```bash
   php artisan storage:link
   ```

3. Library SimpleSoftwareIO/simple-qrcode terinstall?
   ```bash
   composer require simplesoftwareio/simple-qrcode
   ```

---

### ❌ **QR Code Tidak Terbaca Saat Di-scan**

**Penyebab**:
- QR Code terlalu kecil (minimal 3x3 cm)
- Print quality rendah (blur/pixelated)
- Kamera webcam tidak fokus
- Pencahayaan kurang

**Solusi**:
- Print QR Code ukuran lebih besar (5x5 cm)
- Gunakan printer laser/inkjet berkualitas
- Ensure kamera auto-focus aktif
- Scan di tempat terang

---

### ❌ **Scan QR Tapi Data Member Tidak Muncul**

**Cek di Browser Console** (F12 → Console):

1. **Error: "Member not found"**
   - Member ID di QR tidak ada di database
   - Re-generate QR Code member tersebut

2. **Error: "Member status not active"**
   - Status member masih pending/suspended
   - Admin harus approve member dulu

3. **No error but no data**
   - Check JavaScript function `onScanSuccess()`
   - Pastikan endpoint API member work

---

## 🎨 Customisasi QR Code

### **Ubah Ukuran QR Code**:

Edit file: `app/Services/QRCodeService.php`

```php
QrCode::format('svg')
    ->size(500)  // Ubah dari 300 ke 500 (lebih besar)
    ->generate($data, $path);
```

### **Ubah Format (PNG instead of SVG)**:

```php
QrCode::format('png')  // Ubah dari svg ke png
    ->size(300)
    ->generate($data, $path);

// Update filename juga:
$filename = 'qrcodes/members/member_' . $member->id . '_' . time() . '.png';
```

### **Tambah Logo di Tengah QR Code**:

```php
QrCode::format('svg')
    ->size(300)
    ->merge('/public/logo.png', 0.3, true)  // Logo 30% dari QR size
    ->generate($data, $path);
```

### **Ubah Warna QR Code**:

```php
QrCode::format('svg')
    ->size(300)
    ->color(255, 111, 0)  // Orange RGB (FF6F00)
    ->backgroundColor(255, 255, 255)  // White background
    ->generate($data, $path);
```

---

## 📱 QR Code Digital (di HP Member)

Member bisa buka QR Code di HP tanpa perlu print:

1. **Member login di HP**
   - Buka browser (Chrome/Safari)
   - URL: http://127.0.0.1:8000/member/login
   - Login dengan akun member

2. **Buka QR Code Page**
   - Menu: QR Code Saya
   - URL: http://127.0.0.1:8000/member/qr-code

3. **Tunjukkan ke Kasir**
   - Kasir scan dari layar HP
   - Set brightness HP maksimal
   - Hindari refleksi cahaya di layar

---

## 📊 Database Schema

### **Table: members**
```sql
CREATE TABLE members (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    member_code VARCHAR(50) UNIQUE,
    qr_code VARCHAR(255),          -- Path ke file QR Code
    points INT DEFAULT 0,
    join_date DATE,
    birthdate DATE,
    status ENUM('active','pending','suspended'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### **Contoh Data**:
```sql
INSERT INTO members VALUES (
    9,
    9,
    'MBR00009',
    'qrcodes/members/member_9_1699200000.svg',  -- QR Code path
    100,
    '2025-11-05',
    '1990-01-15',
    'active',
    NOW(),
    NOW()
);
```

---

## 🔒 Security

### **Data di QR Code Tidak Sensitif**:
✅ Hanya berisi: type, id, member_code, name
❌ TIDAK berisi: password, email, phone, address, poin

### **Validasi di Backend**:
1. Scan QR → decode JSON
2. Ambil `member_code` dari JSON
3. Query database: `SELECT * FROM members WHERE member_code = ?`
4. Cek status member: harus 'active'
5. Load data lengkap dari database (real-time)
6. Display di POS

### **Tidak Bisa Fake QR Code**:
- Data member di-validate real-time dari database
- QR Code hanya shortcut untuk input member code
- Sama seperti ketik manual member code
- Member code unik (tidak bisa duplicate)

---

## 🚀 Future Enhancement

### **Fitur yang Bisa Ditambahkan**:

1. **QR Code Expiry**
   - QR Code berlaku 1 tahun
   - Auto-generate baru setiap tahun
   - Mencegah QR Code lama yang bocor

2. **QR Code with Signature**
   - Tambah digital signature di JSON
   - Validasi signature di backend
   - Extra security layer

3. **Dynamic QR Code**
   - QR Code berubah setiap login
   - Tidak bisa duplikasi/screenshot
   - Member harus online untuk show QR

4. **QR Code Analytics**
   - Track berapa kali QR di-scan
   - Lokasi scan (kasir mana)
   - Timestamp scan terakhir

5. **Bulk Print QR Cards**
   - Admin bisa print semua member QR sekaligus
   - Format PDF grid (6 cards per page)
   - Auto-layout dengan nama + barcode

---

## 📝 Kesimpulan

✅ **QR Code Member Sudah Aktif!**

- ✅ Auto-generate saat registrasi
- ✅ Simpan di storage/app/public/qrcodes/members/
- ✅ Format SVG (scalable, tidak pixelated)
- ✅ Scan dengan webcam di POS
- ✅ Alternative: search by phone/name/code

**Cara Test**:
1. Daftar member baru → QR auto-generate
2. Admin approve member
3. Member login → lihat QR Code
4. Kasir scan QR → data member muncul
5. Transaksi dengan member → dapat poin!

---

**Dibuat**: 6 November 2025  
**Update Terakhir**: 6 November 2025  
**Versi**: 1.0  
**Status**: ✅ Production Ready
