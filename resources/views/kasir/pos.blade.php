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
        .product-card { @apply bg-white rounded-lg shadow hover:shadow-lg transition cursor-pointer overflow-hidden; }
        .product-card:hover { transform: translateY(-2px); }
        .product-img { @apply w-full h-32 object-cover bg-gray-100; }
        .cart-item-row { @apply flex items-center justify-between border-b py-2; }
        .btn-action { @apply px-4 py-3 rounded-lg font-semibold transition; }
        #reader { border-radius: 8px; overflow: hidden; max-height: 250px; }
    </style>
</head>
<body class="bg-gray-50 h-screen overflow-hidden flex flex-col">
    <!-- Top Bar -->
    <div class="bg-blue-600 text-white shadow-lg">
        <div class="px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-cash-register text-2xl"></i>
                <div>
                    <h1 class="text-xl font-bold">Point of Sale</h1>
                    <p class="text-xs opacity-75">Kasir: {{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('kasir.dashboard') }}" class="px-3 py-1.5 bg-white bg-opacity-20 hover:bg-opacity-30 rounded text-sm">
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
            <!-- Category Tabs & Search -->
            <div class="p-4 border-b bg-gray-50">
                <div class="flex items-center space-x-2 mb-3">
                    <input id="search-input" type="text" placeholder="🔍 Cari produk (nama, SKU, barcode)..." 
                           class="flex-1 border-2 border-gray-300 rounded-lg px-3 py-2 focus:border-blue-500 focus:outline-none" />
                    <button id="btn-scan-toggle" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-qrcode"></i>
                    </button>
                </div>
                <!-- QR Scanner (hidden by default) -->
                <div id="scanner-panel" class="hidden mt-3 bg-white p-3 rounded-lg border">
                    <div id="reader"></div>
                    <p id="scan-status" class="text-xs text-gray-500 mt-2 text-center">Arahkan kamera ke QR code produk atau member</p>
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
            <div class="p-4 bg-blue-50 border-b">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-gray-800">Transaksi Saat Ini</h2>
                        <p class="text-xs text-gray-500">Kasir: {{ auth()->user()->name }}</p>
                    </div>
                    <span id="cart-count" class="bg-blue-600 text-white text-xs px-3 py-1 rounded-full font-bold">0</span>
                </div>
            </div>

            <!-- Member Info -->
            <div class="p-3 bg-purple-50 border-b">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center text-purple-700">
                        <i class="fas fa-user-tag mr-2"></i>
                        <span id="member-display">Umum</span>
                    </div>
                    <button id="btn-select-member" class="text-xs text-purple-600 hover:text-purple-800">
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
            <div class="border-t bg-gray-50 p-4 space-y-3">
                <!-- Summary -->
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span id="subtotal" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Diskon Member:</span>
                        <span id="member-discount" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Pajak (11%):</span>
                        <span id="tax" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-purple-600">
                        <span>Potongan Poin:</span>
                        <span id="points-discount" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-gray-800 pt-2 border-t">
                        <span>TOTAL:</span>
                        <span id="total">Rp 0</span>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Metode Pembayaran:</label>
                    <select id="payment-method" class="w-full border rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        <option value="cash">💵 Tunai</option>
                        <option value="debit">💳 Debit</option>
                        <option value="credit">💳 Kredit</option>
                        <option value="e-wallet">📱 E-Wallet</option>
                    </select>
                </div>

                <!-- Payment Amount -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Bayar:</label>
                    <input id="payment-amount" type="number" step="1000" placeholder="0" 
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" />
                    <div class="text-xs text-gray-600 mt-1">Kembalian: <span id="change" class="font-bold text-green-600">Rp 0</span></div>
                </div>

                <!-- Points -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Gunakan Poin:</label>
                    <input id="points-used" type="number" min="0" step="1" value="0" placeholder="0" 
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:border-purple-500 focus:outline-none" />
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-3 gap-2 pt-2">
                    <button id="btn-hold" class="btn-action bg-yellow-500 hover:bg-yellow-600 text-white text-xs">
                        <i class="fas fa-pause"></i> Hold
                    </button>
                    <button id="btn-cancel" class="btn-action bg-red-500 hover:bg-red-600 text-white text-xs">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button id="btn-pay" class="btn-action bg-green-600 hover:bg-green-700 text-white text-xs">
                        <i class="fas fa-check"></i> Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Member Selection -->
    <div id="member-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md m-4">
            <div class="p-4 border-b flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Pilih Member</h3>
                <button id="close-member-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <input id="member-search-input" type="text" placeholder="🔍 Cari member..." 
                       class="w-full border rounded-lg px-3 py-2 mb-3 focus:border-purple-500 focus:outline-none" />
                <ul id="member-search-results" class="max-h-64 overflow-y-auto space-y-2 mb-3"></ul>
                <button id="btn-register-member" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Member Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Register New Member -->
    <div id="register-member-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md m-4">
            <div class="p-4 border-b flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Daftar Member Baru</h3>
                <button id="close-register-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="register-member-form" class="p-4 space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
                    <input type="text" name="name" required class="w-full border rounded-lg px-3 py-2 focus:border-green-500 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-600">*</span></label>
                    <input type="email" name="email" required class="w-full border rounded-lg px-3 py-2 focus:border-green-500 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telepon <span class="text-red-600">*</span></label>
                    <input type="text" name="phone" required class="w-full border rounded-lg px-3 py-2 focus:border-green-500 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-600">*</span></label>
                    <textarea name="address" required rows="2" class="w-full border rounded-lg px-3 py-2 focus:border-green-500 focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password (opsional)</label>
                    <input type="password" name="password" minlength="8" placeholder="Default: member123" class="w-full border rounded-lg px-3 py-2 focus:border-green-500 focus:outline-none" />
                    <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menggunakan password default</p>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button type="button" id="cancel-register" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

