<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS - Kasir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode" defer></script>
    <style>
        :root {
            --primary: #FFFFFF;
            --secondary: #1F2937;
            --accent: #FF6F00;
            --accent-hover: #F57C00;
            --light-bg: #F9FAFB;
            --border: #E5E7EB;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
        }
        
        * { transition: all 0.2s ease; }
        
        .product-card { 
            @apply bg-white rounded-lg shadow hover:shadow-lg cursor-pointer overflow-hidden border-2 border-transparent;
            transition: all 0.3s ease;
        }
        .product-card:hover { 
            transform: translateY(-4px) scale(1.02);
            border-color: var(--accent);
            box-shadow: 0 12px 24px rgba(255, 111, 0, 0.2);
        }
        .product-img { @apply w-full h-32 object-cover; background-color: var(--light-bg); }
        .cart-item-row { 
            @apply flex items-center justify-between border-b py-2;
            animation: slideInRight 0.3s ease-out;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .btn-action { 
            @apply px-4 py-3 rounded-lg font-semibold;
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .btn-action:active {
            transform: translateY(0);
        }
        #reader { border-radius: 8px; overflow: hidden; max-height: 250px; }
        
        /* Custom Notification */
        .notification-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.2s ease;
        }
        .notification-overlay.show { display: flex; }
        .notification-box {
            background: white;
            border-radius: 12px;
            padding: 0;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
            border: 3px solid var(--accent);
        }
        .notification-header {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
            padding: 20px;
            border-radius: 9px 9px 0 0;
            border-bottom: 2px solid var(--accent-hover);
        }
        .notification-body {
            padding: 25px;
            text-align: center;
        }
        .notification-footer {
            padding: 15px 25px 25px 25px;
            text-align: center;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .fade-in { animation: fadeIn 0.3s ease-out; }
        .slide-down { animation: slideDown 0.3s ease-out; }

        /* POS Responsive Styles */
        @media (max-width: 1280px) {
            .w-96 {
                width: 360px;
            }
        }

        @media (max-width: 1024px) {
            /* Stack layout for tablet */
            body > div {
                flex-direction: column !important;
            }
            .w-96 {
                width: 100% !important;
                max-height: 50vh;
                border-left: none !important;
                border-top: 2px solid var(--border);
            }
            #products-grid {
                grid-template-columns: repeat(3, 1fr) !important;
            }
        }

        @media (max-width: 768px) {
            /* Mobile optimizations */
            #products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 8px !important;
                padding: 12px !important;
            }
            .product-card {
                padding: 8px !important;
            }
            .product-card img {
                height: 80px !important;
            }
            .product-card h3 {
                font-size: 12px !important;
            }
            .product-card .price {
                font-size: 13px !important;
            }
            .w-96 {
                max-height: 60vh;
            }
            #cart-items {
                max-height: 200px !important;
            }
            .notification-modal {
                width: 95% !important;
                max-width: 95% !important;
                margin: 10px !important;
            }
            .notification-body {
                padding: 20px !important;
            }
            .notification-body h2 {
                font-size: 20px !important;
            }
            .notification-body p {
                font-size: 14px !important;
            }
        }

        @media (max-width: 480px) {
            /* Small mobile */
            .p-4 {
                padding: 12px !important;
            }
            .text-3xl {
                font-size: 1.5rem !important;
            }
            .text-xl {
                font-size: 1rem !important;
            }
            #products-grid {
                gap: 6px !important;
                padding: 8px !important;
            }
            .product-card {
                padding: 6px !important;
            }
            .product-card img {
                height: 70px !important;
            }
            .product-card h3 {
                font-size: 11px !important;
            }
            .product-card .price {
                font-size: 12px !important;
            }
            input, button, select {
                font-size: 14px !important;
            }
        }
    </style>
