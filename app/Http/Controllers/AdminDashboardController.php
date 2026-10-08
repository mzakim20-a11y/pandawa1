<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Courier;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * 1. Dashboard Utama
     */
    public function dashboard()
    {
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalOrders = Order::count();
        $totalCouriers = Courier::count();
        $activeCouriers = Courier::where('status', 'available')->count();
        $totalMembers = Member::count();

        // Breakdown Pesanan
        $catalogOrdersCount = Order::where('order_type', 'catalog')->count();
        $deliveryOrdersCount = Order::where('order_type', 'delivery')->count();
        $jastipOrdersCount = Order::where('order_type', 'jastip')->count();

        // Pesanan Terbaru
        $recentOrders = Order::with(['courier', 'items', 'delivery', 'jastip'])->latest()->take(7)->get();

        // Status Kurir
        $couriers = Courier::latest()->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalCouriers',
            'activeCouriers',
            'totalMembers',
            'catalogOrdersCount',
            'deliveryOrdersCount',
            'jastipOrdersCount',
            'recentOrders',
            'couriers'
        ));
    }

    /**
     * 2. Manajemen Kurir
     */
    public function couriers(Request $request)
    {
        $query = Courier::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('plate_number', 'like', "%{$s}%");
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $couriers = $query->latest()->get();

        return view('admin.couriers', compact('couriers'));
    }

    public function storeCourier(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'vehicle' => 'required|string|max:100',
            'plate_number' => 'required|string|max:20',
            'badge' => 'nullable|string|max:100',
            'avatar' => 'nullable|url',
        ]);

        $validated['avatar'] = $validated['avatar'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=80';
        $validated['status'] = 'available';
        $validated['rating'] = 4.90;
        $validated['total_trips'] = '0 trip';

        Courier::create($validated);

        return redirect()->route('admin.couriers')->with('success', 'Kurir baru berhasil ditambahkan!');
    }

    public function updateCourier(Request $request, $id)
    {
        $courier = Courier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'vehicle' => 'required|string|max:100',
            'plate_number' => 'required|string|max:20',
            'status' => 'required|in:available,busy,offline',
            'badge' => 'nullable|string|max:100',
            'avatar' => 'nullable|url',
        ]);

        $courier->update($validated);

        return redirect()->route('admin.couriers')->with('success', "Data kurir {$courier->name} berhasil diperbarui!");
    }

    public function deleteCourier($id)
    {
        $courier = Courier::findOrFail($id);
        $name = $courier->name;
        $courier->delete();

        return redirect()->route('admin.couriers')->with('success', "Kurir {$name} berhasil dihapus.");
    }

    /**
     * 3. Manajemen Member / Pelanggan
     */
    public function members(Request $request)
    {
        $query = Member::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
        }

        $members = $query->latest()->get();

        return view('admin.members', compact('members'));
    }

    public function storeMember(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|unique:members,phone',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
        ]);

        $validated['total_orders'] = 0;
        $validated['total_spent'] = 0;
        $validated['status'] = 'active';

        Member::create($validated);

        return redirect()->route('admin.members')->with('success', 'Member baru berhasil didaftarkan!');
    }

    public function updateMember(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => "required|string|unique:members,phone,{$id}",
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'status' => 'required|in:active,blocked',
        ]);

        $member->update($validated);

        return redirect()->route('admin.members')->with('success', "Data member {$member->name} berhasil diperbarui!");
    }

    public function deleteMember($id)
    {
        $member = Member::findOrFail($id);
        $name = $member->name;
        $member->delete();

        return redirect()->route('admin.members')->with('success', "Member {$name} berhasil dihapus.");
    }

    /**
     * 4. Manajemen Pesanan
     */
    public function orders(Request $request)
    {
        $query = Order::with(['courier', 'items', 'delivery', 'jastip']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('order_type', $request->type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_code', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_phone', 'like', "%{$s}%");
            });
        }

        $orders = $query->latest()->get();
        $couriers = Courier::where('status', 'available')->get();

        return view('admin.orders', compact('orders', 'couriers'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,assigned,on_delivery,completed,cancelled',
            'courier_id' => 'nullable|exists:couriers,id'
        ]);

        $order->status = $request->status;

        if ($request->filled('courier_id')) {
            $order->courier_id = $request->courier_id;
        }

        // Sinkronisasi current_step
        if ($request->status === 'pending') $order->current_step = 1;
        elseif ($request->status === 'assigned') $order->current_step = 2;
        elseif ($request->status === 'on_delivery') $order->current_step = 3;
        elseif ($request->status === 'completed') {
            $order->current_step = 4;
            $order->payment_status = 'paid';
        }

        $order->save();

        return redirect()->back()->with('success', "Status pesanan {$order->order_code} berhasil diubah ke {$order->status}!");
    }

    public function printInvoice($id)
    {
        $order = Order::with(['courier', 'items', 'delivery', 'jastip'])->findOrFail($id);
        $settings = [
            'company_name' => Setting::get('company_name', 'Pandawa Delivery & Jastip'),
            'company_phone' => Setting::get('company_phone', '081234567890'),
            'company_address' => Setting::get('company_address', 'Jl. Jendral Sudirman No. 45, Jakarta'),
        ];

        return view('admin.invoice', compact('order', 'settings'));
    }

    /**
     * 5. Laporan & Rekapitulasi
     */
    public function reports(Request $request)
    {
        $period = $request->get('period', 'month'); // today, week, month, all
        $query = Order::where('status', '!=', 'cancelled');

        if ($period === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        }

        $orders = (clone $query)->with(['courier', 'items'])->latest()->get();
        $totalRevenue = (clone $query)->sum('total_amount');
        $totalOrders = $orders->count();
        $totalShippingEarned = (clone $query)->sum('shipping_cost');
        $totalCompleted = (clone $query)->where('status', 'completed')->count();

        // Breakdown per layanan
        $revenueCatalog = (clone $query)->where('order_type', 'catalog')->sum('total_amount');
        $revenueDelivery = (clone $query)->where('order_type', 'delivery')->sum('total_amount');
        $revenueJastip = (clone $query)->where('order_type', 'jastip')->sum('total_amount');

        // Kinerja Kurir
        $couriersPerformance = Courier::withCount(['orders' => function ($q) {
            $q->where('status', 'completed');
        }])->get();

        return view('admin.reports', compact(
            'period',
            'orders',
            'totalRevenue',
            'totalOrders',
            'totalShippingEarned',
            'totalCompleted',
            'revenueCatalog',
            'revenueDelivery',
            'revenueJastip',
            'couriersPerformance'
        ));
    }

    /**
     * 6. Pengaturan Aplikasi & Tarif
     */
    public function settings()
    {
        $settings = [
            'company_name' => Setting::get('company_name', 'Pandawa Delivery & Jastip'),
            'company_phone' => Setting::get('company_phone', '081234567890'),
            'company_email' => Setting::get('company_email', 'admin@pandawa.com'),
            'company_address' => Setting::get('company_address', 'Jl. Jendral Sudirman No. 45, Jakarta Pusat'),
            'delivery_base_rate' => Setting::get('delivery_base_rate', '10000'),
            'delivery_per_km_rate' => Setting::get('delivery_per_km_rate', '2500'),
            'jastip_fee' => Setting::get('jastip_fee', '10000'),
            'service_fee' => Setting::get('service_fee', '1000'),
        ];

        $admin = Auth::user();

        return view('admin.settings', compact('settings', 'admin'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:100',
            'company_phone' => 'required|string|max:20',
            'company_email' => 'required|email|max:100',
            'company_address' => 'required|string',
            'delivery_base_rate' => 'required|numeric|min:0',
            'delivery_per_km_rate' => 'required|numeric|min:0',
            'jastip_fee' => 'required|numeric|min:0',
            'service_fee' => 'required|numeric|min:0',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan aplikasi & tarif berhasil disimpan!');
    }

    public function updateAdminProfile(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => "required|email|unique:users,email,{$admin->id}",
            'password' => 'nullable|min:6|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.settings')->with('success', 'Profil & password admin berhasil diperbarui!');
    }
}