<audio id="beep" src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSl+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBSh+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBSh+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBSh+zPLaizsKGGS57OihUBELTKXh8bllHgU2jdXzzn0vBQ==" preload="auto"></audio>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const beep = document.getElementById('beep');
let cart = [];
let member = null;
let allProducts = [];
let taxRate = {{ isset($taxRate) ? (float) $taxRate : 11 }};
let globalMemberDiscPercent = {{ isset($globalMemberDiscPercent) ? (float) $globalMemberDiscPercent : 5 }};

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
scanToggle.addEventListener('click', ()=>{
  scannerActive = !scannerActive;
  if(scannerActive){
    scannerPanel.classList.remove('hidden');
    startScanner();
  } else {
    scannerPanel.classList.add('hidden');
    stopScanner();
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
      li.className = 'p-3 cursor-pointer hover:bg-purple-50 rounded border hover:border-purple-300 transition';
      li.innerHTML = `
        <div class="flex items-center justify-between">
          <div>
            <div class="font-semibold text-sm text-gray-800">${m.name}</div>
            <div class="text-xs text-gray-500">${m.member_code||'-'} · ${m.phone||''}</div>
          </div>
          <div class="text-right">
            <div class="text-xs text-gray-500">Poin</div>
            <div class="font-bold text-purple-600 text-sm">${m.points}</div>
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
      alert(`Transaksi berhasil! Kembalian: ${formatRupiah(data.change||0)}`);
      cart=[]; member=null; updateCartUI();
      document.getElementById('member-display').textContent='Umum';
      document.getElementById('payment-amount').value='';
      document.getElementById('points-used').value='0';
    } else {
      alert('Transaksi gagal: '+(data.message||'Unknown error'));
    }
  } catch(e){ alert('Error: '+e.message); }
});

// QR Scanner
let html5QrCode;
function startScanner(){
  if(!html5QrCode && typeof Html5Qrcode !== 'undefined'){
    html5QrCode = new Html5Qrcode("reader");
  }
  if(html5QrCode){
    html5QrCode.start(
      { facingMode: "environment" },
      { fps: 10, qrbox: 250 },
      onScanSuccess
    ).catch(err => {
      document.getElementById('scan-status').textContent = 'Gagal membuka kamera';
    });
  }
}
function stopScanner(){
  if(html5QrCode){ html5QrCode.stop().catch(()=>{}); }
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
    email: formData.get('email'),
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
      alert(`Member berhasil didaftarkan!\nKode: ${data.member.member_code}\nEmail: ${data.member.email}\nPassword: ${payload.password||'member123'}`);
      member = data.member;
      document.getElementById('member-display').innerHTML = `${data.member.name} <span class="text-xs">(0 poin)</span>`;
      cart = cart.map(i => ({...i, isMember: true}));
      updateCartUI();
      registerModal.classList.add('hidden');
      registerForm.reset();
    } else {
      alert('Pendaftaran gagal: '+(data.message||'Unknown error'));
    }
  } catch(e){
    alert('Error: '+e.message);
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
</script>
</body>
</html>