</head>
<body class="h-screen overflow-hidden flex flex-col" style="background: var(--light-bg);">
    <!-- Top Bar -->
    <div class="text-white shadow-lg" style="background: linear-gradient(135deg, var(--secondary) 0%, #111827 100%);">
        <div class="px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-cash-register text-2xl" style="color: var(--accent);"></i>
                <div>
                    <h1 class="text-xl font-bold">Point of Sale</h1>
                    <p class="text-xs opacity-75">Kasir: {{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('kasir.dashboard') }}" class="px-3 py-1.5 rounded text-sm font-semibold" style="background: var(--accent); color: white;" onmouseover="this.style.backgroundColor='var(--accent-hover)'" onmouseout="this.style.backgroundColor='var(--accent)'">
                    <i class="fas fa-home mr-1"></i>Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Left Panel: Products Grid -->
        <div class="flex-1 flex flex-col overflow-hidden bg-white border-r">
            <!-- Category Tabs & Search -->
            <div class="p-4 border-b" style="background: var(--light-bg);">
                <div class="flex items-center space-x-2 mb-3">
                    <input id="search-input" type="text" placeholder="🔍 Cari produk (nama, SKU, barcode)..." 
                           class="flex-1 rounded-lg px-3 py-2 focus:outline-none" style="border: 2px solid var(--border); background: white;" 
                           onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(255, 111, 0, 0.1)'" 
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'" />
                    <button id="btn-scan-toggle" class="px-4 py-2 text-white rounded-lg font-semibold flex items-center gap-2" style="background: #FF6F00;" onmouseover="this.style.backgroundColor='#F57C00'; this.style.transform='scale(1.05)'" onmouseout="this.style.backgroundColor='#FF6F00'; this.style.transform='scale(1)'">
                        <i class="fas fa-qrcode"></i>
                        <span class="hidden sm:inline">Scan</span>
                    </button>
                </div>
                <!-- QR Scanner (hidden by default) -->
                <div id="scanner-panel" class="hidden mt-3 bg-white p-4 rounded-lg slide-down" style="border: 3px solid #FF6F00; box-shadow: 0 4px 12px rgba(255,111,0,0.15);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #FF6F00;">
                        <h3 style="margin: 0; color: #1F2937; font-size: 1rem; font-weight: 700;">
                            <i class="fas fa-qrcode mr-2" style="color: #FF6F00;"></i>Scan QR / Barcode
                        </h3>
                        <button id="btn-close-scanner" style="background: #FEE2E2; border: none; font-size: 1.125rem; cursor: pointer; color: #DC2626; width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='#FCA5A5'" onmouseout="this.style.background='#FEE2E2'">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="reader" style="border-radius: 8px; overflow: hidden; background: #F3F4F6; border: 2px solid #E5E7EB; max-width: 400px; margin: 0 auto;"></div>
                    <div class="mt-3 text-center">
                        <p id="scan-status" class="text-sm font-medium mb-2" style="color: #1F2937;">
                            <i class="fas fa-camera mr-1" style="color: #FF6F00;"></i>Arahkan kamera ke QR code atau barcode
                        </p>
                        <button id="btn-request-camera" class="hidden px-4 py-2 rounded-lg text-xs font-semibold text-white transition" style="background-color: #10B981;">
                            <i class="fas fa-camera mr-1"></i>Izinkan Akses Kamera
                        </button>
                    </div>
                    <audio id="beep" src="https://actions.google.com/sounds/v1/cartoon/wood_plank_flicks.ogg" preload="auto"></audio>
                </div>
            </div>

            <!-- Products Grid -->
            <div id="products-grid" class="flex-1 overflow-y-auto p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 content-start">
                <!-- Products will be loaded here via JS -->
                <div class="col-span-full text-center text-gray-400 py-12">
                    <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                    <p>Memuat produk...</p>
                </div>
            </div>
        </div>

        <!-- Right Panel: Cart & Checkout -->
        <div class="w-96 flex flex-col bg-white border-l">
            <!-- Current Sale Header -->
            <div class="p-4 border-b" style="background: var(--light-bg);">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold" style="color: var(--text-primary);">Transaksi Saat Ini</h2>
                        <p class="text-xs" style="color: var(--text-secondary);">Kasir: {{ auth()->user()->name }}</p>
                    </div>
                    <span id="cart-count" class="text-white text-xs px-3 py-1 rounded-full font-bold" style="background: var(--accent);">0</span>
                </div>
            </div>

            <!-- Member Info -->
            <div class="p-3 border-b" style="background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%);">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center" style="color: var(--accent);">
                        <i class="fas fa-user-tag mr-2"></i>
                        <span id="member-display" class="font-semibold">Umum</span>
                    </div>
                    <button id="btn-select-member" class="text-xs font-semibold" style="color: var(--accent);" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fas fa-search mr-1"></i>Pilih
                    </button>
                </div>
            </div>

            <!-- Cart Items List -->
            <div id="cart-items" class="flex-1 overflow-y-auto p-3 space-y-2">
                <div class="text-center text-gray-400 py-12">
                    <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                    <p class="text-sm">Belum ada item</p>
                </div>
            </div>

            <!-- Summary & Payment -->
            <div class="border-t p-4 space-y-3" style="background: var(--light-bg);">
                <!-- Summary -->
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between" style="color: var(--text-secondary);">
                        <span>Subtotal:</span>
                        <span id="subtotal" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between" style="color: #10B981;">
                        <span>Diskon Member:</span>
                        <span id="member-discount" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between" style="color: var(--text-secondary);">
                        <span>Pajak (11%):</span>
                        <span id="tax" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between" style="color: var(--accent);">
                        <span>Potongan Poin:</span>
                        <span id="points-discount" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-2 border-t" style="color: var(--text-primary);">
                        <span>TOTAL:</span>
                        <span id="total">Rp 0</span>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-xs font-medium mb-1" style="color: var(--text-primary);">Metode Pembayaran:</label>
                    <div class="w-full rounded-lg px-3 py-2 text-sm font-semibold text-center" style="border: 2px solid var(--accent); background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%); color: var(--accent);">
                        💵 Tunai (Cash)
                    </div>
                    <input id="payment-method" type="hidden" value="cash" />
                </div>

                <!-- Payment Amount -->
                <div>
                    <label class="block text-xs font-medium mb-1" style="color: var(--text-primary);">Jumlah Bayar:</label>
                    <input id="payment-amount" type="number" step="1000" placeholder="0" 
                           class="w-full rounded-lg px-3 py-2 text-sm focus:outline-none" style="border: 2px solid var(--border); background: white;"
                           onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(255, 111, 0, 0.1)'" 
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'" />
                    <div class="text-xs mt-1" style="color: var(--text-secondary);">Kembalian: <span id="change" class="font-bold" style="color: #10B981;">Rp 0</span></div>
                </div>

                <!-- Points -->
                <div>
                    <label class="block text-xs font-medium mb-1" style="color: var(--text-primary);">Gunakan Poin:</label>
                    <input id="points-used" type="number" min="0" step="1" value="0" placeholder="0" 
                           class="w-full rounded-lg px-3 py-2 text-sm focus:outline-none" style="border: 2px solid var(--border); background: white;"
                           onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px rgba(255, 111, 0, 0.1)'" 
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'" />
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-3 gap-2 pt-2">
                    <button id="btn-hold" class="btn-action text-white text-xs font-bold" style="background: #F59E0B;" onmouseover="this.style.background='#D97706'" onmouseout="this.style.background='#F59E0B'">
                        <i class="fas fa-pause"></i> Hold
                    </button>
                    <button id="btn-cancel" class="btn-action text-white text-xs font-bold" style="background: #EF4444;" onmouseover="this.style.background='#DC2626'" onmouseout="this.style.background='#EF4444'">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button id="btn-pay" class="btn-action text-white text-xs font-bold" style="background: #10B981;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10B981'">
                        <i class="fas fa-check"></i> Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Member Selection -->
    <div id="member-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 fade-in">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md m-4 slide-down" style="border: 2px solid var(--accent);">
            <div class="p-4 border-b flex items-center justify-between" style="background: var(--light-bg);">
                <h3 class="font-bold" style="color: var(--text-primary);">Pilih Member</h3>
                <button id="close-member-modal" class="hover:opacity-70 transition" style="color: var(--text-secondary);" onmouseover="this.style.transform='rotate(90deg)'" onmouseout="this.style.transform='rotate(0deg)'"">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <input id="member-search-input" type="text" placeholder="🔍 Cari member (nama, kode, telepon)..." 
                       class="w-full border rounded-lg px-3 py-2 mb-3 focus:outline-none" style="border-color: var(--cream);"
                       onfocus="this.style.borderColor='var(--accent-cream)'" onblur="this.style.borderColor='var(--cream)'" />
                <ul id="member-search-results" class="max-h-64 overflow-y-auto space-y-2 mb-3"></ul>
                <button id="btn-register-member" class="w-full text-white px-4 py-2 rounded-lg font-medium transition hover:opacity-90" style="background-color: #10B981;">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Member Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Register New Member -->
    <div id="register-member-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md m-4" style="border: 2px solid var(--cream);">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, var(--cream) 0%, var(--light-cream) 100%); border-color: var(--accent-cream);">
                <h3 class="font-bold" style="color: var(--dark-gray);">Daftar Member Baru</h3>
                <button id="close-register-modal" class="hover:opacity-70 transition" style="color: var(--dark-gray);">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="register-member-form" class="p-4 space-y-3">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--dark-gray);">Nama Lengkap <span class="text-red-600">*</span></label>
                    <input type="text" name="name" required class="w-full border rounded-lg px-3 py-2 focus:outline-none" style="border-color: var(--cream);"
                           onfocus="this.style.borderColor='var(--accent-cream)'" onblur="this.style.borderColor='var(--cream)'" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--dark-gray);">Nomor Telepon <span class="text-red-600">*</span></label>
                    <input type="text" name="phone" required placeholder="Contoh: 08123456789" class="w-full border rounded-lg px-3 py-2 focus:outline-none" style="border-color: var(--cream);"
                           onfocus="this.style.borderColor='var(--accent-cream)'" onblur="this.style.borderColor='var(--cream)'" />
                    <p class="text-xs mt-1" style="color: var(--dark-gray); opacity: 0.7;">Nomor telepon akan digunakan sebagai identitas unik member</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color: var(--dark-gray);">Password (opsional)</label>
                    <input type="password" name="password" minlength="6" placeholder="Default: 123456" class="w-full border rounded-lg px-3 py-2 focus:outline-none" style="border-color: var(--cream);"
                           onfocus="this.style.borderColor='var(--accent-cream)'" onblur="this.style.borderColor='var(--cream)'" />
                    <p class="text-xs mt-1" style="color: var(--dark-gray); opacity: 0.7;">Kosongkan untuk menggunakan password default</p>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 text-white px-4 py-2 rounded-lg font-medium transition" style="background-color: #10B981;">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button type="button" id="cancel-register" class="flex-1 px-4 py-2 rounded-lg font-medium transition" style="background-color: var(--cream); color: var(--dark-gray);">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Receipt (Struk) -->
    <div id="receipt-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50" style="backdrop-filter: blur(4px);">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-md m-4" style="border: 3px solid #FF6F00;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #FF6F00 0%, #F57C00 100%); color: white;">
                <h3 class="font-bold text-lg">
                    <i class="fas fa-receipt mr-2"></i>Struk Transaksi
                </h3>
                <button id="close-receipt-modal" class="hover:opacity-70 transition text-white text-2xl font-bold">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="receipt-content" class="p-6 max-h-[70vh] overflow-y-auto" style="font-family: 'Courier New', monospace;">
                <!-- Receipt content will be loaded here -->
            </div>
            <div class="p-4 border-t flex gap-2" style="background: #F9FAFB;">
                <button id="btn-print-receipt" class="flex-1 text-white px-4 py-3 rounded-lg font-bold transition hover:opacity-90" style="background: #10B981;">
                    <i class="fas fa-print mr-2"></i>Cetak Struk
                </button>
                <button id="btn-new-transaction" class="flex-1 text-white px-4 py-3 rounded-lg font-bold transition hover:opacity-90" style="background: #FF6F00;">
                    <i class="fas fa-shopping-cart mr-2"></i>Transaksi Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Custom Notification Popup -->
    <div id="notification-overlay" class="notification-overlay">
        <div class="notification-box">
            <div class="notification-header">
                <div class="flex items-center justify-center">
                    <i id="notification-icon" class="fas fa-check-circle text-4xl mb-2" style="color: var(--dark-gray);"></i>
                </div>
            </div>
            <div class="notification-body">
                <h3 id="notification-title" class="text-xl font-bold mb-2" style="color: var(--dark-gray);">Transaksi Berhasil!</h3>
                <div id="notification-message" class="text-base" style="color: var(--dark-gray); opacity: 0.8;">
                    <!-- Message will be inserted here -->
                </div>
            </div>
            <div class="notification-footer">
                <button id="notification-ok-btn" class="px-8 py-3 rounded-lg font-semibold text-white transition hover:opacity-90" style="background-color: #10B981;">
                    <i class="fas fa-check mr-2"></i>OK
                </button>
            </div>
        </div>
    </div>

