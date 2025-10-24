<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Setting;
use App\Models\Promotion;
use App\Models\User;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KasirController extends Controller
{
    /**
     * Kasir Dashboard
     */
    public function dashboard()
    {
        $cashier = auth()->user();

        $todayTransactions = Transaction::where('cashier_id', $cashier->id)
            ->whereDate('transaction_date', today())
            ->where('status', 'completed');

        $data = [
            'today_transactions' => $todayTransactions->count(),
            'today_sales' => $todayTransactions->sum('total'),
            'active_members' => Transaction::where('cashier_id', $cashier->id)
                ->whereDate('transaction_date', today())
                ->where('status', 'completed')
                ->whereNotNull('member_id')
                ->distinct('member_id')
                ->count(),
            'avg_transaction' => $todayTransactions->count() > 0 
                ? $todayTransactions->sum('total') / $todayTransactions->count() 
                : 0,
            'recent_transactions' => Transaction::where('cashier_id', $cashier->id)
                ->with(['member.user'])
                ->latest('transaction_date')
                ->take(10)
                ->get(),
        ];

        return view('kasir.dashboard-modern', $data);
    }

    /**
     * POS Interface
     */
    public function pos()
    {
        $categories = \App\Models\Category::with(['products' => function($query) {
            $query->where('is_active', true)->where('stock', '>', 0);
        }])->where('is_active', true)->get();
        
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->with('category')
            ->get();

        $taxRate = Setting::getValue('tax_rate', 11);
        $globalMemberDiscPercent = (float) Setting::getValue('global_member_discount_percent', 5);

        return view('kasir.pos-modern', compact('categories', 'products', 'taxRate', 'globalMemberDiscPercent'));
    }

    /**
     * Scan QR Code (Product or Member)
     */
    public function scanQR(Request $request)
    {
        // Support both POST (qr_data) and GET (code) parameters
        $qrData = $request->input('qr_data') ?? $request->input('code');
        
        if (!$qrData) {
            return response()->json([
                'success' => false,
                'message' => 'QR data or code parameter required',
            ], 400);
        }

        try {
            $data = json_decode($qrData, true);

            if (!$data || !isset($data['type'])) {
                // Try to find by barcode/sku/member_code if not JSON
                $product = Product::where('barcode', $qrData)
                    ->orWhere('sku', $qrData)
                    ->where('is_active', true)
                    ->first();
                
                if ($product) {
                    return response()->json([
                        'success' => true,
                        'type' => 'product',
                        'product' => [
                            'id' => $product->id,
                            'name' => $product->name,
                            'sku' => $product->sku,
                            'barcode' => $product->barcode,
                            'price' => $product->price,
                            'member_price' => $product->member_price,
                            'stock' => $product->stock,
                            'image' => $product->image,
                        ],
                    ]);
                }
                
                $member = Member::with('user')->where('member_code', $qrData)->first();
                if ($member && $member->status === 'active') {
                    return response()->json([
                        'success' => true,
                        'type' => 'member',
                        'member' => [
                            'id' => $member->id,
                            'member_code' => $member->member_code,
                            'name' => $member->user->name,
                            'points' => $member->points,
                            'phone' => $member->user->phone ?? '',
                        ],
                    ]);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR Code',
                ], 400);
            }

            if ($data['type'] === 'product') {
                $product = Product::find($data['id']);

                if (!$product || !$product->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product not found or inactive',
                    ], 404);
                }

                if ($product->stock <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product out of stock',
                    ], 400);
                }

                return response()->json([
                    'success' => true,
                    'type' => 'product',
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'barcode' => $product->barcode,
                        'price' => $product->price,
                        'member_price' => $product->member_price,
                        'stock' => $product->stock,
                        'image' => $product->image,
                    ],
                ]);
            } elseif ($data['type'] === 'member') {
                $member = Member::with('user')->find($data['id']);

                if (!$member || $member->status !== 'active') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Member not found or inactive',
                    ], 404);
                }

                return response()->json([
                    'success' => true,
                    'type' => 'member',
                    'member' => [
                        'id' => $member->id,
                        'member_code' => $member->member_code,
                        'name' => $member->user->name,
                        'points' => $member->points,
                        'phone' => $member->user->phone ?? '',
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Unknown QR Code type',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing QR Code: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search Product by SKU or Name
     */
    public function searchProduct(Request $request)
    {
        $query = $request->input('query', '');

        $productsQuery = Product::where('is_active', true)
            ->where('stock', '>', 0);

        // If query is provided, filter by search terms
        if (!empty($query)) {
            $productsQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('sku', 'like', '%' . $query . '%')
                    ->orWhere('barcode', 'like', '%' . $query . '%');
            });
        }

        $products = $productsQuery->with('category')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'unit' => $product->unit,
                    'price' => $product->price,
                    'member_price' => $product->member_price,
                    'stock' => $product->stock,
                    'image' => $product->image,
                    'category' => $product->category ? $product->category->name : null,
                ];
            });

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    /**
     * Search member by code/name/email/phone
     */
    public function searchMember(Request $request)
    {
        $query = trim((string) $request->get('query', ''));
        if ($query === '') {
            return response()->json(['success' => true, 'members' => []]);
        }

        $members = Member::with('user')
            ->where('status', 'active')
            ->where(function($q) use ($query){
                $q->where('member_code', 'like', "%{$query}%")
                  ->orWhereHas('user', function($uq) use ($query){
                      $uq->where('name', 'like', "%{$query}%")
                         ->orWhere('email', 'like', "%{$query}%")
                         ->orWhere('phone', 'like', "%{$query}%");
                  });
            })
            ->take(20)
            ->get()
            ->map(function($m){
                return [
                    'id' => $m->id,
                    'member_code' => $m->member_code,
                    'name' => $m->user->name ?? '',
                    'email' => $m->user->email ?? '',
                    'phone' => $m->user->phone ?? '',
                    'points' => (int) $m->points,
                ];
            });

        return response()->json(['success' => true, 'members' => $members]);
    }

    /**
     * Get Product Details
     */
    public function getProduct($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product || !$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }

    /**
     * Process Transaction
     */
    public function processTransaction(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,debit,credit,e-wallet',
            'payment_amount' => 'required|numeric|min:0',
            'points_used' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $member = null;
            $isMember = false;

            if ($validated['member_id']) {
                $member = Member::find($validated['member_id']);
                if ($member && $member->status === 'active') {
                    $isMember = true;
                }
            }

            // Calculate subtotal
            $subtotal = 0;
            $memberDiscount = 0;
            $promoDiscount = 0;

            $globalMemberDiscPercent = (float) Setting::getValue('global_member_discount_percent', 5);

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);

                // Check stock
                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$product->name}",
                    ], 400);
                }

                // Calculate price with member price or global member discount fallback
                $price = $product->price;
                if ($isMember) {
                    if ($product->member_price && $product->member_price > 0) {
                        $memberDiscount += ($product->price - $product->member_price) * $item['quantity'];
                        $price = $product->member_price;
                    } else if ($globalMemberDiscPercent > 0) {
                        $discounted = $product->price * (1 - ($globalMemberDiscPercent/100));
                        $memberDiscount += ($product->price - $discounted) * $item['quantity'];
                        $price = $discounted;
                    }
                }
                $itemSubtotal = $price * $item['quantity'];
                $subtotal += $itemSubtotal;
            }

            // Apply member-only promotions (store-wide)
            if ($isMember) {
                $promos = Promotion::getActivePromotions(true);
                foreach ($promos as $promo) {
                    if ($promo->type === 'percentage') {
                        $promoDiscount += $subtotal * ((float)$promo->discount_value / 100);
                    } elseif ($promo->type === 'fixed') {
                        $promoDiscount += (float) $promo->discount_value;
                    }
                    // buy_x_get_y not applied (needs specific rules)
                }
                // Cap promo discount to subtotal
                $promoDiscount = min($promoDiscount, $subtotal);
            }

            // Calculate tax (after promotions)
            $taxRate = Setting::getValue('tax_rate', 11);
            $taxBase = max(0, $subtotal - $promoDiscount);
            $tax = $taxBase * ($taxRate / 100);

            // Apply points discount
            $pointsDiscount = 0;
            $pointsUsed = $validated['points_used'] ?? 0;

            if ($pointsUsed > 0 && $member) {
                if ($member->points < $pointsUsed) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient points',
                    ], 400);
                }

                $pointsDiscount = Transaction::pointsToRupiah($pointsUsed);
            }

            // Calculate total
            $total = $taxBase + $tax - $pointsDiscount;

            // Check payment amount
            if ($validated['payment_amount'] < $total) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient payment amount',
                ], 400);
            }

            $change = $validated['payment_amount'] - $total;

            // Calculate points earned
            $pointsEarned = $isMember ? Transaction::calculatePoints($total) : 0;

            // Create transaction
            $transaction = Transaction::create([
                'transaction_code' => Transaction::generateTransactionCode(),
                'cashier_id' => auth()->id(),
                'member_id' => $member?->id,
                'subtotal' => $subtotal,
                'discount' => $promoDiscount,
                'member_discount' => $memberDiscount,
                'tax' => $tax,
                'total' => $total,
                'points_earned' => $pointsEarned,
                'points_used' => $pointsUsed,
                'payment_method' => $validated['payment_method'],
                'payment_amount' => $validated['payment_amount'],
                'change_amount' => $change,
                'status' => 'completed',
                'transaction_date' => now(),
            ]);

            // Create transaction details and update stock
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $price = $isMember && $product->member_price ? $product->member_price : $product->price;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'discount' => 0,
                    'subtotal' => $price * $item['quantity'],
                ]);

                // Update stock
                $product->decrement('stock', $item['quantity']);
            }

            // Update member points
            if ($member) {
                if ($pointsUsed > 0) {
                    $member->usePoints($pointsUsed, $transaction->id, 'Points used for transaction');
                }

                if ($pointsEarned > 0) {
                    $member->addPoints($pointsEarned, $transaction->id, 'Points earned from transaction');
                }
            }

            DB::commit();

            // Load relationships for response
            $transaction->load(['details.product', 'member.user', 'cashier']);

            return response()->json([
                'success' => true,
                'message' => 'Transaction completed successfully',
                'transaction' => $transaction,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error processing transaction: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Transaction Details
     */
    public function getTransaction($id)
    {
        $transaction = Transaction::with(['details.product', 'member.user', 'cashier'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'transaction' => $transaction,
        ]);
    }

    /**
     * Print Receipt
     */
    public function printReceipt($id)
    {
        $transaction = Transaction::with(['details.product', 'member.user', 'cashier'])
            ->findOrFail($id);

        $settings = [
            'store_name' => Setting::getValue('store_name', 'Toko Saya'),
            'store_address' => Setting::getValue('store_address', ''),
            'store_phone' => Setting::getValue('store_phone', ''),
        ];

        return view('kasir.receipt', compact('transaction', 'settings'));
    }

    /**
     * Transaction History
     */
    public function transactionHistory(Request $request)
    {
        $query = Transaction::where('cashier_id', auth()->id())
                           ->with(['member.user', 'details', 'payments']);
        
        // Search by transaction code
        if ($request->filled('search')) {
            $query->where('transaction_code', 'like', '%' . $request->search . '%');
        }
        
        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('transaction_date', $request->date);
        }
        
        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->whereHas('payments', function($q) use ($request) {
                $q->where('payment_method', $request->payment_method);
            });
        }
        
        $transactions = $query->latest('transaction_date')->paginate(20);
        
        // Daily stats
        $daily_count = Transaction::where('cashier_id', auth()->id())
                                 ->whereDate('transaction_date', today())
                                 ->count();
        $daily_total = Transaction::where('cashier_id', auth()->id())
                                 ->whereDate('transaction_date', today())
                                 ->sum('final_total');

        return view('kasir.transactions-modern', compact('transactions', 'daily_count', 'daily_total'));
    }

    /**
     * Hold Transaction - Save current cart for later
     */
    public function holdTransaction(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'cart_items' => 'required|array|min:1',
            'points_to_use' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $heldTransaction = \App\Models\HeldTransaction::create([
                'hold_code' => \App\Models\HeldTransaction::generateHoldCode(),
                'cashier_id' => auth()->id(),
                'member_id' => $validated['member_id'] ?? null,
                'cart_items' => $validated['cart_items'],
                'points_to_use' => $validated['points_to_use'] ?? 0,
                'notes' => $validated['notes'] ?? null,
                'held_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction held successfully',
                'hold_code' => $heldTransaction->hold_code,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to hold transaction: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Held Transactions
     */
    public function getHeldTransactions()
    {
        $heldTransactions = \App\Models\HeldTransaction::where('cashier_id', auth()->id())
            ->with(['member.user'])
            ->latest('held_at')
            ->get();

        return response()->json([
            'success' => true,
            'held_transactions' => $heldTransactions,
        ]);
    }

    /**
     * Retrieve Held Transaction
     */
    public function retrieveHeldTransaction($id)
    {
        $heldTransaction = \App\Models\HeldTransaction::where('cashier_id', auth()->id())
            ->with(['member.user'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'held_transaction' => $heldTransaction,
        ]);
    }

    /**
     * Delete Held Transaction
     */
    public function deleteHeldTransaction($id)
    {
        $heldTransaction = \App\Models\HeldTransaction::where('cashier_id', auth()->id())
            ->findOrFail($id);

        $heldTransaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Held transaction deleted successfully',
        ]);
    }

    /**
     * Process Transaction with Multiple Payments (Split Payment)
     */
    public function processTransactionWithSplitPayment(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'payments' => 'required|array|min:1',
            'payments.*.method' => 'required|in:cash,debit,credit,e-wallet,transfer',
            'payments.*.amount' => 'required|numeric|min:0',
            'payments.*.reference' => 'nullable|string',
            'points_used' => 'nullable|integer|min:0',
            'held_transaction_id' => 'nullable|exists:held_transactions,id',
        ]);

        try {
            DB::beginTransaction();

            $member = null;
            $isMember = false;

            if ($validated['member_id']) {
                $member = Member::find($validated['member_id']);
                if ($member && $member->status === 'active') {
                    $isMember = true;
                }
            }

            // Calculate subtotal and discounts
            $subtotal = 0;
            $totalItemDiscount = 0;
            $memberDiscount = 0;
            $promoDiscount = 0;

            $globalMemberDiscPercent = (float) Setting::getValue('global_member_discount_percent', 5);

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);

                // Check stock
                if ($product->stock < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$product->name}",
                    ], 400);
                }

                // Calculate price with member price or global member discount fallback
                $price = $product->price;
                if ($isMember) {
                    if ($product->member_price && $product->member_price > 0) {
                        $memberDiscount += ($product->price - $product->member_price) * $item['quantity'];
                        $price = $product->member_price;
                    } else if ($globalMemberDiscPercent > 0) {
                        $discounted = $product->price * (1 - ($globalMemberDiscPercent/100));
                        $memberDiscount += ($product->price - $discounted) * $item['quantity'];
                        $price = $discounted;
                    }
                }
                $itemDiscount = $item['discount'] ?? 0;
                $itemSubtotal = ($price * $item['quantity']) - $itemDiscount;
                $subtotal += $itemSubtotal;
                $totalItemDiscount += $itemDiscount;
            }

            // Apply member-only promotions (store-wide)
            if ($isMember) {
                $promos = Promotion::getActivePromotions(true);
                foreach ($promos as $promo) {
                    if ($promo->type === 'percentage') {
                        $promoDiscount += $subtotal * ((float)$promo->discount_value / 100);
                    } elseif ($promo->type === 'fixed') {
                        $promoDiscount += (float) $promo->discount_value;
                    }
                }
                $promoDiscount = min($promoDiscount, $subtotal);
            }

            // Calculate tax (after promotions)
            $taxRate = Setting::getValue('tax_rate', 11);
            $taxBase = max(0, $subtotal - $promoDiscount);
            $tax = $taxBase * ($taxRate / 100);

            // Apply points discount
            $pointsDiscount = 0;
            $pointsUsed = $validated['points_used'] ?? 0;

            if ($pointsUsed > 0 && $member) {
                if ($member->points < $pointsUsed) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient points',
                    ], 400);
                }

                $pointsDiscount = Transaction::pointsToRupiah($pointsUsed);
            }

            // Calculate total
            $total = $taxBase + $tax - $pointsDiscount;

            // Validate payment total
            $paymentTotal = array_sum(array_column($validated['payments'], 'amount'));
            
            if ($paymentTotal < $total) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount is less than total',
                ], 400);
            }

            $change = $paymentTotal - $total;

            // Calculate points earned
            $pointsEarned = $isMember ? Transaction::calculatePoints($total) : 0;

            // Create transaction
            $transaction = Transaction::create([
                'transaction_code' => Transaction::generateTransactionCode(),
                'cashier_id' => auth()->id(),
                'member_id' => $member?->id,
                'subtotal' => $subtotal,
                'discount' => $totalItemDiscount + $promoDiscount,
                'member_discount' => $memberDiscount,
                'tax' => $tax,
                'total' => $total,
                'points_earned' => $pointsEarned,
                'points_used' => $pointsUsed,
                'payment_method' => count($validated['payments']) > 1 ? 'split' : $validated['payments'][0]['method'],
                'payment_amount' => $paymentTotal,
                'change_amount' => $change,
                'status' => 'completed',
                'transaction_date' => now(),
            ]);

            // Create transaction details and update stock
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $price = $product->price;
                if ($isMember) {
                    if ($product->member_price && $product->member_price > 0) {
                        $price = $product->member_price;
                    } else if ($globalMemberDiscPercent > 0) {
                        $price = $product->price * (1 - ($globalMemberDiscPercent/100));
                    }
                }
                $itemDiscount = $item['discount'] ?? 0;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'discount' => $itemDiscount,
                    'subtotal' => ($price * $item['quantity']) - $itemDiscount,
                ]);

                // Update stock
                $product->decrement('stock', $item['quantity']);
            }

            // Create payment records
            foreach ($validated['payments'] as $payment) {
                \App\Models\TransactionPayment::create([
                    'transaction_id' => $transaction->id,
                    'payment_method' => $payment['method'],
                    'amount' => $payment['amount'],
                    'reference_number' => $payment['reference'] ?? null,
                ]);
            }

            // Update member points
            if ($member) {
                if ($pointsUsed > 0) {
                    $member->usePoints($pointsUsed, $transaction->id, 'Points used for transaction');
                }

                if ($pointsEarned > 0) {
                    $member->addPoints($pointsEarned, $transaction->id, 'Points earned from transaction');
                }
            }

            // Delete held transaction if this was from a hold
            if (isset($validated['held_transaction_id'])) {
                \App\Models\HeldTransaction::where('id', $validated['held_transaction_id'])
                    ->where('cashier_id', auth()->id())
                    ->delete();
            }

            DB::commit();

            // Load relationships for response
            $transaction->load(['details.product', 'member.user', 'cashier', 'payments']);

            return response()->json([
                'success' => true,
                'message' => 'Transaction completed successfully',
                'transaction' => $transaction,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error processing transaction: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Void Transaction
     */
    public function voidTransaction(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['details.product', 'member'])
                ->findOrFail($id);

            // Only allow voiding completed transactions
            if ($transaction->status !== 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only completed transactions can be voided',
                ], 400);
            }

            // Restore stock
            foreach ($transaction->details as $detail) {
                $detail->product->increment('stock', $detail->quantity);
            }

            // Reverse member points
            if ($transaction->member) {
                if ($transaction->points_earned > 0) {
                    $transaction->member->points -= $transaction->points_earned;
                }
                if ($transaction->points_used > 0) {
                    $transaction->member->points += $transaction->points_used;
                }
                $transaction->member->save();

                // Create points history
                if ($transaction->points_earned > 0) {
                    \App\Models\PointsHistory::create([
                        'member_id' => $transaction->member->id,
                        'transaction_id' => $transaction->id,
                        'points' => -$transaction->points_earned,
                        'type' => 'adjusted',
                        'description' => 'Points reversed due to void transaction',
                        'date' => now(),
                    ]);
                }
            }

            // Update transaction status
            $transaction->update([
                'status' => 'void',
                'void_reason' => $validated['reason'],
                'voided_by' => auth()->id(),
                'voided_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaction voided successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error voiding transaction: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create Refund Request
     */
    public function createRefund(Request $request, $id)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.transaction_detail_id' => 'required|exists:transaction_details,id',
            'items.*.quantity' => 'required|integer|min:1',
            'refund_type' => 'required|in:full,partial',
            'refund_method' => 'required|in:cash,original_payment',
            'reason' => 'required|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['details.product'])
                ->findOrFail($id);

            // Validate transaction can be refunded
            if ($transaction->status === 'void') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot refund voided transactions',
                ], 400);
            }

            $refundAmount = 0;

            // Validate refund items
            foreach ($validated['items'] as $item) {
                $detail = TransactionDetail::findOrFail($item['transaction_detail_id']);
                
                // Check if detail belongs to this transaction
                if ($detail->transaction_id != $transaction->id) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid transaction detail',
                    ], 400);
                }

                // Check quantity
                if ($item['quantity'] > $detail->quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Refund quantity exceeds original quantity',
                    ], 400);
                }

                // Calculate refund amount for this item
                $itemRefundAmount = ($detail->price * $item['quantity']) - ($detail->discount * ($item['quantity'] / $detail->quantity));
                $refundAmount += $itemRefundAmount;
            }

            // Create refund
            $refund = \App\Models\Refund::create([
                'refund_code' => \App\Models\Refund::generateRefundCode(),
                'original_transaction_id' => $transaction->id,
                'processed_by' => auth()->id(),
                'refund_amount' => $refundAmount,
                'refund_type' => $validated['refund_type'],
                'refund_method' => $validated['refund_method'],
                'reason' => $validated['reason'],
                'status' => 'completed', // Auto-approve for kasir
                'refund_date' => now(),
            ]);

            // Create refund items and restore stock
            foreach ($validated['items'] as $item) {
                $detail = TransactionDetail::findOrFail($item['transaction_detail_id']);
                $itemRefundAmount = ($detail->price * $item['quantity']) - ($detail->discount * ($item['quantity'] / $detail->quantity));

                \App\Models\RefundItem::create([
                    'refund_id' => $refund->id,
                    'transaction_detail_id' => $detail->id,
                    'quantity' => $item['quantity'],
                    'refund_amount' => $itemRefundAmount,
                ]);

                // Restore stock
                $detail->product->increment('stock', $item['quantity']);
            }

            // If full refund, update transaction status
            if ($validated['refund_type'] === 'full') {
                $transaction->update(['status' => 'refunded']);
            }

            DB::commit();

            $refund->load(['items.transactionDetail.product']);

            return response()->json([
                'success' => true,
                'message' => 'Refund processed successfully',
                'refund' => $refund,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error processing refund: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get Transaction for Refund (with eligible items)
     */
    public function getTransactionForRefund($transactionCode)
    {
        $transaction = Transaction::where('transaction_code', $transactionCode)
            ->with(['details.product', 'member.user', 'refunds.items'])
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found',
            ], 404);
        }

        if ($transaction->status === 'void') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot refund voided transactions',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'transaction' => $transaction,
        ]);
    }

    /**
     * Quick Member Registration by Kasir
     */
    public function registerMember(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? 'member123'),
                'role' => 'member',
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'is_active' => true,
            ]);

            // Create member profile
            $member = Member::create([
                'user_id' => $user->id,
                'member_code' => Member::generateMemberCode(),
                'points' => 0,
                'status' => 'active',
            ]);

            // Generate QR Code
            $qrCodeService = new QRCodeService();
            $qrPath = $qrCodeService->generateMemberQR($member);
            $member->update(['qr_code' => $qrPath]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Member registered successfully',
                'member' => [
                    'id' => $member->id,
                    'member_code' => $member->member_code,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'points' => 0,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reports Index (for Kasir)
     */
    public function reportsIndex()
    {
        return view('kasir.reports.index');
    }

    /**
     * Sales Report (for Kasir - only their own transactions)
     */
    public function reportsSales(Request $request)
    {
        $startDate = $request->input('start_date', today()->startOfMonth());
        $endDate = $request->input('end_date', today());
        $memberId = $request->input('member_id');
        $productId = $request->input('product_id');

        // Kasir only sees their own transactions
        $query = Transaction::where('cashier_id', auth()->id())
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with(['cashier', 'member.user', 'details.product']);

        // Filter by member if specified
        if ($memberId) {
            $query->where('member_id', $memberId);
        }

        // Filter by product if specified
        if ($productId) {
            $query->whereHas('details', function($q) use ($productId) {
                $q->where('product_id', $productId);
            });
        }

        $transactions = $query->get();

        $totalSales = $transactions->sum('total');
        $totalTransactions = $transactions->count();

        // Get all active members and products for filters
        $members = Member::with('user')->where('status', 'active')->orderBy('member_code')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('kasir.reports.sales', compact('transactions', 'totalSales', 'totalTransactions', 'startDate', 'endDate', 'members', 'products'));
    }
}
