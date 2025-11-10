@extends('layouts.modern')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="fade-in">
    <!-- Page Header -->
    <div class="card" style="background: linear-gradient(135deg, #FF6F00 0%, #F57C00 100%); margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; color: white;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; margin-bottom: 8px;">
                    <i class="fas fa-history mr-3"></i>
                    Riwayat Transaksi
                </h1>
                <p style="opacity: 0.9; font-size: 15px;">Lihat semua transaksi pembelanjaan Anda</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 14px; opacity: 0.9;">Total Transaksi</div>
                <div style="font-size: 40px; font-weight: 700; margin-top: 4px;">{{ $transactions->total() }}</div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card" style="margin-bottom: 24px;">
        <form method="GET" action="{{ route('member.transactions') }}" id="filterForm">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end;">
                <!-- Search -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        <i class="fas fa-search" style="color: #6B7280; margin-right: 6px;"></i>
                        Cari Transaksi
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #E5E7EB; border-radius: 10px; font-size: 14px; transition: all 0.2s;"
                           placeholder="Kode transaksi..."
                           onfocus="this.style.borderColor='#8B5CF6'; this.style.boxShadow='0 0 0 3px rgba(139, 92, 246, 0.1)';"
                           onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';" />
                </div>

                <!-- Date From -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        <i class="fas fa-calendar" style="color: #6B7280; margin-right: 6px;"></i>
                        Dari Tanggal
                    </label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #E5E7EB; border-radius: 10px; font-size: 14px; transition: all 0.2s;"
                           onfocus="this.style.borderColor='#8B5CF6'; this.style.boxShadow='0 0 0 3px rgba(139, 92, 246, 0.1)';"
                           onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';" />
                </div>

                <!-- Date To -->
                <div>
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;">
                        <i class="fas fa-calendar" style="color: #6B7280; margin-right: 6px;"></i>
                        Sampai Tanggal
                    </label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           style="width: 100%; padding: 12px 16px; border: 2px solid #E5E7EB; border-radius: 10px; font-size: 14px; transition: all 0.2s;"
                           onfocus="this.style.borderColor='#8B5CF6'; this.style.boxShadow='0 0 0 3px rgba(139, 92, 246, 0.1)';"
                           onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none';" />
                </div>

                <!-- Buttons -->
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 12px;">
                        <i class="fas fa-filter"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('member.transactions') }}" class="btn btn-secondary" style="padding: 12px;">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Transactions List -->
    @forelse($transactions as $transaction)
    <div class="card card-hover" style="margin-bottom: 16px;">
        <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 20px; align-items: start;">
            <!-- Left: Date Badge -->
            <div style="text-align: center; padding: 12px; background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%); border-radius: 12px; color: white; min-width: 80px;">
                <div style="font-size: 24px; font-weight: 700; line-height: 1;">{{ $transaction->transaction_date->format('d') }}</div>
                <div style="font-size: 12px; opacity: 0.9; margin-top: 2px;">{{ $transaction->transaction_date->format('M Y') }}</div>
                <div style="font-size: 11px; opacity: 0.8; margin-top: 4px;">{{ $transaction->transaction_date->format('H:i') }}</div>
            </div>

            <!-- Middle: Transaction Info -->
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                    <div>
                        <div style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 4px;">
                            {{ $transaction->transaction_code }}
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6B7280;">
                                <i class="fas fa-user"></i>
                                <span>{{ $transaction->cashier->name ?? '-' }}</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 6px; font-size: 13px; color: #6B7280;">
                                <i class="fas fa-box"></i>
                                <span>{{ $transaction->details->count() }} item</span>
                            </div>
                            @if($transaction->points_earned > 0)
                            <span class="badge badge-warning">
                                <i class="fas fa-plus mr-1"></i>{{ $transaction->points_earned }} poin
                            </span>
                            @endif
                        </div>
                    </div>
                    <span class="badge badge-success" style="padding: 6px 12px;">
                        <i class="fas fa-check-circle mr-1"></i>Selesai
                    </span>
                </div>

                <!-- Items Preview -->
                <div style="background: #F9FAFB; padding: 12px; border-radius: 10px; margin-bottom: 12px;">
                    <div style="font-size: 13px; font-weight: 600; color: #6B7280; margin-bottom: 8px;">Barang yang dibeli:</div>
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        @foreach($transaction->details->take(3) as $detail)
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px;">
                            <div style="color: #374151;">
                                <span style="font-weight: 600;">{{ $detail->quantity }}x</span>
                                <span>{{ $detail->product->name ?? 'Produk tidak tersedia' }}</span>
                            </div>
                            <div style="font-weight: 600; color: #111827;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                        </div>
                        @endforeach
                        @if($transaction->details->count() > 3)
                        <div style="font-size: 13px; color: #6B7280; font-style: italic;">
                            + {{ $transaction->details->count() - 3 }} item lainnya
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Info -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; padding: 12px; background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); border-radius: 10px;">
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 2px;">Subtotal</div>
                        <div style="font-size: 15px; font-weight: 600; color: #374151;">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</div>
                    </div>
                    @if($transaction->discount > 0)
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 2px;">Diskon</div>
                        <div style="font-size: 15px; font-weight: 600; color: #10B981;">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</div>
                    </div>
                    @endif
                    @if($transaction->tax > 0)
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 2px;">Pajak</div>
                        <div style="font-size: 15px; font-weight: 600; color: #374151;">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</div>
                    </div>
                    @endif
                    <div>
                        <div style="font-size: 12px; color: #6B7280; margin-bottom: 2px;">Total Bayar</div>
                        <div style="font-size: 18px; font-weight: 700; color: #4F46E5;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Right: Action Button -->
            <a href="{{ route('member.transaction-detail', $transaction->id) }}" class="btn btn-primary" style="padding: 12px 20px; white-space: nowrap;">
                <i class="fas fa-eye mr-2"></i>
                <span>Detail</span>
            </a>
        </div>
    </div>
    @empty
    <div class="card" style="padding: 80px 20px;">
        <div style="text-align: center; color: #6B7280;">
            <i class="fas fa-shopping-bag" style="font-size: 80px; color: #D1D5DB; margin-bottom: 24px; display: block;"></i>
            <h3 style="font-size: 24px; font-weight: 700; color: #374151; margin-bottom: 8px;">Belum Ada Transaksi</h3>
            <p style="font-size: 15px; margin-bottom: 24px;">Anda belum melakukan transaksi pembelanjaan</p>
            <a href="{{ route('member.products') }}" class="btn btn-primary" style="padding: 14px 28px;">
                <i class="fas fa-shopping-cart mr-2"></i>
                <span>Mulai Belanja</span>
            </a>
        </div>
    </div>
    @endforelse

    <!-- Pagination -->
    @if($transactions->hasPages())
    <div style="margin-top: 32px; display: flex; justify-content: center;">
        <div style="background: white; border-radius: 12px; padding: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
            {{ $transactions->links() }}
        </div>
    </div>
    @endif

    <!-- Summary Card -->
    <div class="card" style="margin-top: 32px; background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%);">
        <div style="display: flex; justify-content: space-around; align-items: center; flex-wrap: wrap; gap: 24px; text-align: center;">
            <div>
                <div style="font-size: 14px; color: #6B7280; margin-bottom: 4px;">Total Transaksi</div>
                <div style="font-size: 32px; font-weight: 700; color: #111827;">{{ $transactions->total() }}</div>
            </div>
            <div style="width: 2px; height: 60px; background: #D1D5DB;"></div>
            <div>
                <div style="font-size: 14px; color: #6B7280; margin-bottom: 4px;">Total Belanja</div>
                <div style="font-size: 32px; font-weight: 700; color: #10B981;">Rp {{ number_format($transactions->sum('total'), 0, ',', '.') }}</div>
            </div>
            <div style="width: 2px; height: 60px; background: #D1D5DB;"></div>
            <div>
                <div style="font-size: 14px; color: #6B7280; margin-bottom: 4px;">Poin Terkumpul</div>
                <div style="font-size: 32px; font-weight: 700; color: #F59E0B;">{{ number_format($transactions->sum('points_earned'), 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Pagination Styling */
.pagination {
    display: flex;
    gap: 4px;
    align-items: center;
}

.pagination a,
.pagination span {
    padding: 8px 14px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s;
}

.pagination a {
    color: #6B7280;
    text-decoration: none;
}

.pagination a:hover {
    background: #F3F4F6;
    color: #111827;
}

.pagination .active span {
    background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
    color: white;
}

.pagination .disabled span {
    color: #D1D5DB;
}
</style>
</div>
@endsection