<audio id="beep" src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSl+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBSh+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBSh+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBSh+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBQ==" preload="auto"></audio>
<audio id="cash-register" preload="auto">
    <source src="https://actions.google.com/sounds/v1/office/cash_register.ogg" type="audio/ogg">
    <source src="https://freesound.org/data/previews/268/268822_4486188-lq.mp3" type="audio/mpeg">
</audio>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const beep = document.getElementById('beep');
const cashRegisterSound = document.getElementById('cash-register');
let cart = [];
let member = null;
let allProducts = [];
let taxRate = {{ isset($taxRate) ? (float) $taxRate : 11 }};
let globalMemberDiscPercent = {{ isset($globalMemberDiscPercent) ? (float) $globalMemberDiscPercent : 5 }};

// Cash Register Sound Effect using Web Audio API
let soundPlayed = false;

function playCashRegisterSound() {
  if(soundPlayed) return; // Prevent multiple plays
  soundPlayed = true;
  
  try {
    // Try playing the audio element first
    if(cashRegisterSound) {
      cashRegisterSound.currentTime = 0;
      cashRegisterSound.play().catch(err => {
        console.log('Audio element failed, using Web Audio API');
        generateCashRegisterSound();
      });
    } else {
      generateCashRegisterSound();
    }
  } catch(e) {
    console.log('Error playing sound:', e);
    generateCashRegisterSound();
  }
  
  // Reset flag after sound duration
  setTimeout(() => { soundPlayed = false; }, 1000);
}

