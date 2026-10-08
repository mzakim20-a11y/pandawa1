<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // 2. Couriers (Kurir Dedicated Pandawa)
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('vehicle');
            $table->string('plate_number');
            $table->decimal('rating', 3, 2)->default(4.90);
            $table->string('total_trips')->default('100+');
            $table->string('avatar')->nullable();
            $table->string('status')->default('available'); // available, busy, offline
            $table->string('badge')->nullable();
            $table->timestamps();
        });

        // 3. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->decimal('rating', 3, 2)->default(4.90);
            $table->string('sold_count')->default('100+');
            $table->string('prep_time')->default('15-20 mnt');
            $table->string('image')->nullable();
            $table->string('badge')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // 4. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('order_type'); // catalog, delivery, jastip
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->text('notes')->nullable();
            $table->foreignId('courier_id')->nullable()->constrained('couriers')->onDelete('set null');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('service_fee', 12, 2)->default(1000);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('payment_method')->default('COD'); // COD, QRIS, Transfer
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->string('status')->default('pending'); // pending, assigned, on_delivery, completed, cancelled
            $table->tinyInteger('current_step')->default(1); // 1 to 4
            $table->timestamps();
        });

        // 5. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('item_name');
            $table->decimal('price', 12, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Deliveries (Detail Pengiriman Paket / Dokumen)
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->text('sender_address');
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('receiver_address');
            $table->string('package_type');
            $table->text('package_note')->nullable();
            $table->string('armada_type'); // motor_instant, motor_cargo, mobil
            $table->decimal('distance_km', 5, 2);
            $table->timestamps();
        });

        // 7. Jastips (Detail Layanan Jastip Belanja)
        Schema::create('jastips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('store_name');
            $table->text('store_address')->nullable();
            $table->text('destination_address');
            $table->text('shopping_notes')->nullable();
            $table->decimal('talangan_amount', 12, 2);
            $table->decimal('jastip_fee', 12, 2);
            $table->timestamps();
        });

        // 8. Vouchers
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount_amount', 12, 2);
            $table->string('discount_type')->default('fixed'); // fixed, percent
            $table->decimal('min_order', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('jastips');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('couriers');
        Schema::dropIfExists('categories');
    }
};
