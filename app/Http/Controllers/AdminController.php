<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Promotion;
use App\Models\Setting;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected $qrCodeService;

    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $data = [
            'today_sales' => Transaction::whereDate('transaction_date', today())
                ->where('status', 'completed')
                ->sum('total'),
            'today_transactions' => Transaction::whereDate('transaction_date', today())
                ->where('status', 'completed')
                ->count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_members' => Member::where('status', 'active')->count(),
            'new_members_this_month' => Member::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'recent_transactions' => Transaction::with(['cashier', 'member.user'])
                ->latest('transaction_date')
                ->take(10)
                ->get(),
            'top_products' => Product::withCount('transactionDetails')
                ->orderBy('transaction_details_count', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard-modern', $data);
    }

    /**
     * Members Management
     */
    public function membersIndex()
    {
        $members = Member::with('user')->latest()->paginate(20);
        return view('admin.members.index-modern', compact('members'));
    }

    public function approveMember($id)
    {
        try {
            $member = Member::findOrFail($id);
            $member->status = 'active';
            $member->save();

            return redirect()->back()->with('success', 'Member berhasil diaktifkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengaktifkan member: ' . $e->getMessage());
        }
    }

    public function suspendMember($id)
    {
        try {
            $member = Member::findOrFail($id);
            $member->status = 'suspended';
            $member->save();

            return redirect()->back()->with('success', 'Member berhasil di-suspend!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal suspend member: ' . $e->getMessage());
        }
    }

    /**
     * Products Management
     */
    public function productsIndex()
    {
        $products = Product::with('category')->latest()->paginate(20);
        $categories = Category::all();
        return view('admin.products.index-modern', compact('products', 'categories'));
    }

    public function productsCreate()
    {
        $categories = Category::all();
        return view('admin.products.form-modern', compact('categories'));
    }

    public function productsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'barcode' => 'required|string|unique:products,barcode',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        // Generate QR Code
        $qrPath = $this->qrCodeService->generateProductQR($product);
        $product->update(['qr_code' => $qrPath]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function productsEdit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.form-modern', compact('product', 'categories'));
    }

    public function productsUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'barcode' => 'required|string|unique:products,barcode,' . $id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function productsDestroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete images
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        if ($product->qr_code) {
            Storage::disk('public')->delete($product->qr_code);
        }

        $product->delete();

        return back()->with('success', 'Product deleted successfully!');
    }

    public function downloadProductQR($id)
    {
        $product = Product::findOrFail($id);

        if (!$product->qr_code) {
            return back()->with('error', 'QR Code not found!');
        }

        $path = storage_path('app/public/' . $product->qr_code);

        if (!file_exists($path)) {
            return back()->with('error', 'QR Code file not found!');
        }

        return response()->download($path, 'product_' . $product->sku . '_qr.png');
    }

    /**
     * Categories Management
     */
    public function categoriesIndex()
    {
        $categories = Category::withCount('products')->latest()->paginate(20);
        return view('admin.categories.index-modern', compact('categories'));
    }

    /**
     * Transactions Management
     */
    public function transactionsIndex()
    {
        $transactions = Transaction::with(['cashier', 'member.user'])
            ->latest('transaction_date')
            ->paginate(20);
        
        return view('admin.transactions.index-modern', compact('transactions'));
    }

    public function transactionsShow($id)
    {
        $transaction = Transaction::with(['cashier', 'member.user', 'details.product'])
            ->findOrFail($id);
        
        return view('admin.transactions.show-modern', compact('transaction'));
    }

    /**
     * Users Management
     */
    public function usersIndex()
    {
        $users = User::whereIn('role', ['admin', 'kasir'])->latest()->paginate(20);
        return view('admin.users.index-modern', compact('users'));
    }

    public function usersCreate()
    {
        return view('admin.users.form-modern');
    }

    public function usersStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,kasir,member',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_active' => true,
        ]);

        // Auto-create member profile if role is member
        if ($validated['role'] === 'member') {
            $member = Member::create([
                'user_id' => $user->id,
                'member_code' => Member::generateMemberCode(),
                'points' => 0,
                'status' => 'active',
            ]);

            // Generate QR Code for member
            $qrPath = $this->qrCodeService->generateMemberQR($member);
            $member->update(['qr_code' => $qrPath]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function usersEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.form-modern', compact('user'));
    }

    public function usersUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,kasir,member',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $oldRole = $user->role;
        $user->update($validated);

        // Handle role change to member: create member profile if doesn't exist
        if ($validated['role'] === 'member' && $oldRole !== 'member') {
            if (!$user->member) {
                $member = Member::create([
                    'user_id' => $user->id,
                    'member_code' => Member::generateMemberCode(),
                    'points' => 0,
                    'status' => 'active',
                ]);

                $qrPath = $this->qrCodeService->generateMemberQR($member);
                $member->update(['qr_code' => $qrPath]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function usersDestroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }

    public function usersResetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success', 'Password reset successfully!');
    }

    /**
     * Kasir Management
     */
    public function kasirIndex()
    {
        $kasirs = User::where('role', 'kasir')->latest()->paginate(20);
        return view('admin.kasir.index', compact('kasirs'));
    }

    public function kasirCreate()
    {
        return view('admin.kasir.create');
    }

    public function kasirStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'kasir',
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.kasir.index')->with('success', 'Kasir created successfully!');
    }

    /**
     * Promotions Management
     */
    public function promotionsIndex()
    {
        $promotions = Promotion::latest()->paginate(20);
        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Reports
     */
    public function reportsIndex()
    {
        return view('admin.reports.index');
    }

    public function reportsSales(Request $request)
    {
        $startDate = $request->input('start_date', today()->startOfMonth());
        $endDate = $request->input('end_date', today());
        $memberId = $request->input('member_id');
        $productId = $request->input('product_id');

        $query = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->with(['cashier', 'member.user', 'details.product']);

        // Filter by member if specified
        if ($memberId) {
            $query->where('member_id', $memberId);
        }

        // Filter by product if specified (through transaction details)
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

        return view('admin.reports.sales-modern', compact('transactions', 'totalSales', 'totalTransactions', 'startDate', 'endDate', 'members', 'products'));
    }

    /**
     * Settings
     */
    public function settingsIndex()
    {
        $settings = Setting::all();
        return view('admin.settings.index', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
            }
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