// Generate cash register sound effect with Web Audio API
function generateCashRegisterSound() {
  try {
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    const now = audioCtx.currentTime;
    
    // KA-CHING Sound Effect (Viral TikTok Style)
    
    // "KA" sound - Quick high bell
    const osc1 = audioCtx.createOscillator();
    const gain1 = audioCtx.createGain();
    osc1.connect(gain1);
    gain1.connect(audioCtx.destination);
    osc1.frequency.value = 1800; // High pitch
    osc1.type = 'sine';
    gain1.gain.setValueAtTime(0.4, now);
    gain1.gain.exponentialRampToValueAtTime(0.01, now + 0.08);
    osc1.start(now);
    osc1.stop(now + 0.08);
    
    // "CHING" sound - Main bell ring (iconic!)
    const osc2 = audioCtx.createOscillator();
    const gain2 = audioCtx.createGain();
    osc2.connect(gain2);
    gain2.connect(audioCtx.destination);
    osc2.frequency.value = 1400;
    osc2.type = 'sine';
    gain2.gain.setValueAtTime(0, now + 0.08);
    gain2.gain.linearRampToValueAtTime(0.5, now + 0.1);
    gain2.gain.exponentialRampToValueAtTime(0.01, now + 0.5);
    osc2.start(now + 0.08);
    osc2.stop(now + 0.5);
    
    // Second harmonic for richness
    const osc3 = audioCtx.createOscillator();
    const gain3 = audioCtx.createGain();
    osc3.connect(gain3);
    gain3.connect(audioCtx.destination);
    osc3.frequency.value = 2800; // Octave higher
    osc3.type = 'sine';
    gain3.gain.setValueAtTime(0, now + 0.08);
    gain3.gain.linearRampToValueAtTime(0.2, now + 0.1);
    gain3.gain.exponentialRampToValueAtTime(0.01, now + 0.4);
    osc3.start(now + 0.08);
    osc3.stop(now + 0.4);
    
    // Cash drawer "thunk" sound
    const osc4 = audioCtx.createOscillator();
    const gain4 = audioCtx.createGain();
    osc4.connect(gain4);
    gain4.connect(audioCtx.destination);
    osc4.frequency.value = 150;
    osc4.type = 'square';
    gain4.gain.setValueAtTime(0, now + 0.15);
    gain4.gain.linearRampToValueAtTime(0.3, now + 0.18);
    gain4.gain.exponentialRampToValueAtTime(0.01, now + 0.35);
    osc4.start(now + 0.15);
    osc4.stop(now + 0.35);
    
    // Add some "coins jingling" effect
    for(let i = 0; i < 3; i++) {
      const coinOsc = audioCtx.createOscillator();
      const coinGain = audioCtx.createGain();
      coinOsc.connect(coinGain);
      coinGain.connect(audioCtx.destination);
      coinOsc.frequency.value = 2000 + (Math.random() * 500);
      coinOsc.type = 'sine';
      const startTime = now + 0.2 + (i * 0.05);
      coinGain.gain.setValueAtTime(0.1, startTime);
      coinGain.gain.exponentialRampToValueAtTime(0.01, startTime + 0.08);
      coinOsc.start(startTime);
      coinOsc.stop(startTime + 0.08);
    }
    
    console.log('🔊 KA-CHING! Cash register sound played!');
  } catch(e) {
    console.error('Web Audio API error:', e);
  }
}

// Show Custom Notification
function showNotification(title, message, icon = 'check-circle', iconColor = null) {
  return new Promise((resolve) => {
    const overlay = document.getElementById('notification-overlay');
    const titleEl = document.getElementById('notification-title');
    const messageEl = document.getElementById('notification-message');
    const iconEl = document.getElementById('notification-icon');
    const okBtn = document.getElementById('notification-ok-btn');
    
    // Set content
    titleEl.textContent = title;
    messageEl.innerHTML = message;
    iconEl.className = `fas fa-${icon} text-4xl mb-2`;
    if(iconColor) iconEl.style.color = iconColor;
    else iconEl.style.color = 'var(--dark-gray)';
    
    // Show notification
    overlay.classList.add('show');
    
    // Handle OK button
    const handleOk = () => {
      overlay.classList.remove('show');
      okBtn.removeEventListener('click', handleOk);
      resolve();
    };
    
    okBtn.addEventListener('click', handleOk);
    
    // Handle click outside (optional)
    overlay.onclick = (e) => {
      if(e.target === overlay) {
        overlay.classList.remove('show');
        okBtn.removeEventListener('click', handleOk);
        resolve();
      }
    };
  });
}

function formatRupiah(n){
  return new Intl.NumberFormat('id-ID',{style:'currency', currency:'IDR', minimumFractionDigits:0}).format(n);
}

function updateCartUI(){
  const cartEl = document.getElementById('cart-items');
  const cartCountEl = document.getElementById('cart-count');
  cartEl.innerHTML = '';
  
  if(cart.length === 0){
    cartEl.innerHTML = '<div class="text-center text-gray-400 py-12"><i class="fas fa-shopping-cart text-4xl mb-2"></i><p class="text-sm">Belum ada item</p></div>';
    cartCountEl.textContent = '0';
  } else {
    cartCountEl.textContent = cart.length;
  }
  
  let subtotal=0, memberDiscount=0;
  cart.forEach((item, idx)=>{
    const normalPrice = parseFloat(item.price);
    let price = normalPrice;
    if(item.isMember){
      if(item.member_price && parseFloat(item.member_price)>0){
        price = parseFloat(item.member_price);
      } else if (globalMemberDiscPercent>0){
        price = normalPrice * (1 - (globalMemberDiscPercent/100));
      }
    }
    const sub = price * item.qty;
    const disc = Math.max(0, (normalPrice - price)) * item.qty;
    subtotal += sub;
    memberDiscount += disc;
    const row = document.createElement('div');
    row.className = 'cart-item-row';
    row.innerHTML = `
      <div class="flex-1">
        <div class="font-semibold text-sm text-gray-800">${item.name}</div>
        <div class="text-xs text-gray-500">${formatRupiah(price)} x ${item.qty}</div>
      </div>
      <div class="flex items-center space-x-1">
        <button class="w-6 h-6 bg-red-100 text-red-600 hover:bg-red-200 rounded text-xs" data-idx="${idx}" data-act="dec">
          <i class="fas fa-minus"></i>
        </button>
        <span class="w-8 text-center font-bold text-sm">${item.qty}</span>
        <button class="w-6 h-6 bg-green-100 text-green-600 hover:bg-green-200 rounded text-xs" data-idx="${idx}" data-act="inc">
          <i class="fas fa-plus"></i>
        </button>
        <button class="w-6 h-6 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded text-xs ml-1" data-idx="${idx}" data-act="del">
          <i class="fas fa-trash"></i>
        </button>
      </div>
      <div class="text-right font-semibold text-sm ml-2 w-20">${formatRupiah(sub)}</div>
    `;
    cartEl.appendChild(row);
  });
  const tax = subtotal * (taxRate/100);
  const pointsUsed = parseInt(document.getElementById('points-used').value||0,10);
  const pointsDiscount = pointsUsed * 1000;
  const total = subtotal + tax - pointsDiscount;
  document.getElementById('subtotal').textContent = formatRupiah(subtotal);
  document.getElementById('member-discount').textContent = formatRupiah(memberDiscount);
  document.getElementById('tax').textContent = formatRupiah(tax);
  document.getElementById('points-discount').textContent = formatRupiah(pointsDiscount);
  document.getElementById('total').textContent = formatRupiah(total);
  const payAmt = parseFloat(document.getElementById('payment-amount').value||0);
  document.getElementById('change').textContent = formatRupiah(Math.max(0, payAmt - total));
}

function addToCart(p){
  const existing = cart.find(i=>i.id===p.id);
  if(existing){ existing.qty += 1; }
  else{ cart.push({ ...p, qty:1, isMember: !!member }); }
  updateCartUI();
  if(beep) beep.play().catch(()=>{});
}

// Cart actions
document.getElementById('cart-items').addEventListener('click', (e)=>{
  const target = e.target.closest('[data-act]');
  if(!target) return;
  const act = target.getAttribute('data-act');
  const idx = parseInt(target.getAttribute('data-idx'),10);
  if(!isNaN(idx)){
    if(act==='inc') cart[idx].qty++;
    if(act==='dec') cart[idx].qty = Math.max(1, cart[idx].qty-1);
    if(act==='del') cart.splice(idx,1);
    updateCartUI();
  }
});

