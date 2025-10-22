<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard principal del administrador
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'pending_reviews' => Review::where('is_approved', false)->count(),
            'total_categories' => Category::count(),
        ];

        $recent_orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recent_users = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'recent_users'));
    }

    /**
     * Gestión de usuarios
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,nutritionist,client'
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Rol actualizado exitosamente.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'Usuario eliminado exitosamente.');
    }
}


