# 🎯 Quick Start: Cara Scan Barcode/QR Code

## 📱 Scan QR Code Member (di POS Kasir)

### Langkah-langkah:
1. **Login sebagai Kasir**
   - Email: `kasir@gmail.com`
   - Password: `12345`

2. **Buka Halaman POS**
   - URL: http://127.0.0.1:8000/kasir/pos
   - Atau klik menu "Point of Sale"

3. **Klik Tombol "Scan QR Member"**
   - Ada di sidebar kanan, bagian atas cart
   - Icon: 📷 kamera

4. **Izinkan Akses Kamera**
   - Browser akan minta permission
   - Klik **"Allow"** atau **"Izinkan"**
   - Jika tidak muncul popup, cek icon kamera di address bar

5. **Arahkan Webcam ke QR Code**
   - Jarak ideal: 15-25 cm
   - Pastikan QR Code terlihat jelas di kotak scan
   - Tunggu beberapa detik untuk deteksi otomatis

6. **Hasil Scan**
   - Data member otomatis muncul di sidebar
   - Nama, poin, dan member code terisi
   - Bisa langsung transaksi dengan diskon member

---

## 🏷️ Scan Barcode Produk (di Admin)

### Langkah-langkah:
1. **Login sebagai Admin**
   - Email: `admin@gmail.com`
   - Password: `12345`

2. **Buka Form Tambah Produk**
   - URL: http://127.0.0.1:8000/admin/products/create
   - Atau: Menu "Kelola Produk" → "Tambah Produk Baru"

3. **Klik Icon Kamera di Field Barcode**
   - Ada icon 📷 di sebelah input Barcode/SKU
   - Modal scanner akan terbuka

4. **Izinkan Akses Kamera**
   - Sama seperti di POS
   - Klik "Allow" jika diminta

5. **Arahkan ke Barcode Produk**
   - Posisi barcode horizontal (garis vertikal)
   - Pastikan pencahayaan cukup
   - Barcode harus utuh (tidak terpotong)

6. **Hasil Scan**
   - Kode barcode otomatis terisi di field
   - Modal scanner otomatis tutup
   - Lanjutkan isi data produk lainnya

---

## 🔍 Troubleshooting Cepat

### ❌ Kamera Tidak Muncul?
**Solusi**:
- Cek icon kamera di address bar browser (kiri atas)
- Klik icon tersebut
- Pilih "Always allow camera"
- Refresh halaman (F5)

### ❌ QR Code Tidak Terbaca?
**Coba**:
- Perbesar QR Code (print lebih besar atau zoom di HP)
- Tambahkan cahaya (nyalakan lampu)
- Bersihkan lensa webcam
- Jika QR Code di layar HP, atur brightness maksimal

### ❌ Barcode Tidak Terdeteksi?
**Coba**:
- Putar barcode 90° atau 180°
- Gerakkan barcode mendekat/menjauh dari kamera
- Pastikan tidak ada refleksi cahaya di barcode
- Pastikan barcode tidak rusak/sobek

### ❌ Scanner Loading Terus?
**Solusi**:
- Tutup aplikasi lain yang pakai webcam (Zoom, Teams, dll)
- Restart browser
- Coba browser lain (Chrome/Edge recommended)

---

## 💡 Tips Hasil Scan Maksimal

### ✅ **Pencahayaan**
- Gunakan cahaya natural (dekat jendela) ATAU lampu ruangan
- Hindari backlight (cahaya dari belakang barcode)
- Jangan terlalu terang (glare/pantulan)

### ✅ **Jarak & Posisi**
- **QR Code**: 10-30 cm dari kamera
- **Barcode**: 15-25 cm dari kamera
- Posisi tegak lurus (kamera di tengah, tidak miring)

### ✅ **Kualitas Barcode**
- Print dengan resolusi tinggi (300 DPI minimum)
- Kontras tinggi: hitam di kertas putih
- Tidak blur/pecah
- Ukuran cukup besar (min 3x3 cm)

### ✅ **Webcam**
- Resolusi minimal: 720p (HD)
- FPS minimal: 30fps
- Auto-focus enabled
- Lens bersih (lap dengan microfiber cloth)

---

## 📋 Format Barcode yang Bisa Di-scan

| Format | Jenis | Contoh Penggunaan |
|--------|-------|-------------------|
| **QR Code** | 2D Code | Member card, payment, URL |
| **EAN-13** | 1D Barcode | Produk retail (13 digit) |
| **EAN-8** | 1D Barcode | Produk kecil (8 digit) |
| **UPC-A** | 1D Barcode | Produk USA/Canada (12 digit) |
| **UPC-E** | 1D Barcode | Produk kecil USA (6 digit) |
| **Code 128** | 1D Barcode | Logistik, shipping |
| **Code 39** | 1D Barcode | Industri, inventory |
| **ITF** | 1D Barcode | Packaging karton |
| **CODABAR** | 1D Barcode | Perpustakaan, blood bank |

---

## 🎓 Best Practices

### **Untuk Kasir**:
1. Minta member tampilkan QR Code sebelum mulai scan produk
2. Pastikan QR Code jelas terlihat (tidak blur)
3. Jika QR Code rusak, input manual member code
4. Verifikasi nama member setelah scan

### **Untuk Admin**:
1. Scan barcode saat input produk baru
2. Jika barcode gagal, ketik manual lalu verify
3. Test scan ulang untuk pastikan barcode tersimpan benar
4. Update barcode jika produk rebranding

### **Maintenance**:
- Bersihkan lensa webcam setiap minggu
- Update browser ke versi terbaru
- Test scanner sebelum jam operasional
- Backup data member code (jika QR Code hilang)

---

## 📞 Support

Jika masih ada masalah:
1. Baca file: `BARCODE_SCANNER_GUIDE.md` (panduan lengkap)
2. Cek console error di browser (F12 → Console)
3. Test di browser lain (Chrome/Edge/Firefox)
4. Pastikan webcam driver terinstall

---

**Happy Scanning! 📸✨**

Dibuat: 6 November 2025