// Load all products
let isLoadingProducts = false;
async function loadProducts(){
  if(isLoadingProducts) {
    console.log('Already loading products, skipping...');
    return; // Prevent duplicate loading
  }
  
  console.log('Starting loadProducts...');
  isLoadingProducts = true;
  
  const grid = document.getElementById('products-grid');
  if(!grid) {
    console.error('Products grid element not found!');
    isLoadingProducts = false;
    return;
  }
  
  grid.innerHTML = '<div class="col-span-full text-center text-blue-600 py-12"><i class="fas fa-spinner fa-spin text-3xl mb-2"></i><p>Memuat produk...</p></div>';
  
  try {
    console.log('Fetching products from server...');
    const res = await fetch('{{ route("kasir.search-product") }}?query=', { 
      headers: { 'X-Requested-With':'XMLHttpRequest' }
    });
    
    if (!res.ok) {
      throw new Error('Failed to fetch products: ' + res.status);
    }
    
    const data = await res.json();
    console.log('Products loaded:', data.products ? data.products.length : 0);
    allProducts = data.products || [];
    renderProducts(allProducts);
  } catch(e){
    console.error('Load products error:', e);
    grid.innerHTML = '<div class="col-span-full text-center text-red-500 py-12"><i class="fas fa-exclamation-triangle text-3xl mb-2"></i><p>Gagal memuat produk: ' + e.message + '</p><button onclick="loadProducts()" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Coba Lagi</button></div>';
  } finally {
    isLoadingProducts = false;
    console.log('loadProducts finished');
  }
}

function renderProducts(products){
  const grid = document.getElementById('products-grid');
  
  // Clear grid first to prevent duplicates
  while(grid.firstChild) {
    grid.removeChild(grid.firstChild);
  }
  
  if(products.length === 0){
    const emptyDiv = document.createElement('div');
    emptyDiv.className = 'col-span-full text-center text-gray-400 py-12';
    emptyDiv.innerHTML = '<i class="fas fa-box-open text-3xl mb-2"></i><p>Tidak ada produk</p>';
    grid.appendChild(emptyDiv);
    return;
  }
  
  // Use DocumentFragment for better performance
  const fragment = document.createDocumentFragment();
  
  products.forEach(p=>{
    const card = document.createElement('div');
    card.className = 'product-card';
    const imgSrc = p.image ? `/storage/${p.image}` : 'https://via.placeholder.com/150?text=No+Image';
    card.innerHTML = `
      <img src="${imgSrc}" alt="${p.name}" class="product-img" onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
      <div class="p-2">
        <div class="font-semibold text-xs text-gray-800 truncate">${p.name}</div>
        <div class="text-xs text-blue-600 font-bold mt-1">${formatRupiah(p.price)}</div>
        ${p.member_price ? `<div class="text-xs text-purple-600">★ ${formatRupiah(p.member_price)}</div>` : ''}
        <div class="text-xs text-gray-400 mt-1">Stok: ${p.stock}</div>
      </div>
    `;
    card.onclick = ()=> addToCart(p);
    fragment.appendChild(card);
  });
  
  grid.appendChild(fragment);
}

// Search products
const searchInput = document.getElementById('search-input');
let searchTimeout;
searchInput.addEventListener('input', ()=>{
  clearTimeout(searchTimeout);
  const q = searchInput.value.trim().toLowerCase();
  if(!q){ 
    renderProducts(allProducts); 
    return; 
  }
  searchTimeout = setTimeout(()=>{
    const filtered = allProducts.filter(p => 
      p.name.toLowerCase().includes(q) || 
      p.sku.toLowerCase().includes(q) ||
      (p.barcode && p.barcode.toLowerCase().includes(q))
    );
    renderProducts(filtered);
  }, 300);
});

// Toggle QR scanner
const scanToggle = document.getElementById('btn-scan-toggle');
const scannerPanel = document.getElementById('scanner-panel');
let scannerActive = false;

// Scanner will only start when user clicks the scan button (removed auto-start)
// This prevents infinite loading on page refresh

scanToggle.addEventListener('click', ()=>{
  scannerActive = !scannerActive;
  console.log('Scanner toggle:', scannerActive);
  if(scannerActive){
    scannerPanel.classList.remove('hidden');
    startScanner();
  } else {
    scannerPanel.classList.add('hidden');
    stopScanner();
  }
});

// Close scanner button
document.getElementById('btn-close-scanner').addEventListener('click', () => {
  scannerActive = false;
  scannerPanel.classList.add('hidden');
  stopScanner();
});

// Manual camera permission request button
document.getElementById('btn-request-camera').addEventListener('click', async () => {
  console.log('Manual camera request clicked');
  const btnRequest = document.getElementById('btn-request-camera');
  const statusEl = document.getElementById('scan-status');
  
  btnRequest.classList.add('hidden');
  statusEl.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Meminta izin kamera...';
  
  try {
    // Request camera permission explicitly
    const stream = await navigator.mediaDevices.getUserMedia({ video: true });
    
    // Permission granted, stop the stream and start scanner
    stream.getTracks().forEach(track => track.stop());
    console.log('Permission granted, starting scanner...');
    statusEl.innerHTML = '<i class="fas fa-check-circle text-green-600 mr-1"></i> Izin diberikan, memulai scanner...';
    
    setTimeout(() => {
      startScanner();
    }, 500);
    
  } catch(err) {
    console.error('Permission request failed:', err);
    statusEl.innerHTML = `
      <div class="text-red-600 text-xs">
        <i class="fas fa-exclamation-circle mr-1"></i> <strong>Izin Ditolak!</strong><br><br>
        <div class="text-left bg-red-50 p-3 rounded mt-2" style="border: 1px solid #FCA5A5;">
          <p class="font-semibold mb-2">Cara Mengizinkan Kamera:</p>
          <ol class="list-decimal ml-4 space-y-1">
            <li>Klik icon <strong>🔒 gembok</strong> atau <strong>🎥 kamera</strong> di address bar (kiri atas)</li>
            <li>Ubah "Camera" dari <strong>Block</strong> ke <strong>Allow</strong></li>
            <li>Refresh halaman (F5)</li>
          </ol>
        </div>
      </div>
    `;
    btnRequest.classList.remove('hidden');
    btnRequest.innerHTML = '<i class="fas fa-redo mr-1"></i>Coba Lagi';
  }
});

// Member modal
const memberModal = document.getElementById('member-modal');
const btnSelectMember = document.getElementById('btn-select-member');
const closeMemberModal = document.getElementById('close-member-modal');
const memberSearchInput = document.getElementById('member-search-input');
const memberResultsEl = document.getElementById('member-search-results');

