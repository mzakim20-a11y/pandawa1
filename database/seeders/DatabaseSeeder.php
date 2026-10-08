<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Courier;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Delivery;
use App\Models\Jastip;
use App\Models\Member;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Default Admin User
        User::updateOrCreate(
            ['email' => 'admin@pandawa.com'],
            [
                'name' => 'Admin Pandawa',
                'password' => Hash::make('admin123')
            ]
        );

        // 1. Categories
        $catMakanan = Category::create([
            'name' => 'Makanan Siap Saji',
            'slug' => 'makanan',
            'icon' => 'fa-bowl-food'
        ]);

        $catMinuman = Category::create([
            'name' => 'Kopi & Minuman Dingin',
            'slug' => 'minuman',
            'icon' => 'fa-mug-hot'
        ]);

        $catSembako = Category::create([
            'name' => 'Sembako & Kebutuhan Dapur',
            'slug' => 'sembako',
            'icon' => 'fa-wheat-awn'
        ]);

        $catSnack = Category::create([
            'name' => 'Buah & Cemilan',
            'slug' => 'snack',
            'icon' => 'fa-cookie'
        ]);

        $catKesehatan = Category::create([
            'name' => 'Apotek & Kesehatan',
            'slug' => 'kesehatan',
            'icon' => 'fa-heart-pulse'
        ]);

        // 2. Couriers (Armada Kurir Pandawa)
        $courier1 = Courier::create([
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'vehicle' => 'Honda Vario 160 (Navy Edition)',
            'plate_number' => 'B 4521 PDW',
            'rating' => 4.96,
            'total_trips' => '2.940+',
            'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=80',
            'status' => 'available',
            'badge' => 'Kurir Prioritas Pandawa'
        ]);

        $courier2 = Courier::create([
            'name' => 'Rian Hidayat',
            'phone' => '081298765432',
            'vehicle' => 'Yamaha NMAX 155 (Midnight Navy)',
            'plate_number' => 'B 6319 PDW',
            'rating' => 4.93,
            'total_trips' => '1.870+',
            'avatar' => 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=200&auto=format&fit=crop&q=80',
            'status' => 'available',
            'badge' => 'Spesialis Jastip & Belanja Pasar'
        ]);

        $courier3 = Courier::create([
            'name' => 'Deni Saputra',
            'phone' => '085712349988',
            'vehicle' => 'Honda PCX 160 (Navy Blue)',
            'plate_number' => 'B 3820 PDW',
            'rating' => 4.90,
            'total_trips' => '1.250+',
            'avatar' => 'https://images.unsplash.com/photo-1527980965255-d3b416303d12?w=200&auto=format&fit=crop&q=80',
            'status' => 'available',
            'badge' => 'Kurir Express Antar Dokumen'
        ]);

        // 3. Products
        Product::create([
            'category_id' => $catMakanan->id,
            'name' => 'Nasi Ayam Geprek Sambal Korek Pandawa',
            'slug' => 'nasi-ayam-geprek-sambal-korek',
            'description' => 'Ayam krispi gurih digeprek dengan cabai rawit pedas mantap khas Pandawa. Lengkap nasi hangat & lalap segar.',
            'price' => 22000,
            'original_price' => 28000,
            'rating' => 4.9,
            'sold_count' => '1.5k+',
            'prep_time' => '15-20 mnt',
            'image' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=600&auto=format&fit=crop&q=80',
            'badge' => 'Diskon 20%',
            'is_popular' => true,
            'is_available' => true
        ]);

        Product::create([
            'category_id' => $catMakanan->id,
            'name' => 'Mie Goreng Spesial Pandawa Express',
            'slug' => 'mie-goreng-spesial-pandawa',
            'description' => 'Mie kenyal ditumis dengan suwiran ayam gurih, bakso sapi, telur orak-arik, dan sayuran segar berpadu kecap manis premium.',
            'price' => 19000,
            'original_price' => 23000,
            'rating' => 4.8,
            'sold_count' => '980+',
            'prep_time' => '10-15 mnt',
            'image' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=600&auto=format&fit=crop&q=80',
            'badge' => 'Favorit',
            'is_popular' => true,
            'is_available' => true
        ]);

        Product::create([
            'category_id' => $catMinuman->id,
            'name' => 'Es Kopi Susu Gula Aren Pandawa 500ml',
            'slug' => 'es-kopi-susu-gula-aren-pandawa',
            'description' => 'Espresso robusta & arabika blend berpadu susu segar creamy dan gula aren murni khas Nusantara.',
            'price' => 17000,
            'original_price' => 22000,
            'rating' => 4.9,
            'sold_count' => '2.6k+',
            'prep_time' => '5-10 mnt',
            'image' => 'https://images.unsplash.com/photo-1517256064527-09c73fc73e38?w=600&auto=format&fit=crop&q=80',
            'badge' => 'Best Seller',
            'is_popular' => true,
            'is_available' => true
        ]);

        Product::create([
            'category_id' => $catMinuman->id,
            'name' => 'Matcha Latte Creamy Ice Cup Jumbo',
            'slug' => 'matcha-latte-creamy-ice',
            'description' => 'Bubuk matcha premium Jepang dengan susu pasteurisasi lembut, rasa manis pas dan menyegarkan.',
            'price' => 20000,
            'original_price' => 25000,
            'rating' => 4.7,
            'sold_count' => '720+',
            'prep_time' => '5-10 mnt',
            'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=600&auto=format&fit=crop&q=80',
            'badge' => 'Segar',
            'is_popular' => false,
            'is_available' => true
        ]);

        Product::create([
            'category_id' => $catSembako->id,
            'name' => 'Beras Pandan Wangi Premium 5 Kg',
            'slug' => 'beras-pandan-wangi-5kg',
            'description' => 'Beras putih bersih, pulen, wangi alami tanpa pengawet atau pemutih kimia. Kemasan 5 kg praktis siap antar kurir.',
            'price' => 75000,
            'original_price' => 82000,
            'rating' => 4.9,
            'sold_count' => '3.4k+',
            'prep_time' => 'Langsung Kirim',
            'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=600&auto=format&fit=crop&q=80',
            'badge' => 'Sembako Pokok',
            'is_popular' => true,
            'is_available' => true
        ]);

        Product::create([
            'category_id' => $catSembako->id,
            'name' => 'Minyak Goreng Sawit Pouch 2 Liter',
            'slug' => 'minyak-goreng-pouch-2l',
            'description' => 'Minyak goreng kelapa sawit murni berkualitas tinggi, hasil gorengan renyah dan tidak cepat hitam.',
            'price' => 35000,
            'original_price' => 38000,
            'rating' => 4.8,
            'sold_count' => '2.1k+',
            'prep_time' => 'Langsung Kirim',
            'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=600&auto=format&fit=crop&q=80',
            'badge' => 'Promo Hemat',
            'is_popular' => false,
            'is_available' => true
        ]);

        Product::create([
            'category_id' => $catSembako->id,
            'name' => 'Telur Ayam Negeri Fresh 1 Kg',
            'slug' => 'telur-ayam-negeri-fresh-1kg',
            'description' => 'Telur ayam segar langsung dari peternakan lokal, cangkang kokoh, kuning telur padat berkualitas.',
            'price' => 29000,
            'original_price' => 32000,
            'rating' => 4.9,
            'sold_count' => '4.8k+',
            'prep_time' => 'Langsung Kirim',
            'image' => 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=600&auto=format&fit=crop&q=80',
            'badge' => 'Fresh Tiap Hari',
            'is_popular' => true,
            'is_available' => true
        ]);

        Product::create([
            'category_id' => $catSnack->id,
            'name' => 'Paket Buah Apel Fuji & Jeruk Manis 1 Kg',
            'slug' => 'paket-buah-apel-jeruk',
            'description' => 'Kombinasi apel fuji renyah manis dan jeruk peras manis kaya vitamin C. Dipilihkan buah tersegar oleh kurir Pandawa.',
            'price' => 45000,
            'original_price' => 52000,
            'rating' => 4.8,
            'sold_count' => '620+',
            'prep_time' => 'Pilihan Kurir',
            'image' => 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=600&auto=format&fit=crop&q=80',
            'badge' => 'Kaya Vitamin',
            'is_popular' => false,
            'is_available' => true
        ]);

        Product::create([
            'category_id' => $catSnack->id,
            'name' => 'Keripik Kentang Balado Crispy 250g',
            'slug' => 'keripik-kentang-balado',
            'description' => 'Keripik kentang asli dengan balutan bumbu balado pedas manis gurih. Renyah dan bikin nagih.',
            'price' => 21000,
            'original_price' => 25000,
            'rating' => 4.9,
            'sold_count' => '1.3k+',
            'prep_time' => 'Siap Kirim',
            'image' => 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?w=600&auto=format&fit=crop&q=80',
            'badge' => 'Cemilan Favorit',
            'is_popular' => true,
            'is_available' => true
        ]);

        Product::create([
            'category_id' => $catKesehatan->id,
            'name' => 'Paket Vitamin C 500mg & Madu Murni Alami',
            'slug' => 'paket-vitamin-c-madu',
            'description' => 'Paket jaga daya tahan tubuh harian berisikan strip Vitamin C dan botol madu murni berkualitas.',
            'price' => 39000,
            'original_price' => 45000,
            'rating' => 4.9,
            'sold_count' => '850+',
            'prep_time' => 'Kirim Cepat',
            'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&auto=format&fit=crop&q=80',
            'badge' => 'Imunitas Sehat',
            'is_popular' => false,
            'is_available' => true
        ]);

        // 4. Vouchers
        Voucher::create([
            'code' => 'ONGKIRGRATIS',
            'discount_amount' => 8000,
            'discount_type' => 'fixed',
            'min_order' => 20000,
            'is_active' => true
        ]);

        Voucher::create([
            'code' => 'PANDAWA10',
            'discount_amount' => 10,
            'discount_type' => 'percent',
            'min_order' => 30000,
            'is_active' => true
        ]);

        Voucher::create([
            'code' => 'PANDAWABARU',
            'discount_amount' => 15000,
            'discount_type' => 'fixed',
            'min_order' => 50000,
            'is_active' => true
        ]);

        // 5. Members (Pelanggan Terdaftar)
        Member::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@gmail.com',
            'phone' => '081299887766',
            'address' => 'Jl. Jendral Sudirman No. 45, Tower A Lt. 8, Jakarta',
            'total_orders' => 6,
            'total_spent' => 285000,
            'status' => 'active'
        ]);

        Member::create([
            'name' => 'Siti Rahma',
            'email' => 'siti.rahma@yahoo.com',
            'phone' => '085711223344',
            'address' => 'Apartemen Senayan Tower B Lt 7 No 702, Jl. Asia Afrika',
            'total_orders' => 4,
            'total_spent' => 180000,
            'status' => 'active'
        ]);

        Member::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi.lestari@gmail.com',
            'phone' => '081388772211',
            'address' => 'Jl. Kemang Raya No. 42, RT 02 / RW 05, Bangka',
            'total_orders' => 9,
            'total_spent' => 450000,
            'status' => 'active'
        ]);

        Member::create([
            'name' => 'Bambang Wijaya',
            'email' => 'bambang.wijaya@outlook.com',
            'phone' => '081234567890',
            'address' => 'Jl. Melati No. 18, Kebayoran Baru, Jakarta Selatan',
            'total_orders' => 3,
            'total_spent' => 125000,
            'status' => 'active'
        ]);

        // 6. Settings Default
        Setting::set('company_name', 'Pandawa Delivery & Jastip');
        Setting::set('company_phone', '081234567890');
        Setting::set('company_email', 'admin@pandawa.com');
        Setting::set('company_address', 'Jl. Jendral Sudirman No. 45, Jakarta Pusat');
        Setting::set('delivery_base_rate', '10000');
        Setting::set('delivery_per_km_rate', '2500');
        Setting::set('jastip_fee', '10000');
        Setting::set('service_fee', '1000');

        // 7. Sample Initial Orders
        $order1 = Order::create([
            'order_code' => 'PDW-849201',
            'order_type' => 'catalog',
            'customer_name' => 'Ahmad Fauzi',
            'customer_phone' => '081299887766',
            'customer_address' => 'Jl. Jendral Sudirman No. 45, Tower A Lt. 8, Jakarta',
            'notes' => 'Tolong antar ke resepsionis ya mas kurir',
            'courier_id' => $courier1->id,
            'subtotal' => 39000,
            'shipping_cost' => 8000,
            'service_fee' => 1000,
            'discount' => 0,
            'total_amount' => 48000,
            'payment_method' => 'COD',
            'payment_status' => 'unpaid',
            'status' => 'on_delivery',
            'current_step' => 3
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => 1,
            'item_name' => 'Nasi Ayam Geprek Sambal Korek Pandawa',
            'price' => 22000,
            'quantity' => 1,
            'subtotal' => 22000,
            'notes' => 'Pedas sedang'
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => 3,
            'item_name' => 'Es Kopi Susu Gula Aren Pandawa 500ml',
            'price' => 17000,
            'quantity' => 1,
            'subtotal' => 17000,
            'notes' => 'Kurang manis'
        ]);

        // Sample Delivery Order
        $order2 = Order::create([
            'order_code' => 'PDW-DEL-291039',
            'order_type' => 'delivery',
            'customer_name' => 'Siti Rahma',
            'customer_phone' => '085711223344',
            'customer_address' => 'Apartemen Senayan Tower B Lt 7 No 702',
            'notes' => 'Dokumen penting, hati-hati basah',
            'courier_id' => $courier3->id,
            'subtotal' => 0,
            'shipping_cost' => 15000,
            'service_fee' => 1000,
            'discount' => 0,
            'total_amount' => 16000,
            'payment_method' => 'QRIS',
            'payment_status' => 'paid',
            'status' => 'completed',
            'current_step' => 4
        ]);

        Delivery::create([
            'order_id' => $order2->id,
            'sender_name' => 'Siti Rahma',
            'sender_phone' => '085711223344',
            'sender_address' => 'Jl. Kebon Jeruk No. 12',
            'receiver_name' => 'Kantor Notaris Indah',
            'receiver_phone' => '081288990011',
            'receiver_address' => 'Apartemen Senayan Tower B Lt 7 No 702',
            'package_type' => 'Dokumen',
            'armada_type' => 'motor_instant',
            'distance_km' => 5.0
        ]);

        // Sample Jastip Order
        $order3 = Order::create([
            'order_code' => 'PDW-JAS-554210',
            'order_type' => 'jastip',
            'customer_name' => 'Dewi Lestari',
            'customer_phone' => '081388772211',
            'customer_address' => 'Jl. Kemang Raya No. 42, Bangka',
            'notes' => 'Minta nota toko asli',
            'courier_id' => $courier2->id,
            'subtotal' => 145000,
            'shipping_cost' => 12000,
            'service_fee' => 1000,
            'discount' => 0,
            'total_amount' => 168000,
            'payment_method' => 'COD (Talangan Kurir)',
            'payment_status' => 'unpaid',
            'status' => 'assigned',
            'current_step' => 2
        ]);

        Jastip::create([
            'order_id' => $order3->id,
            'store_name' => 'Pasar Mayestik - Daging & Bumbu',
            'store_address' => 'Jl. Tebah III, Kebayoran Baru',
            'destination_address' => 'Jl. Kemang Raya No. 42, Bangka',
            'shopping_notes' => 'Pilih daging segar',
            'talangan_amount' => 145000,
            'jastip_fee' => 10000
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'item_name' => 'Daging Sapi Segar 1 Kg',
            'price' => 130000,
            'quantity' => 1,
            'subtotal' => 130000
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'item_name' => 'Bumbu Gulai Spesial Padang',
            'price' => 15000,
            'quantity' => 1,
            'subtotal' => 15000
        ]);
    }
}
