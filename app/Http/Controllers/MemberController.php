<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    /**
     * Member Dashboard
     */
    public function dashboard()
    {
        $member = auth()->user()->member;

        $data = [
            'member' => $member,
            'total_transactions' => Transaction::where('member_id', $member->id)
                ->where('status', 'completed')
                ->count(),
            'total_spent' => Transaction::where('member_id', $member->id)
                ->where('status', 'completed')
                ->sum('total'),
            'recent_transactions' => Transaction::where('member_id', $member->id)
                ->with(['details.product', 'cashier'])
                ->latest('transaction_date')
                ->take(5)
                ->get(),
            'points_history' => $member->pointsHistory()
                ->latest('date')
                ->take(10)
                ->get(),
            'active_promotions' => Promotion::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->get(),
        ];

        return view('member.dashboard-modern', $data);
    }

    /**
     * View QR Code
     */
    public function qrCode()
    {
        $member = auth()->user()->member;

        return view('member.qr-code', compact('member'));
    }

    /**
     * Transaction History (UC-P01)
     */
    public function transactions(Request $request)
    {
        $member = auth()->user()->member;

        $query = Transaction::where('member_id', $member->id)
            ->with(['details.product', 'cashier'])
            ->where('status', 'completed');

        // Filter by search (transaction code)
        if ($request->filled('search')) {
            $query->where('transaction_code', 'like', '%' . $request->search . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->latest('transaction_date')->paginate(10)->withQueryString();

        return view('member.transactions-modern', compact('transactions'));
    }

    /**
     * View Transaction Detail (UC-P01)
     */
    public function transactionDetail($id)
    {
        $member = auth()->user()->member;

        $transaction = Transaction::where('member_id', $member->id)
            ->with(['details.product', 'cashier', 'payments'])
            ->where('status', 'completed')
            ->findOrFail($id);

        return view('member.transaction-detail-modern', compact('transaction'));
    }

    /**
     * Points History
     */
    public function pointsHistory(Request $request)
    {
        $member = auth()->user()->member;
        
        $query = PointsHistory::where('member_id', $member->id)->with('transaction');
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        
        $points_history = $query->latest()->paginate(20);
        
        // Calculate totals
        $total_earned = PointsHistory::where('member_id', $member->id)
                                    ->where('type', 'earned')
                                    ->sum('points');
        $total_used = PointsHistory::where('member_id', $member->id)
                                  ->whereIn('type', ['redeemed', 'expired'])
                                  ->sum('points');
        
        return view('member.points-history-modern', compact('member', 'points_history', 'total_earned', 'total_used'));
    }

    /**
     * Product Catalog
     */
    public function products(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->with('category');

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category_id', $request->input('category'));
        }

        $products = $query->paginate(20);
        $categories = \App\Models\Category::where('is_active', true)->get();

        return view('member.products-modern', compact('products', 'categories'));
    }

    /**
     * Promotions
     */
    public function promotions()
    {
        $promotions = Promotion::getActivePromotions(true);

        return view('member.promotions', compact('promotions'));
    }

    /**
     * Profile
     */
    public function profile()
    {
        $user = auth()->user();
        $member = $user->member;

        return view('member.profile-modern', compact('user', 'member'));
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $member = $user->member;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'birthdate' => 'nullable|date',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        $member->update([
            'birthdate' => $validated['birthdate'],
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}