btnSelectMember.addEventListener('click', ()=> memberModal.classList.remove('hidden'));
closeMemberModal.addEventListener('click', ()=> memberModal.classList.add('hidden'));

let memberSearchTimeout;
memberSearchInput.addEventListener('input', ()=>{
  clearTimeout(memberSearchTimeout);
  const q = memberSearchInput.value.trim();
  if(!q){ memberResultsEl.innerHTML=''; return; }
  memberSearchTimeout = setTimeout(async ()=>{
    const res = await fetch(`{{ route('kasir.search-member') }}?query=${encodeURIComponent(q)}`, { headers: { 'X-Requested-With':'XMLHttpRequest' }});
    const data = await res.json();
    memberResultsEl.innerHTML = '';
    if((data.members||[]).length === 0){
      memberResultsEl.innerHTML = '<li class="text-gray-400 text-center py-4 text-sm">Member tidak ditemukan</li>';
    }
    (data.members||[]).forEach(m=>{
      const li = document.createElement('li');
      li.className = 'p-3 cursor-pointer rounded border transition';
      li.style.backgroundColor = 'white';
      li.style.borderColor = 'var(--cream)';
      li.onmouseenter = function(){ this.style.backgroundColor = 'var(--light-cream)'; this.style.borderColor = 'var(--accent-cream)'; };
      li.onmouseleave = function(){ this.style.backgroundColor = 'white'; this.style.borderColor = 'var(--cream)'; };
      li.innerHTML = `
        <div class="flex items-center justify-between">
          <div>
            <div class="font-semibold text-sm" style="color: var(--dark-gray);">${m.name}</div>
            <div class="text-xs" style="color: var(--dark-gray); opacity: 0.6;">${m.member_code||'-'} · ${m.phone||''}</div>
          </div>
          <div class="text-right">
            <div class="text-xs" style="color: var(--dark-gray); opacity: 0.6;">Poin</div>
            <div class="font-bold text-sm" style="color: var(--accent-cream);">${m.points}</div>
          </div>
        </div>
      `;
      li.onclick = ()=>{
        member = m;
        document.getElementById('member-display').innerHTML = `${m.name} <span class="text-xs">(${m.points} poin)</span>`;
        cart = cart.map(i => ({...i, isMember: true}));
        updateCartUI();
        memberModal.classList.add('hidden');
        memberSearchInput.value='';
        memberResultsEl.innerHTML='';
      };
      memberResultsEl.appendChild(li);
    });
  }, 300);
});

// Payment amount change
document.getElementById('payment-amount').addEventListener('input', updateCartUI);
document.getElementById('points-used').addEventListener('input', updateCartUI);

// Buttons
document.getElementById('btn-cancel').addEventListener('click', ()=>{
  if(confirm('Batalkan transaksi?')){ cart=[]; member=null; updateCartUI(); document.getElementById('member-display').textContent='Umum'; }
});

document.getElementById('btn-hold').addEventListener('click', async ()=>{
  if(cart.length===0){ alert('Keranjang kosong!'); return; }
  // Hold transaction logic here
  alert('Fitur Hold belum diimplementasikan');
});

