<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCategory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'Tổng sản phẩm' => Food::count(),
                'Danh mục' => FoodCategory::count(),
                'Đang bán' => Food::where('is_available', true)->count(),
                'Hết hàng' => Food::where('stock', 0)->count(),
            ],
            'recentFoods' => Food::with('category')->latest()->take(6)->get(),
        ]);
    }
}
