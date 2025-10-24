@extends('layouts.modern')

@section('title', 'Point of Sale')

@section('content')
<div class="content-header">
    <h1>Point of Sale (POS)</h1>
    <p>Proses transaksi penjualan</p>
</div>

<div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem; min-height: calc(100vh - 200px);">
    <!-- Left Side - Product Selection -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Search & Scanner -->
        <div class="modern-card">
            <div style="display: flex; gap: 1rem;">
                <div style="flex: 1; position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6b7280;"></i>
                    <input type="text" id="productSearch" placeholder="Cari produk atau scan barcode..." 
                           style="width: 100%; padding: 0.875rem 1rem 0.875rem 3rem; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 1rem;">
                </div>
                <button onclick="openQRScanner()" class="btn-primary">
                    <i class="fas fa-qrcode"></i> Scan QR
                </button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="modern-card" style="flex: 1; overflow-y: auto; max-height: calc(100vh - 350px);">
            <div id="productGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                @foreach($categories as $category)
                    @foreach($category->products->where('is_active', true)->where('stock', '>', 0) as $product)
                    <div class="product-card" onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->member_price ?? $product->price }}, '{{ $product->image ?? '' }}', {{ $product->stock }})" 
                         style="cursor: pointer; background: white; border: 2px solid #e5e7eb; border-radius: 12px; padding: 1rem; text-align: center; transition: all 0.3s; hover:transform: translateY(-2px); hover:box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                             style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px; margin-bottom: 0.5rem;">
                        @else
                        <div style="width: 100%; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; margin-bottom: 0.5rem;">
                            <i class="fas fa-box" style="font-size: 2rem;"></i>
                        </div>
                        @endif
                        <div style="font-weight: 600; font-size: 0.9375rem; margin-bottom: 0.25rem; color: #1f2937;">{{ Str::limit($product->name, 20) }}</div>
                        <div style="color: #059669; font-weight: 700;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Stok: {{ $product->stock }}</div>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Side - Cart & Checkout -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Member Info -->
        <div class="modern-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0; font-size: 1rem;"><i class="fas fa-user"></i> Member</h3>
                <button onclick="searchMember()" class="btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
            <div id="memberInfo" style="padding: 1rem; background: #f9fafb; border-radius: 8px; text-align: center;">
                <p style="margin: 0; color: #6b7280; font-size: 0.9375rem;">Belum ada member dipilih</p>
                <button onclick="searchMember()" style="margin-top: 0.5rem; background: none; border: 1px solid #e5e7eb; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; font-size: 0.875rem;">
                    <i class="fas fa-plus"></i> Pilih Member
                </button>
            </div>
            <input type="hidden" id="selectedMemberId" value="">
        </div>

        <!-- Cart Items -->
        <div class="modern-card" style="flex: 1;">
            <h3 style="margin: 0 0 1rem 0; font-size: 1.125rem;"><i class="fas fa-shopping-cart"></i> Keranjang</h3>
            <div id="cartItems" style="max-height: 300px; overflow-y: auto;">
                <div style="text-align: center; padding: 3rem 1rem; color: #6b7280;">
                    <i class="fas fa-shopping-cart" style="font-size: 3rem; opacity: 0.3; display: block; margin-bottom: 1rem;"></i>
                    <p>Keranjang masih kosong</p>
                </div>
            </div>
        </div>

        <!-- Totals -->
        <div class="modern-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <span style="font-size: 1.125rem;">Subtotal</span>
                <span id="subtotal" style="font-size: 1.5rem; font-weight: 700;">Rp 0</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.2);">
                <span>Diskon Member</span>
                <span id="discount" style="font-weight: 600;">- Rp 0</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <span style="font-size: 1.25rem; font-weight: 600;">TOTAL</span>
                <span id="total" style="font-size: 2rem; font-weight: 700;">Rp 0</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                <button onclick="holdTransaction()" class="btn-secondary" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-pause"></i> Hold
                </button>
                <button onclick="clearCart()" class="btn-secondary" style="background: rgba(239,68,68,0.2); color: white; border: 1px solid rgba(239,68,68,0.3);">
                    <i class="fas fa-trash"></i> Clear
                </button>
            </div>
            <button onclick="checkout()" id="checkoutBtn" class="btn-primary" style="width: 100%; margin-top: 0.75rem; background: white; color: #667eea; font-size: 1.125rem; font-weight: 700;" disabled>
                <i class="fas fa-cash-register"></i> BAYAR
            </button>
        </div>
    </div>
