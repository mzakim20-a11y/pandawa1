<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Courier;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Delivery;
use App\Models\Jastip;
use App\Models\Voucher;

class PandawaController extends Controller
{
    /**
     * Halaman Utama Pandawa Delivery & Jastip
     */
    public function index()
    {
        $categories = Category::all();
        $couriers = Courier::all();
        $featuredProducts = Product::where('is_popular', true)->take(4)->get();
        $products = Product::where('is_available', true)->with('category')->get();
        $recentOrders = Order::with(['courier', 'items', 'delivery', 'jastip'])->latest()->take(10)->get();

        return view('pandawa.index', compact('categories', 'couriers', 'featuredProducts', 'products', 'recentOrders'));
    }

    /**
     * API: Ambil produk berdasarkan filter & search
     */
    public function getProducts(Request $request)
    {
        $query = Product::where('is_available', true)->with('category');

        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->sortBy === 'price-asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sortBy === 'price-desc') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sortBy === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->orderBy('is_popular', 'desc')->latest();
        }

        return response()->json($query->get());
    }

    /**
     * API: Simpan Pesanan Katalog ke Database
     */
    public function createCatalogOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'payment_method' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $itemInput) {
                $product = Product::findOrFail($itemInput['id']);
                $itemSubtotal = $product->price * $itemInput['qty'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'item_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $itemInput['qty'],
                    'subtotal' => $itemSubtotal,
                    'notes' => $itemInput['notes'] ?? null
                ];
            }

            $shippingCost = 8000;
            $serviceFee = 1000;
            $discount = 0;

            if ($request->filled('voucher_code')) {
                $voucher = Voucher::where('code', strtoupper($request->voucher_code))->where('is_active', true)->first();
                if ($voucher && $subtotal >= $voucher->min_order) {
                    if ($voucher->discount_type === 'fixed') {
                        $discount = $voucher->discount_amount;
                    } elseif ($voucher->discount_type === 'percent') {
                        $discount = ($subtotal * $voucher->discount_amount) / 100;
                    }
                }
            }

            $totalAmount = max(0, $subtotal + $shippingCost + $serviceFee - $discount);
            $orderCode = 'PDW-' . mt_rand(100000, 999999);

            // Assign dedicated courier (random or first available)
            $courier = Courier::where('status', 'available')->first() ?? Courier::first();

            $order = Order::create([
                'order_code' => $orderCode,
                'order_type' => 'catalog',
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'notes' => $request->notes,
                'courier_id' => $courier ? $courier->id : null,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'service_fee' => $serviceFee,
                'discount' => $discount,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'status' => 'assigned',
                'current_step' => 2
            ]);

            foreach ($itemsData as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }

            // Generate WhatsApp formatted text
            $itemsListText = "";
            foreach ($itemsData as $it) {
                $itemsListText .= "- {$it['quantity']}x {$it['item_name']} (Rp " . number_format($it['subtotal'], 0, ',', '.') . ")\n";
            }

            $waMsg = "*PESANAN PANDAWA DELIVERY & JASTIP* 🛵\n"
                   . "--------------------------------\n"
                   . "*No. Order:* {$orderCode}\n"
                   . "*Nama:* {$order->customer_name}\n"
                   . "*No. HP:* {$order->customer_phone}\n"
                   . "*Alamat Antar:* {$order->customer_address}\n"
                   . ($order->notes ? "*Catatan:* {$order->notes}\n" : "")
                   . "*Detail Belanja:*\n"
                   . $itemsListText
                   . "--------------------------------\n"
                   . "Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n"
                   . "Ongkir Kurir: Rp " . number_format($shippingCost, 0, ',', '.') . "\n"
                   . ($discount > 0 ? "Diskon: -Rp " . number_format($discount, 0, ',', '.') . "\n" : "")
                   . "Biaya Layanan: Rp 1.000\n"
                   . "*TOTAL BAYAR: Rp " . number_format($totalAmount, 0, ',', '.') . "*\n"
                   . "*Metode:* {$order->payment_method}\n"
                   . "*Kurir:* " . ($courier ? "{$courier->name} ({$courier->plate_number})" : "Pandawa Courier") . "\n\n"
                   . "Pesanan siap diproses dan diantar!";

            $courierPhone = $courier ? preg_replace('/^0/', '62', $courier->phone) : '6281234567890';
            $waUrl = "https://wa.me/{$courierPhone}?text=" . rawurlencode($waMsg);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil disimpan di database!',
                'order' => $order->load(['courier', 'items']),
                'whatsapp_url' => $waUrl
            ]);
        });
    }

    /**
     * API: Simpan Pesanan Delivery Paket (GoSend) ke Database
     */
    public function createDeliveryOrder(Request $request)
    {
        $request->validate([
            'sender_name' => 'required|string',
            'sender_phone' => 'required|string',
            'sender_address' => 'required|string',
            'receiver_name' => 'required|string',
            'receiver_phone' => 'required|string',
            'receiver_address' => 'required|string',
            'package_type' => 'required|string',
            'armada_type' => 'required|string',
            'distance_km' => 'required|numeric|min:0.5',
        ]);

        return DB::transaction(function () use ($request) {
            $km = (float)$request->distance_km;
            $baseRate = 10000;
            $perKmRate = 2500;

            if ($request->armada_type === 'motor_cargo') {
                $baseRate = 15000;
                $perKmRate = 3500;
            } elseif ($request->armada_type === 'mobil') {
                $baseRate = 35000;
                $perKmRate = 5000;
            }

            $baseKm = 3;
            $extraKm = max(0, $km - $baseKm);
            $shippingCost = $baseRate + ($extraKm * $perKmRate);
            $serviceFee = 1000;
            $totalAmount = $shippingCost + $serviceFee;

            $orderCode = 'PDW-DEL-' . mt_rand(100000, 999999);
            $courier = Courier::where('status', 'available')->first() ?? Courier::first();

            $order = Order::create([
                'order_code' => $orderCode,
                'order_type' => 'delivery',
                'customer_name' => $request->sender_name,
                'customer_phone' => $request->sender_phone,
                'customer_address' => $request->receiver_address,
                'notes' => $request->package_note,
                'courier_id' => $courier ? $courier->id : null,
                'subtotal' => 0,
                'shipping_cost' => $shippingCost,
                'service_fee' => $serviceFee,
                'discount' => 0,
                'total_amount' => $totalAmount,
                'payment_method' => 'COD',
                'payment_status' => 'unpaid',
                'status' => 'assigned',
                'current_step' => 2
            ]);

            Delivery::create([
                'order_id' => $order->id,
                'sender_name' => $request->sender_name,
                'sender_phone' => $request->sender_phone,
                'sender_address' => $request->sender_address,
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_address' => $request->receiver_address,
                'package_type' => $request->package_type,
                'package_note' => $request->package_note,
                'armada_type' => $request->armada_type,
                'distance_km' => $km
            ]);

            $waMsg = "*ORDER DELIVERY PANDAWA* 📦\n"
                   . "--------------------------------\n"
                   . "*No. Order:* {$orderCode}\n"
                   . "*Jarak:* {$km} KM\n"
                   . "*Jenis Paket:* {$request->package_type}\n"
                   . "*PENGIRIM:* {$request->sender_name} ({$request->sender_phone})\n"
                   . "*Alamat Jemput:* {$request->sender_address}\n"
                   . "*PENERIMA:* {$request->receiver_name} ({$request->receiver_phone})\n"
                   . "*Alamat Antar:* {$request->receiver_address}\n"
                   . ($request->package_note ? "*Catatan:* {$request->package_note}\n" : "")
                   . "*Total Ongkir:* Rp " . number_format($totalAmount, 0, ',', '.') . "\n"
                   . "*Kurir:* " . ($courier ? "{$courier->name} ({$courier->plate_number})" : "Pandawa Kurir") . "\n\n"
                   . "Kurir segera menjemput paket!";

            $courierPhone = $courier ? preg_replace('/^0/', '62', $courier->phone) : '6281234567890';
            $waUrl = "https://wa.me/{$courierPhone}?text=" . rawurlencode($waMsg);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan Delivery berhasil disimpan di database!',
                'order' => $order->load(['courier', 'delivery']),
                'whatsapp_url' => $waUrl
            ]);
        });
    }

    /**
     * API: Simpan Pesanan Jastip ke Database
     */
    public function createJastipOrder(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string',
            'destination_address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $talanganAmount = 0;
            foreach ($request->items as $it) {
                $talanganAmount += (float)$it['price'];
            }

            $jastipFee = 10000;
            $shippingCost = 12000;
            $serviceFee = 1000;
            $totalAmount = $talanganAmount + $jastipFee + $shippingCost + $serviceFee;

            $orderCode = 'PDW-JAS-' . mt_rand(100000, 999999);
            $courier = Courier::where('status', 'available')->first() ?? Courier::first();

            $order = Order::create([
                'order_code' => $orderCode,
                'order_type' => 'jastip',
                'customer_name' => $request->customer_name ?? 'Pelanggan Jastip',
                'customer_phone' => $request->customer_phone ?? '-',
                'customer_address' => $request->destination_address,
                'notes' => $request->shopping_notes,
                'courier_id' => $courier ? $courier->id : null,
                'subtotal' => $talanganAmount,
                'shipping_cost' => $shippingCost,
                'service_fee' => $serviceFee,
                'discount' => 0,
                'total_amount' => $totalAmount,
                'payment_method' => 'COD (Talangan Kurir)',
                'payment_status' => 'unpaid',
                'status' => 'assigned',
                'current_step' => 2
            ]);

            Jastip::create([
                'order_id' => $order->id,
                'store_name' => $request->store_name,
                'store_address' => $request->store_address,
                'destination_address' => $request->destination_address,
                'shopping_notes' => $request->shopping_notes,
                'talangan_amount' => $talanganAmount,
                'jastip_fee' => $jastipFee
            ]);

            $itemsText = "";
            foreach ($request->items as $it) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_name' => $it['name'],
                    'price' => (float)$it['price'],
                    'quantity' => 1,
                    'subtotal' => (float)$it['price']
                ]);
                $itemsText .= "- {$it['name']} (Est. Rp " . number_format($it['price'], 0, ',', '.') . ")\n";
            }

            $waMsg = "*ORDER JASTIP PANDAWA* 🛍️\n"
                   . "--------------------------------\n"
                   . "*No. Order:* {$orderCode}\n"
                   . "*Toko/Lokasi Beli:* {$request->store_name}\n"
                   . "*Alamat Antar:* {$request->destination_address}\n"
                   . "*Daftar Belanjaan:*\n"
                   . $itemsText
                   . ($request->shopping_notes ? "*Catatan Khusus:* {$request->shopping_notes}\n" : "")
                   . "--------------------------------\n"
                   . "Talangan Belanja: Rp " . number_format($talanganAmount, 0, ',', '.') . "\n"
                   . "Jasa Titip (Jastip Fee): Rp " . number_format($jastipFee, 0, ',', '.') . "\n"
                   . "Ongkir Kurir: Rp " . number_format($shippingCost, 0, ',', '.') . "\n"
                   . "*TOTAL ESTIMASI: Rp " . number_format($totalAmount, 0, ',', '.') . "*\n"
                   . "*Kurir Belanja:* " . ($courier ? "{$courier->name}" : "Pandawa Kurir") . "\n\n"
                   . "Kurir segera membelikan dan mengantar barang!";

            $courierPhone = $courier ? preg_replace('/^0/', '62', $courier->phone) : '6281234567890';
            $waUrl = "https://wa.me/{$courierPhone}?text=" . rawurlencode($waMsg);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan Jastip berhasil disimpan di database!',
                'order' => $order->load(['courier', 'jastip', 'items']),
                'whatsapp_url' => $waUrl
            ]);
        });
    }

    /**
     * API: Ambil status tracking pesanan dari DB
     */
    public function getOrderTracking($orderCode)
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['courier', 'items', 'delivery', 'jastip'])
            ->firstOrFail();

        return response()->json($order);
    }

    /**
     * API: Update status langkah tracking di DB (Simulasi)
     */
    public function updateTrackingStep(Request $request, $orderCode)
    {
        $order = Order::where('order_code', $orderCode)->firstOrFail();
        
        $newStep = (int)$request->step;
        if ($newStep >= 1 && $newStep <= 4) {
            $order->current_step = $newStep;
            if ($newStep === 1) $order->status = 'pending';
            elseif ($newStep === 2) $order->status = 'assigned';
            elseif ($newStep === 3) $order->status = 'on_delivery';
            elseif ($newStep === 4) {
                $order->status = 'completed';
                $order->payment_status = 'paid';
            }
            $order->save();
        }

        return response()->json([
            'success' => true,
            'current_step' => $order->current_step,
            'status' => $order->status
        ]);
    }

    /**
     * API: Validasi voucher promo dari DB
     */
    public function checkVoucher(Request $request)
    {
        $code = strtoupper(trim($request->code));
        $voucher = Voucher::where('code', $code)->where('is_active', true)->first();

        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode voucher tidak ditemukan atau sudah tidak aktif.'
            ], 404);
        }

        return response()->json([
            'valid' => true,
            'code' => $voucher->code,
            'discount_amount' => (float)$voucher->discount_amount,
            'discount_type' => $voucher->discount_type,
            'min_order' => (float)$voucher->min_order,
            'message' => 'Voucher berhasil digunakan!'
        ]);
    }

    /**
     * API: Riwayat Pesanan dari DB
     */
    public function getOrderHistory()
    {
        $orders = Order::with(['courier', 'items', 'delivery', 'jastip'])
            ->latest()
            ->take(20)
            ->get();

        return response()->json($orders);
    }
}
