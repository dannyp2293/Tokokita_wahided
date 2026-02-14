<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Hitung total pesanan user
        $orderCount = Order::where('user_id', auth()->id())->count();

        // Hitung total belanja user
        $totalSpent = Order::where('user_id', auth()->id())->sum('total');

        // Ambil 3 pesanan terakhir
        $recentOrders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('dashboard', compact('orderCount', 'totalSpent', 'recentOrders'));
    }
}