</div>

<script>
let cart = [];
let selectedMember = null;

function addToCart(id, name, price, memberPrice, image, stock) {
    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
        if (existingItem.qty < stock) {
            existingItem.qty++;
        } else {
            alert('Stok tidak mencukupi!');
            return;
        }
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            memberPrice: memberPrice,
            image: image,
            stock: stock,
            qty: 1
        });
    }
    
    updateCart();
}

function updateCart() {
    const cartItemsDiv = document.getElementById('cartItems');
    const isMember = selectedMember !== null;
    
    if (cart.length === 0) {
        cartItemsDiv.innerHTML = `
            <div style="text-align: center; padding: 3rem 1rem; color: #6b7280;">
                <i class="fas fa-shopping-cart" style="font-size: 3rem; opacity: 0.3; display: block; margin-bottom: 1rem;"></i>
                <p>Keranjang masih kosong</p>
            </div>
        `;
        document.getElementById('checkoutBtn').disabled = true;
    } else {
        let html = '';
        let subtotal = 0;
        let discount = 0;
        
        cart.forEach((item, index) => {
            const itemPrice = isMember ? item.memberPrice : item.price;
            const itemTotal = itemPrice * item.qty;
            subtotal += item.price * item.qty;
            
            html += `
                <div style="display: flex; gap: 0.75rem; padding: 0.75rem; border-bottom: 1px solid #e5e7eb;">
                    <div style="width: 50px; height: 50px; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-box" style="color: #9ca3af;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 0.9375rem; color: #1f2937;">${item.name}</div>
                        <div style="color: #059669; font-weight: 600; margin-top: 0.25rem;">Rp ${itemPrice.toLocaleString('id-ID')}</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <button onclick="decreaseQty(${index})" style="width: 28px; height: 28px; border: none; background: #fee2e2; color: #dc2626; border-radius: 6px; cursor: pointer;">-</button>
                        <span style="font-weight: 700; width: 30px; text-align: center;">${item.qty}</span>
                        <button onclick="increaseQty(${index})" style="width: 28px; height: 28px; border: none; background: #dcfce7; color: #059669; border-radius: 6px; cursor: pointer;">+</button>
                        <button onclick="removeItem(${index})" style="width: 28px; height: 28px; border: none; background: #fee2e2; color: #dc2626; border-radius: 6px; cursor: pointer; margin-left: 0.25rem;"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            `;
        });
        
        if (isMember) {
            const memberTotal = cart.reduce((sum, item) => sum + (item.memberPrice * item.qty), 0);
            discount = subtotal - memberTotal;
        }
        
        const total = subtotal - discount;
        
        cartItemsDiv.innerHTML = html;
        document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('discount').textContent = '- Rp ' + discount.toLocaleString('id-ID');
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('checkoutBtn').disabled = false;
    }
}

function increaseQty(index) {
    if (cart[index].qty < cart[index].stock) {
        cart[index].qty++;
        updateCart();
    } else {
        alert('Stok tidak mencukupi!');
    }
}

function decreaseQty(index) {
    if (cart[index].qty > 1) {
        cart[index].qty--;
        updateCart();
    }
}

function removeItem(index) {
    cart.splice(index, 1);
    updateCart();
}

function clearCart() {
    if (confirm('Clear semua item di keranjang?')) {
        cart = [];
        updateCart();
    }
}

function searchMember() {
    alert('Fitur pencarian member - akan membuka modal search');
}

function openQRScanner() {
    alert('Fitur QR Scanner - akan membuka camera scanner');
}

function holdTransaction() {
    if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }
    alert('Transaksi di-hold - akan disimpan untuk nanti');
}

function checkout() {
    if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }
    alert('Proses checkout - akan membuka modal pembayaran');
}

// Product search
document.getElementById('productSearch').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const name = product.textContent.toLowerCase();
        product.style.display = name.includes(search) ? 'block' : 'none';
    });
});
</script>

<style>
.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    border-color: #4f46e5;
}
</style>
@endsection