document.getElementById('btn-pay').addEventListener('click', async ()=>{
  if(cart.length===0){ alert('Keranjang kosong!'); return; }
  const total = parseFloat(document.getElementById('total').textContent.replace(/[^0-9]/g,''));
  const payAmt = parseFloat(document.getElementById('payment-amount').value||0);
  if(payAmt < total){ alert('Jumlah bayar kurang!'); return; }
  // Process payment logic here
  const payload = {
    items: cart.map(i=>({product_id:i.id, quantity:i.qty, price:i.price, member_price:i.member_price||null})),
    member_id: member?.id||null,
    payment_method: document.getElementById('payment-method').value,
    payment_amount: payAmt,
    points_used: parseInt(document.getElementById('points-used').value||0,10)
  };
  try {
    const res = await fetch('{{ route("kasir.checkout") }}', {
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken},
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if(data.success){
      const transactionId = data.transaction.id;
      const changeAmount = formatRupiah(data.transaction.change_amount || 0);
      
      // Show notification first
      const message = `
        <div class="space-y-3">
          <div class="text-lg font-semibold" style="color: #10B981;">💰 Kembalian</div>
          <div class="text-3xl font-bold" style="color: var(--dark-gray);">${changeAmount}</div>
          <div class="text-sm mt-3" style="color: var(--dark-gray); opacity: 0.7;">
            <i class="fas fa-check-circle mr-1"></i> Transaksi #${data.transaction.transaction_code} berhasil
          </div>
          <div class="mt-4 p-3 rounded-lg" style="background: #FEF3C7; border: 1px solid #FCD34D;">
            <p style="color: #78350F; font-size: 0.875rem; font-weight: 600;">
              <i class="fas fa-print mr-1"></i> Struk akan dibuka otomatis
            </p>
          </div>
        </div>
      `;
      
      // Show notification and wait for OK button
      await showNotification('✅ Transaksi Berhasil!', message, 'check-circle', '#10B981');
      
      // Play sound AFTER user clicks OK
      playCashRegisterSound();
      
      // Load and show receipt modal
      loadReceipt(transactionId);
      
      // Reset cart
      cart=[]; member=null; updateCartUI();
      document.getElementById('member-display').textContent='Umum';
      document.getElementById('payment-amount').value='';
      document.getElementById('points-used').value='0';
    } else {
      await showNotification('❌ Transaksi Gagal', 
        `<p style="color: #EF4444;">${data.message||'Terjadi kesalahan'}</p>`, 
        'times-circle', '#EF4444');
    }
  } catch(e){ 
    await showNotification('❌ Error', 
      `<p style="color: #EF4444;">${e.message}</p>`, 
      'exclamation-triangle', '#F59E0B');
  }
});

// QR Scanner
let html5QrCode;
function startScanner(){
  const statusEl = document.getElementById('scan-status');
  
  if(!html5QrCode && typeof Html5Qrcode !== 'undefined'){
    html5QrCode = new Html5Qrcode("reader");
  }
  
  if(html5QrCode){
    statusEl.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Membuka kamera...';
    
    // Try with different camera configs
    Html5Qrcode.getCameras().then(cameras => {
      if (cameras && cameras.length) {
        console.log('Cameras found:', cameras.length);
        statusEl.innerHTML = '<i class="fas fa-camera mr-1"></i> Kamera ditemukan, memulai...';
        
        // Start with first camera (or back camera if available)
        const cameraId = cameras.length > 1 ? cameras[1].id : cameras[0].id;
        
        html5QrCode.start(
          cameraId,
          { 
            fps: 10, 
            qrbox: { width: 220, height: 220 },
            formatsToSupport: [
              Html5QrcodeSupportedFormats.QR_CODE,
              Html5QrcodeSupportedFormats.EAN_13,
              Html5QrcodeSupportedFormats.EAN_8,
              Html5QrcodeSupportedFormats.CODE_128,
              Html5QrcodeSupportedFormats.CODE_39,
              Html5QrcodeSupportedFormats.UPC_A,
              Html5QrcodeSupportedFormats.UPC_E,
              Html5QrcodeSupportedFormats.ITF,
              Html5QrcodeSupportedFormats.CODABAR
            ]
          },
          onScanSuccess,
          onScanError
        ).then(() => {
          statusEl.innerHTML = '<i class="fas fa-check-circle" style="color: #10B981; margin-right: 4px;"></i><span style="color: #1F2937; font-weight: 600;">Kamera aktif! Arahkan ke QR/Barcode</span>';
          console.log('Scanner started successfully');
        }).catch(err => {
          console.error('Error starting scanner:', err);
          statusEl.innerHTML = `<i class="fas fa-exclamation-triangle text-red-600 mr-1"></i> Gagal: ${err}`;
          document.getElementById('btn-request-camera').classList.remove('hidden');
          
          // Fallback: try with facingMode
          setTimeout(() => {
            statusEl.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mencoba metode lain...';
            html5QrCode.start(
              { facingMode: "environment" },
              { 
                fps: 10, 
                qrbox: { width: 220, height: 220 },
                formatsToSupport: [
                  Html5QrcodeSupportedFormats.QR_CODE,
                  Html5QrcodeSupportedFormats.EAN_13,
                  Html5QrcodeSupportedFormats.EAN_8,
                  Html5QrcodeSupportedFormats.CODE_128,
                  Html5QrcodeSupportedFormats.CODE_39,
                  Html5QrcodeSupportedFormats.UPC_A,
                  Html5QrcodeSupportedFormats.UPC_E,
                  Html5QrcodeSupportedFormats.ITF,
                  Html5QrcodeSupportedFormats.CODABAR
                ]
              },
              onScanSuccess,
              onScanError
            ).then(() => {
              statusEl.innerHTML = '<i class="fas fa-check-circle" style="color: #10B981; margin-right: 4px;"></i><span style="color: #1F2937; font-weight: 600;">Kamera aktif! Arahkan ke QR/Barcode</span>';
              document.getElementById('btn-request-camera').classList.add('hidden');
            }).catch(err2 => {
              console.error('Fallback failed:', err2);
              
              // Check if it's permission error
              const isPermissionError = err2.toString().includes('Permission') || 
                                       err2.toString().includes('NotAllowedError') ||
                                       err2.name === 'NotAllowedError';
              
              if(isPermissionError) {
                statusEl.innerHTML = `
                  <div class="text-red-600 text-xs">
                    <i class="fas fa-ban mr-1"></i> <strong>Akses Kamera Ditolak</strong><br><br>
                    <div class="text-left bg-yellow-50 p-3 rounded mt-2" style="border: 1px solid #FCD34D;">
                      <p class="font-semibold mb-2" style="color: var(--dark-gray);">📌 Langkah-langkah:</p>
                      <ol class="list-decimal ml-4 space-y-1" style="color: var(--dark-gray);">
                        <li>Klik icon <strong>🎥 kamera</strong> di address bar (pojok kiri atas)</li>
                        <li>Pilih <strong>"Allow"</strong> untuk Camera</li>
                        <li>Klik tombol hijau di bawah atau refresh halaman</li>
                      </ol>
                    </div>
                  </div>
                `;
              } else {
                statusEl.innerHTML = `
                  <i class="fas fa-times-circle text-red-600 mr-1"></i> 
                  Error: ${err2}<br>
                  <small>Klik tombol di bawah untuk coba lagi</small>
                `;
              }
              
              document.getElementById('btn-request-camera').classList.remove('hidden');
            });
          }, 1000);
        });
      } else {
        statusEl.innerHTML = '<i class="fas fa-times-circle text-red-600 mr-1"></i> Tidak ada kamera ditemukan. Pastikan webcam terhubung.';
        document.getElementById('btn-request-camera').classList.remove('hidden');
        console.error('No cameras found');
      }
    }).catch(err => {
      console.error('Error getting cameras:', err);
      statusEl.innerHTML = '<i class="fas fa-exclamation-triangle text-red-600 mr-1"></i> Error: ' + err.message;
      document.getElementById('btn-request-camera').classList.remove('hidden');
    });
  }
}

function stopScanner(){
  if(html5QrCode){ 
    if(html5QrCode.isScanning){
      html5QrCode.stop().then(() => {
        console.log('Scanner stopped successfully');
        document.getElementById('scan-status').innerHTML = '<i class="fas fa-info-circle mr-1" style="color: #6B7280;"></i>Scanner dihentikan. Klik tombol Scan untuk mulai lagi.';
        // Clear the scanner instance
        html5QrCode.clear();
      }).catch(err => {
        console.error('Error stopping scanner:', err);
        // Force clear even if stop fails
        try {
          html5QrCode.clear();
        } catch(e) {
          console.error('Error clearing scanner:', e);
        }
      }); 
    } else {
      // Scanner not running but instance exists, clear it
      try {
        html5QrCode.clear();
        console.log('Scanner instance cleared');
      } catch(e) {
        console.error('Error clearing scanner:', e);
      }
    }
  }
}

function onScanError(error) {
  // Ignore scan errors (happens when no QR detected)
  // console.log('Scan error:', error);
}

async function onScanSuccess(decoded){
  if(beep) beep.play().catch(()=>{});
  document.getElementById('scan-status').innerHTML = `<i class="fas fa-check-circle text-green-600 mr-1"></i> Memproses: ${decoded}`;
  // Check if product or member
  try {
    const res = await fetch(`{{ route('kasir.scan-qr') }}?code=${encodeURIComponent(decoded)}`, { headers: { 'X-Requested-With':'XMLHttpRequest' }});
    const data = await res.json();
    if(data.type==='product' && data.product){
      addToCart(data.product);
      document.getElementById('scan-status').innerHTML = `<i class="fas fa-check-circle text-green-600 mr-1"></i> Produk ditambahkan!`;
    } else if(data.type==='member' && data.member){
      member = data.member;
      document.getElementById('member-display').innerHTML = `${data.member.name} <span class="text-xs">(${data.member.points} poin)</span>`;
      cart = cart.map(i => ({...i, isMember: true}));
      updateCartUI();
      document.getElementById('scan-status').innerHTML = `<i class="fas fa-check-circle text-green-600 mr-1"></i> Member dipilih!`;
    } else {
      document.getElementById('scan-status').innerHTML = `<i class="fas fa-exclamation-triangle text-yellow-600 mr-1"></i> QR tidak dikenali`;
    }
  } catch(e){
    document.getElementById('scan-status').innerHTML = `<i class="fas fa-times-circle text-red-600 mr-1"></i> Error: ${e.message}`;
  }
  setTimeout(()=>{ document.getElementById('scan-status').textContent = 'Arahkan kamera ke QR code produk atau member'; }, 3000);
}

// Register Member Modal Handlers
const registerModal = document.getElementById('register-member-modal');
const registerForm = document.getElementById('register-member-form');

document.getElementById('btn-register-member').addEventListener('click', ()=>{
  memberModal.classList.add('hidden');
  registerModal.classList.remove('hidden');
});

document.getElementById('close-register-modal').addEventListener('click', ()=>{
  registerModal.classList.add('hidden');
  registerForm.reset();
});

document.getElementById('cancel-register').addEventListener('click', ()=>{
  registerModal.classList.add('hidden');
  registerForm.reset();
});

registerForm.addEventListener('submit', async (e)=>{
  e.preventDefault();
  const formData = new FormData(registerForm);
  const payload = {
    name: formData.get('name'),
    phone: formData.get('phone'),
    password: formData.get('password')||null
  };
  try {
    const res = await fetch('{{ route("kasir.register-member") }}', {
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken},
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if(data.success){
      const message = `
        <div class="space-y-2 text-left">
          <div class="flex items-center justify-between border-b pb-2" style="border-color: var(--cream);">
            <span style="color: var(--dark-gray); opacity: 0.7;">Nama:</span>
            <span class="font-semibold" style="color: var(--dark-gray);">${data.member.name}</span>
          </div>
          <div class="flex items-center justify-between border-b pb-2" style="border-color: var(--cream);">
            <span style="color: var(--dark-gray); opacity: 0.7;">Kode Member:</span>
            <span class="font-semibold" style="color: var(--dark-gray);">${data.member.member_code}</span>
          </div>
          <div class="flex items-center justify-between border-b pb-2" style="border-color: var(--cream);">
            <span style="color: var(--dark-gray); opacity: 0.7;">Telepon:</span>
            <span class="font-semibold" style="color: var(--dark-gray);">${data.member.phone}</span>
          </div>
          <div class="flex items-center justify-between">
            <span style="color: var(--dark-gray); opacity: 0.7;">Password:</span>
            <span class="font-mono font-semibold" style="color: var(--dark-gray);">${payload.password||'123456'}</span>
          </div>
        </div>
      `;
      await showNotification('✅ Member Berhasil Didaftarkan!', message, 'user-check', '#10B981');
      
      member = data.member;
      document.getElementById('member-display').innerHTML = `${data.member.name} <span class="text-xs">(0 poin)</span>`;
      cart = cart.map(i => ({...i, isMember: true}));
      updateCartUI();
      registerModal.classList.add('hidden');
      registerForm.reset();
    } else {
      await showNotification('❌ Pendaftaran Gagal', 
        `<p style="color: #EF4444;">${data.message||'Terjadi kesalahan'}</p>`, 
        'times-circle', '#EF4444');
    }
  } catch(e){
    await showNotification('❌ Error', 
      `<p style="color: #EF4444;">${e.message}</p>`, 
      'exclamation-triangle', '#F59E0B');
  }
});

// Initialize - Only load once when DOM is ready
let initialized = false;
if(document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', function() {
    if(!initialized) {
      initialized = true;
      loadProducts();
    }
  });
} else {
  if(!initialized) {
    initialized = true;
    loadProducts();
  }
}

// Clean up scanner when page is about to be closed/navigated away
window.addEventListener('beforeunload', function() {
    if (html5QrCode) {
        try {
            if (html5QrCode.isScanning) {
                html5QrCode.stop();
            }
            html5QrCode.clear();
            console.log('POS Scanner cleaned up before page unload');
        } catch(e) {
            console.error('Error cleaning up POS scanner:', e);
        }
    }
});

// Also clean up when navigating away
window.addEventListener('pagehide', function() {
    if (html5QrCode) {
        try {
            if (html5QrCode.isScanning) {
                html5QrCode.stop();
            }
            html5QrCode.clear();
        } catch(e) {}
    }
});

// Receipt Modal Functions
async function loadReceipt(transactionId) {
    try {
        const response = await fetch(`/kasir/transactions/${transactionId}/print`);
        if (!response.ok) throw new Error('Failed to load receipt');
        
        const html = await response.text();
        
        // Extract body content from the receipt page
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const bodyContent = doc.querySelector('body').innerHTML;
        
        // Remove the buttons from receipt (we have our own)
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = bodyContent;
        const buttons = tempDiv.querySelectorAll('button, .no-print');
        buttons.forEach(btn => btn.remove());
        
        // Load into modal
        document.getElementById('receipt-content').innerHTML = tempDiv.innerHTML;
        
        // Show modal
        document.getElementById('receipt-modal').classList.remove('hidden');
        
    } catch (error) {
        console.error('Error loading receipt:', error);
        await showNotification('❌ Error', 
            '<p style="color: #EF4444;">Gagal memuat struk</p>', 
            'exclamation-triangle', '#EF4444');
    }
}

// Close receipt modal
document.getElementById('close-receipt-modal').addEventListener('click', function() {
    document.getElementById('receipt-modal').classList.add('hidden');
});

// Print receipt
document.getElementById('btn-print-receipt').addEventListener('click', function() {
    // Create a temporary iframe for printing
    const printFrame = document.createElement('iframe');
    printFrame.style.position = 'absolute';
    printFrame.style.width = '0';
    printFrame.style.height = '0';
    printFrame.style.border = 'none';
    
    document.body.appendChild(printFrame);
    
    const receiptContent = document.getElementById('receipt-content').innerHTML;
    const printDocument = printFrame.contentWindow.document;
    
    printDocument.open();
    printDocument.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Struk Transaksi</title>
            <style>
                @media print {
                    body { margin: 0; padding: 0; }
                    * { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                }
                body {
                    font-family: 'Courier New', monospace;
                    width: 80mm;
                    margin: 0 auto;
                    padding: 5mm;
                }
            </style>
        </head>
        <body>
            ${receiptContent}
        </body>
        </html>
    `);
    printDocument.close();
    
    // Wait for content to load then print
    printFrame.contentWindow.onload = function() {
        setTimeout(() => {
            printFrame.contentWindow.print();
            setTimeout(() => {
                document.body.removeChild(printFrame);
            }, 100);
        }, 250);
    };
});

// New transaction button
document.getElementById('btn-new-transaction').addEventListener('click', function() {
    document.getElementById('receipt-modal').classList.add('hidden');
});
</script>
</body>
</html>