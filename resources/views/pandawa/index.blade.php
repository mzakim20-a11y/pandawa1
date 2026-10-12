<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pandawa Delivery & Jastip - Layanan Cepat Antar & Titip Belanja</title>
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  
  <!-- Font Awesome 6 Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Google Font: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            navy: {
              50: '#F0F4F8',
              100: '#D9E2EC',
              200: '#BCCCDC',
              300: '#9FB3C8',
              400: '#627D98',
              500: '#334E68',
              600: '#243B53',
              700: '#1A365D',
              800: '#0F2942',
              900: '#0B1D32',
              950: '#06101E',
            },
            pandawa: {
              blue: '#1E3A8A',
              light: '#3B82F6',
              accent: '#F59E0B',
              dark: '#0F172A',
            }
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <style>
    body {
      font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      -webkit-tap-highlight-color: transparent;
    }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 9999px; }
    ::-webkit-scrollbar-thumb:hover { background: #64748b; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    @keyframes pulse-navy {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(30, 58, 138, 0.7); }
      70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(30, 58, 138, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(30, 58, 138, 0); }
    }
    .pulse-navy { animation: pulse-navy 2s infinite cubic-bezier(0.4, 0, 0.6, 1); }

    @keyframes bike-ride {
      0% { left: 10%; transform: translateY(-50%) scaleX(1); }
      48% { left: 85%; transform: translateY(-50%) scaleX(1); }
      50% { left: 85%; transform: translateY(-50%) scaleX(-1); }
      98% { left: 10%; transform: translateY(-50%) scaleX(-1); }
      100% { left: 10%; transform: translateY(-50%) scaleX(1); }
    }
    .animate-courier-marker { animation: bike-ride 12s ease-in-out infinite; }

    @keyframes dash-animation { to { stroke-dashoffset: -40; } }
    .dashed-route-navy { stroke-dasharray: 6, 6; animation: dash-animation 1.5s linear infinite; }

    .card-hover {
      transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .card-hover:hover {
      transform: translateY(-3px);
      box-shadow: 0 14px 28px -6px rgba(15, 29, 50, 0.12), 0 8px 12px -6px rgba(15, 29, 50, 0.06);
    }
  </style>
</head>
<body class="bg-slate-100/80 text-slate-900 min-h-screen flex flex-col font-sans antialiased pb-20 md:pb-8">

  <!-- ================= TOP APP BAR / HEADER (NAVY THEME) ================= -->
  <header class="sticky top-0 z-40 bg-navy-950 text-white border-b border-navy-800 shadow-md">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
      
      <!-- Brand Logo Pandawa -->
      <div class="flex items-center gap-3 cursor-pointer" onclick="switchTab('home')">
        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-700 via-navy-700 to-amber-500 flex items-center justify-center text-white shadow-lg shadow-navy-900/50 border border-white/10">
          <i class="fa-solid fa-bolt-lightning text-xl text-amber-300"></i>
        </div>
        <div>
          <div class="flex items-center gap-2">
            <span class="text-xl font-black tracking-tight text-white">PANDAWA</span>
            <span class="text-[10px] uppercase font-extrabold bg-blue-900 text-blue-200 border border-blue-600/40 px-2 py-0.5 rounded-full">Delivery & Jastip</span>
          </div>
          <p class="text-[11px] text-slate-300 hidden sm:block">Katalog Makanan • Antar Paket • Titip Belanja Cepat</p>
        </div>
      </div>

      <!-- Quick Delivery Address Widget -->
      <div class="hidden md:flex items-center gap-2 bg-navy-900 border border-navy-800 hover:border-navy-700 px-3.5 py-1.5 rounded-full text-xs font-medium text-slate-200 transition cursor-pointer" onclick="openAddressPrompt()">
        <i class="fa-solid fa-location-dot text-amber-400"></i>
        <span class="text-slate-400">Kirim ke:</span>
        <span id="currentAddressHeader" class="font-bold text-white truncate max-w-[200px]">Jl. Jend. Sudirman No. 45</span>
        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
      </div>

      <!-- Right Header Actions (Masuk/Daftar & Cart) -->
      <div class="flex items-center gap-2">
        <!-- Tombol Masuk / Daftar Customer -->
        @auth('member')
          <span class="hidden md:inline text-xs text-slate-300">Halo, <strong class="text-white">{{ auth('member')->user()->name }}</strong></span>
          <form method="POST" action="{{ route('customer.logout') }}">
            @csrf
            <button type="submit" class="px-3 py-2 text-xs font-bold rounded-xl border border-navy-700 text-slate-200 hover:bg-navy-800 transition">
              Keluar
            </button>
          </form>
        @else
          <a href="{{ route('customer.login') }}" class="px-3 py-2 text-xs font-bold rounded-xl border border-navy-700 text-slate-200 hover:bg-navy-800 transition">
            Masuk
          </a>
          <a href="{{ route('customer.register') }}" class="px-3 py-2 text-xs font-extrabold rounded-xl bg-amber-500 hover:bg-amber-400 text-navy-950 transition">
            Daftar
          </a>
        @endauth

        <!-- Tombol Keranjang Belanja -->
        <button onclick="toggleCartModal(true)" class="relative flex items-center gap-2 bg-gradient-to-r from-blue-600 to-navy-700 hover:from-blue-500 hover:to-navy-600 text-white px-4 py-2 rounded-xl text-sm font-bold transition active:scale-95 shadow-md border border-blue-500/30">
          <i class="fa-solid fa-basket-shopping text-base text-amber-300"></i>
          <span class="hidden sm:inline">Keranjang</span>
          <span id="cartCountBadge" class="w-5 h-5 rounded-full bg-amber-400 text-navy-950 text-[11px] font-black flex items-center justify-center -mr-1 shadow">0</span>
        </button>
      </div>

    </div>

    <!-- Desktop Navigation Tabs (Navy Styled) -->
    <nav class="hidden md:block border-t border-navy-900 bg-navy-900/90 backdrop-blur-md">
      <div class="max-w-6xl mx-auto px-4 flex gap-1">
        <button onclick="switchTab('home')" class="nav-tab active-tab px-4 py-2.5 text-xs sm:text-sm font-bold flex items-center gap-2 border-b-2 border-amber-400 text-amber-400 transition" data-tab="home">
          <i class="fa-solid fa-house"></i> Beranda
        </button>
        <button onclick="switchTab('catalog')" class="nav-tab px-4 py-2.5 text-xs sm:text-sm font-semibold flex items-center gap-2 border-b-2 border-transparent text-slate-300 hover:text-white transition" data-tab="catalog">
          <i class="fa-solid fa-utensils"></i> Katalog Belanja Makanan & Sembako
        </button>
        <button onclick="switchTab('delivery')" class="nav-tab px-4 py-2.5 text-xs sm:text-sm font-semibold flex items-center gap-2 border-b-2 border-transparent text-slate-300 hover:text-white transition" data-tab="delivery">
          <i class="fa-solid fa-truck-fast"></i> Layanan Delivery (Antar Barang)
        </button>
        <button onclick="switchTab('jastip')" class="nav-tab px-4 py-2.5 text-xs sm:text-sm font-semibold flex items-center gap-2 border-b-2 border-transparent text-slate-300 hover:text-white transition" data-tab="jastip">
          <i class="fa-solid fa-bag-shopping"></i> Layanan Jastip Belanja
        </button>
        <button onclick="switchTab('tracking')" class="nav-tab px-4 py-2.5 text-xs sm:text-sm font-semibold flex items-center gap-2 border-b-2 border-transparent text-slate-300 hover:text-white transition" data-tab="tracking">
          <i class="fa-solid fa-map-location-dot"></i> Lacak Kurir Pandawa <span class="w-2 h-2 rounded-full bg-amber-400 pulse-navy ml-1"></span>
        </button>
        <button onclick="switchTab('history')" class="nav-tab px-4 py-2.5 text-xs sm:text-sm font-semibold flex items-center gap-2 border-b-2 border-transparent text-slate-300 hover:text-white transition" data-tab="history">
          <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pesanan
        </button>
      </div>
    </nav>
  </header>

  <!-- ================= MAIN CONTENT CONTAINER ================= -->
  <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-6">

    <!-- ================= 1. TAB: BERANDA (HOME) ================= -->
    <section id="tab-home" class="tab-content space-y-6">
      
      <!-- Greeting Banner (Deep Navy Gradient with Gold Accents) -->
      <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-navy-950 via-navy-900 to-blue-950 text-white p-6 sm:p-8 shadow-2xl border border-navy-800">
        <div class="relative z-10 max-w-xl space-y-3">
          <div class="inline-flex items-center gap-2 bg-blue-900/60 border border-blue-500/40 backdrop-blur-md px-3.5 py-1 rounded-full text-xs font-bold text-amber-300">
            <i class="fa-solid fa-shield-halved"></i> Resmi Pandawa Delivery & Jastip
          </div>
          <h1 class="text-2xl sm:text-4xl font-extrabold leading-tight tracking-tight">
            Antar Barang, Belanja Katalog & Jastip Apa Saja Cepat Sampai!
          </h1>
          <p class="text-xs sm:text-sm text-slate-300">
            Armada kurir internal Pandawa siap menjemput dan mengantar pesanan Anda dengan aman, cepat, dan tarif transparan langsung tercatat ke sistem database.
          </p>
          <div class="pt-2 flex flex-wrap gap-3">
            <button onclick="switchTab('catalog')" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-navy-950 font-black px-5 py-2.5 rounded-xl text-xs sm:text-sm transition shadow-lg active:scale-95 flex items-center gap-2">
              <i class="fa-solid fa-store"></i> Belanja Katalog
            </button>
            <button onclick="switchTab('delivery')" class="bg-navy-800/80 hover:bg-navy-800 border border-blue-400/30 text-white font-bold px-5 py-2.5 rounded-xl text-xs sm:text-sm transition active:scale-95 flex items-center gap-2">
              <i class="fa-solid fa-truck-fast"></i> Order Kurir Delivery
            </button>
            <button onclick="switchTab('jastip')" class="bg-blue-950 hover:bg-blue-900 border border-amber-400/40 text-amber-300 font-bold px-5 py-2.5 rounded-xl text-xs sm:text-sm transition active:scale-95 flex items-center gap-2">
              <i class="fa-solid fa-bag-shopping"></i> Titip Belanja (Jastip)
            </button>
          </div>
        </div>
        
        <!-- Background Graphic Pattern -->
        <div class="absolute -right-8 -bottom-10 opacity-10 pointer-events-none text-[220px] text-blue-300">
          <i class="fa-solid fa-motorcycle"></i>
        </div>
      </div>

      <!-- Quick Action Cards Grid (Navy Cards) -->
      <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm">
        <h2 class="text-sm font-extrabold text-navy-950 uppercase tracking-wider mb-4 flex items-center gap-2">
          <i class="fa-solid fa-layer-group text-blue-700"></i> Layanan Pandawa
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
          
          <!-- Menu 1: GoKatalog -->
          <div onclick="switchTab('catalog')" class="card-hover p-4 rounded-2xl bg-gradient-to-b from-blue-50/50 to-white border border-blue-100 cursor-pointer flex flex-col items-center text-center group">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-navy-900 to-blue-700 text-white flex items-center justify-center text-2xl shadow-md group-hover:scale-110 transition duration-200">
              <i class="fa-solid fa-utensils text-amber-300"></i>
            </div>
            <div class="font-bold text-navy-950 text-sm mt-3">Katalog Belanja</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Makanan & Sembako DB</div>
            <span class="mt-2 text-[10px] font-extrabold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-full">Pesan Instan</span>
          </div>

          <!-- Menu 2: Delivery Antar Barang -->
          <div onclick="switchTab('delivery')" class="card-hover p-4 rounded-2xl bg-gradient-to-b from-slate-50/60 to-white border border-slate-200 cursor-pointer flex flex-col items-center text-center group">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-navy-800 to-navy-950 text-white flex items-center justify-center text-2xl shadow-md group-hover:scale-110 transition duration-200">
              <i class="fa-solid fa-truck-fast text-blue-300"></i>
            </div>
            <div class="font-bold text-navy-950 text-sm mt-3">Pandawa Delivery</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Antar Titik ke Titik</div>
            <span class="mt-2 text-[10px] font-extrabold text-navy-700 bg-slate-200 px-2 py-0.5 rounded-full">Hitung Tarif KM</span>
          </div>

          <!-- Menu 3: Pandawa Jastip -->
          <div onclick="switchTab('jastip')" class="card-hover p-4 rounded-2xl bg-gradient-to-b from-amber-50/40 to-white border border-amber-100 cursor-pointer flex flex-col items-center text-center group">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-600 to-yellow-500 text-navy-950 flex items-center justify-center text-2xl shadow-md group-hover:scale-110 transition duration-200">
              <i class="fa-solid fa-bag-shopping text-white"></i>
            </div>
            <div class="font-bold text-navy-950 text-sm mt-3">Pandawa Jastip</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Titip Beli dari Mana Aja</div>
            <span class="mt-2 text-[10px] font-extrabold text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">Talangan Kurir</span>
          </div>

          <!-- Menu 4: Live Kurir Status -->
          <div onclick="switchTab('tracking')" class="card-hover p-4 rounded-2xl bg-gradient-to-b from-blue-50/40 to-white border border-blue-100 cursor-pointer flex flex-col items-center text-center group">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-800 to-indigo-950 text-white flex items-center justify-center text-2xl shadow-md group-hover:scale-110 transition duration-200">
              <i class="fa-solid fa-map-location-dot text-amber-300"></i>
            </div>
            <div class="font-bold text-navy-950 text-sm mt-3">Lacak Kurir</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Posisi Kurir Realtime</div>
            <span class="mt-2 text-[10px] font-extrabold text-blue-800 bg-blue-100 px-2 py-0.5 rounded-full">Live Tracking</span>
          </div>

        </div>
      </div>

      <!-- Dedicated Kurir Pandawa Spotlight (Loaded from Database) -->
      <div class="bg-gradient-to-br from-navy-950 via-navy-900 to-navy-800 text-white rounded-3xl p-5 sm:p-6 shadow-xl border border-navy-800">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center gap-4">
            <div class="relative">
              <img src="{{ $couriers->first()->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=80' }}" alt="Kurir Pandawa" class="w-16 h-16 rounded-2xl object-cover border-2 border-amber-400 shadow-md">
              <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-amber-400 rounded-full border-2 border-navy-950 flex items-center justify-center text-[10px] text-navy-950 font-bold">
                <i class="fa-solid fa-check"></i>
              </span>
            </div>
            <div>
              <div class="inline-flex items-center gap-1.5 text-xs text-amber-400 font-bold mb-0.5">
                <i class="fa-solid fa-shield-halved"></i> Kurir Resmi Pandawa Delivery
              </div>
              <h3 class="text-base sm:text-lg font-extrabold">{{ $couriers->first()->name ?? 'Budi Santoso' }} & Tim Armada</h3>
              <p class="text-xs text-slate-300">
                Setiap pesanan diantar langsung oleh kurir internal kami dengan armada {{ $couriers->first()->vehicle ?? 'Motor Khusus' }} ber-box higienis.
              </p>
            </div>
          </div>
          <div class="flex items-center gap-3 w-full sm:w-auto">
            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $couriers->first()->phone ?? '081234567890') }}?text=Halo%20Kurir%20Pandawa,%20saya%20ingin%20tanya%20layanan%20pengantaran" target="_blank" class="flex-1 sm:flex-initial bg-amber-500 hover:bg-amber-400 text-navy-950 text-xs font-black px-4 py-2.5 rounded-xl transition flex items-center justify-center gap-2 shadow-md">
              <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp Driver
            </a>
            <button onclick="switchTab('delivery')" class="flex-1 sm:flex-initial bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition">
              Cek Tarif
            </button>
          </div>
        </div>
      </div>

      <!-- Featured Products from Database -->
      <div class="space-y-3">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-lg font-bold text-navy-950">Produk Terlaris Pandawa (Database)</h2>
            <p class="text-xs text-slate-500">Paling banyak dipesan & siap diantar cepat oleh kurir</p>
          </div>
          <button onclick="switchTab('catalog')" class="text-xs font-bold text-blue-700 hover:text-blue-800 flex items-center gap-1">
            Lihat Semua <i class="fa-solid fa-chevron-right text-[10px]"></i>
          </button>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
          @foreach($featuredProducts as $fp)
            <div class="card-hover bg-white rounded-3xl border border-slate-200 overflow-hidden flex flex-col justify-between shadow-sm group">
              <div class="relative w-full h-36 sm:h-44 bg-slate-100 overflow-hidden">
                <img src="{{ $fp->image }}" alt="{{ $fp->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @if($fp->badge)
                  <span class="absolute top-2.5 left-2.5 bg-navy-900/90 text-amber-300 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full shadow border border-amber-400/30">
                    {{ $fp->badge }}
                  </span>
                @endif
                <div class="absolute bottom-2 left-2 bg-navy-950/80 text-white text-[10px] font-semibold px-2 py-0.5 rounded-lg flex items-center gap-1">
                  <i class="fa-solid fa-clock text-[9px] text-amber-400"></i> {{ $fp->prep_time }}
                </div>
              </div>
              <div class="p-3.5 sm:p-4 flex-1 flex flex-col justify-between">
                <div>
                  <div class="flex items-center gap-1 text-xs text-slate-500 mb-1">
                    <span class="text-amber-500 font-bold text-[11px]"><i class="fa-solid fa-star text-[10px]"></i> {{ $fp->rating }}</span>
                    <span>•</span>
                    <span class="text-[11px]">{{ $fp->sold_count }} terjual</span>
                  </div>
                  <h3 class="font-bold text-navy-950 text-xs sm:text-sm line-clamp-2">{{ $fp->name }}</h3>
                </div>
                <div class="pt-3 mt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                  <div>
                    @if($fp->original_price)
                      <span class="text-[10px] text-slate-400 line-through block leading-none">Rp {{ number_format($fp->original_price, 0, ',', '.') }}</span>
                    @endif
                    <span class="text-xs sm:text-sm font-extrabold text-navy-900">Rp {{ number_format($fp->price, 0, ',', '.') }}</span>
                  </div>
                  <button onclick="addToCart({{ $fp->id }}, '{{ addslashes($fp->name) }}', {{ $fp->price }}, '{{ $fp->image }}')" class="bg-navy-900 hover:bg-blue-900 text-white w-8 h-8 rounded-xl flex items-center justify-center transition active:scale-90 shadow">
                    <i class="fa-solid fa-plus text-xs"></i>
                  </button>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

    </section>

    <!-- ================= 2. TAB: KATALOG BELANJA ================= -->
    <section id="tab-catalog" class="tab-content hidden space-y-6">
      
      <!-- Catalog Search & Filter Header -->
      <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row gap-3">
          
          <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input 
              type="text" 
              id="catalogSearchInput" 
              oninput="fetchProductsFromDB()" 
              placeholder="Cari makanan pandawa, ayam geprek, sembako, kopi..." 
              class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-navy-600/20 focus:border-navy-700 transition"
            >
          </div>

          <div class="flex gap-2">
            <select id="catalogSortSelect" onchange="fetchProductsFromDB()" class="bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2.5 text-xs sm:text-sm font-medium text-slate-700 focus:outline-none focus:border-navy-700 cursor-pointer">
              <option value="popular">Paling Populer</option>
              <option value="price-asc">Harga Terendah</option>
              <option value="price-desc">Harga Tertinggi</option>
              <option value="rating">Rating Tertinggi</option>
            </select>
          </div>

        </div>

        <!-- Category Pills (From Database) -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar text-xs font-semibold" id="categoryPillContainer">
          <button onclick="filterCategoryBySlug('all')" class="cat-pill active-pill px-4 py-2 rounded-xl whitespace-nowrap bg-navy-950 text-amber-300 transition shadow-sm border border-amber-400/30" data-category="all">
            <i class="fa-solid fa-border-all mr-1.5"></i> Semua Kategori
          </button>
          @foreach($categories as $cat)
            <button onclick="filterCategoryBySlug('{{ $cat->slug }}')" class="cat-pill px-4 py-2 rounded-xl whitespace-nowrap bg-slate-100 hover:bg-slate-200 text-slate-700 transition" data-category="{{ $cat->slug }}">
              <i class="fa-solid {{ $cat->icon }} mr-1.5 text-blue-700"></i> {{ $cat->name }}
            </button>
          @endforeach
        </div>
      </div>

      <!-- Product Results Grid -->
      <div id="catalogGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <!-- Rendered dynamically from MySQL via API / Blade -->
      </div>

      <!-- Empty State -->
      <div id="catalogEmptyState" class="hidden text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 space-y-3">
        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto">
          <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <h3 class="text-base font-bold text-slate-700">Produk Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto">Gunakan layanan Jastip Pandawa untuk meminta kurir kami membelikannya dari toko atau pasar manapun!</p>
        <button onclick="switchTab('jastip')" class="mt-2 bg-navy-900 text-amber-300 font-bold text-xs px-4 py-2 rounded-xl">
          Buka Form Jastip
        </button>
      </div>

    </section>

    <!-- ================= 3. TAB: LAYANAN DELIVERY (ANTAR BARANG) ================= -->
    <section id="tab-delivery" class="tab-content hidden space-y-6">
      
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Form Delivery -->
        <div class="lg:col-span-7 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-6">
          <div>
            <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-900 text-xs font-bold px-3 py-1 rounded-full mb-2">
              <i class="fa-solid fa-truck-fast"></i> Pandawa Delivery Kurir
            </div>
            <h2 class="text-xl font-extrabold text-navy-950">Pesan Pengantaran Barang / Dokumen</h2>
            <p class="text-xs text-slate-500">Kurir Pandawa langsung menjemput barang di titik penjemputan dan mengantar langsung ke alamat tujuan.</p>
          </div>

          <form id="deliveryForm" onsubmit="submitDeliveryToDB(event)" class="space-y-4">
            
            <!-- Lokasi Jemput -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
              <div class="flex items-center gap-2 text-xs font-bold text-navy-950 uppercase tracking-wider">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> Titik Penjemputan (Pengirim)
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <input type="text" id="delivSenderName" required placeholder="Nama Pengirim" class="p-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none" value="Ahmad Pratama">
                <input type="tel" id="delivSenderPhone" required placeholder="No. HP Pengirim" class="p-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none" value="081299887766">
              </div>
              <textarea id="delivSenderAddress" required rows="2" placeholder="Alamat Lengkap Penjemputan & Patokan" class="w-full p-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none">Jl. Melati No. 18, Kebayoran Baru (Depan Masjid Al-Ikhlas)</textarea>
            </div>

            <!-- Lokasi Antar -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
              <div class="flex items-center gap-2 text-xs font-bold text-navy-950 uppercase tracking-wider">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span> Titik Pengantaran (Penerima)
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <input type="text" id="delivReceiverName" required placeholder="Nama Penerima" class="p-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none" value="Dewi Lestari">
                <input type="tel" id="delivReceiverPhone" required placeholder="No. HP Penerima" class="p-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none" value="085711223344">
              </div>
              <textarea id="delivReceiverAddress" required rows="2" placeholder="Alamat Lengkap Pengantaran & Patokan" class="w-full p-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none">Apartemen Senayan Tower B Lt 7 No 702, Jl. Asia Afrika</textarea>
            </div>

            <!-- Info Paket -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Jenis / Kategori Barang</label>
                <select id="delivPackageType" class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none">
                  <option value="Dokumen">📄 Dokumen / Surat Penting</option>
                  <option value="Makanan">🍱 Makanan / Minuman</option>
                  <option value="Pakaian">👕 Pakaian / Tekstil</option>
                  <option value="Elektronik">📱 Barang Elektronik / Gadget</option>
                  <option value="Pecah Belah">📦 Barang Lain / Dus</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan untuk Kurir</label>
                <input type="text" id="delivPackageNote" placeholder="Contoh: Titip ke satpam / Barang rapuh" class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none">
              </div>
            </div>

            <!-- Pilihan Armada -->
            <div class="space-y-3">
              <label class="block text-xs font-bold text-slate-700">Pilih Armada Kurir Pandawa:</label>
              <div class="grid grid-cols-3 gap-2">
                
                <label class="cursor-pointer border-2 border-navy-700 bg-blue-50/60 p-3 rounded-2xl flex flex-col items-center text-center transition armada-option" onclick="selectArmada('motor_instant', this)">
                  <input type="radio" name="armada" value="motor_instant" checked class="hidden">
                  <i class="fa-solid fa-motorcycle text-xl text-blue-700 mb-1"></i>
                  <span class="text-xs font-bold text-navy-950">Motor Instant</span>
                  <span class="text-[10px] text-slate-500">Maks 5 Kg</span>
                </label>

                <label class="cursor-pointer border-2 border-slate-200 bg-white hover:bg-slate-50 p-3 rounded-2xl flex flex-col items-center text-center transition armada-option" onclick="selectArmada('motor_cargo', this)">
                  <input type="radio" name="armada" value="motor_cargo" class="hidden">
                  <i class="fa-solid fa-box-open text-xl text-amber-600 mb-1"></i>
                  <span class="text-xs font-bold text-navy-950">Motor Kargo</span>
                  <span class="text-[10px] text-slate-500">Maks 20 Kg (Box)</span>
                </label>

                <label class="cursor-pointer border-2 border-slate-200 bg-white hover:bg-slate-50 p-3 rounded-2xl flex flex-col items-center text-center transition armada-option" onclick="selectArmada('mobil', this)">
                  <input type="radio" name="armada" value="mobil" class="hidden">
                  <i class="fa-solid fa-car text-xl text-navy-700 mb-1"></i>
                  <span class="text-xs font-bold text-navy-950">Mobil Kurir</span>
                  <span class="text-[10px] text-slate-500">Maks 100 Kg</span>
                </label>

              </div>
            </div>

            <!-- Estimasi Jarak Slider -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
              <div class="flex justify-between items-center text-xs">
                <span class="font-bold text-slate-700">Estimasi Jarak Pengantaran:</span>
                <span class="font-extrabold text-blue-700 text-sm"><span id="deliveryKmDisplay">5</span> KM</span>
              </div>
              <input type="range" id="deliveryKmSlider" min="1" max="30" value="5" step="0.5" oninput="updateDeliveryCalculation()" class="w-full accent-navy-700 cursor-pointer">
              <div class="flex justify-between text-[10px] text-slate-400">
                <span>1 KM</span>
                <span>15 KM</span>
                <span>30 KM</span>
              </div>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-navy-900 to-blue-800 hover:from-navy-800 hover:to-blue-700 text-white font-bold rounded-2xl shadow-lg transition active:scale-95 flex items-center justify-center gap-2">
              <i class="fa-solid fa-motorcycle text-amber-300"></i> Simpan ke DB & Pesan Kurir (<span id="btnDeliveryTotal">Rp 16.000</span>)
            </button>

          </form>
        </div>

        <!-- Right: Ringkasan Biaya & Profil Kurir -->
        <div class="lg:col-span-5 space-y-5">
          
          <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-navy-950 flex items-center gap-2">
              <i class="fa-solid fa-receipt text-blue-700"></i> Rincian Biaya Pengantaran Pandawa
            </h3>
            
            <div class="space-y-2 text-xs">
              <div class="flex justify-between text-slate-600">
                <span>Tarif Dasar (<span id="calcArmadaName">Motor Instant</span>)</span>
                <span id="calcBasePrice" class="font-semibold text-slate-800">Rp 10.000</span>
              </div>
              <div class="flex justify-between text-slate-600">
                <span>Jarak Tambahan (<span id="calcExtraKm">2</span> km x <span id="calcRatePerKm">Rp 2.500</span>)</span>
                <span id="calcExtraPrice" class="font-semibold text-slate-800">Rp 5.000</span>
              </div>
              <div class="flex justify-between text-slate-600">
                <span>Biaya Layanan & Asuransi</span>
                <span class="font-semibold text-slate-800">Rp 1.000</span>
              </div>
              <div class="border-t border-dashed border-slate-200 pt-2 flex justify-between text-sm font-bold text-navy-950">
                <span>Total Biaya Kurir:</span>
                <span id="calcTotalPrice" class="text-blue-700 text-base">Rp 16.000</span>
              </div>
            </div>

            <div class="p-3 bg-blue-50 border border-blue-100 rounded-xl text-blue-900 text-[11px] flex items-center gap-2">
              <i class="fa-solid fa-circle-check text-blue-700 text-sm"></i>
              <span>Data pengiriman otomatis tersimpan di tabel `orders` & `deliveries`.</span>
            </div>
          </div>

          <!-- Kurir Assigned -->
          <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kurir yang Siap Antar</span>
              <span class="text-[10px] font-bold bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Standby
              </span>
            </div>

            <div class="flex items-center gap-3">
              <img src="{{ $couriers->first()->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=80' }}" alt="Kurir" class="w-14 h-14 rounded-2xl object-cover border border-slate-200">
              <div class="flex-1">
                <h4 class="font-bold text-navy-950 text-sm">{{ $couriers->first()->name ?? 'Budi Santoso' }}</h4>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                  <span class="flex items-center text-amber-500 font-semibold"><i class="fa-solid fa-star text-[10px] mr-1"></i> {{ $couriers->first()->rating ?? '4.96' }}</span>
                  <span>•</span>
                  <span>{{ $couriers->first()->plate_number ?? 'B 4521 PDW' }}</span>
                </div>
                <div class="text-[10px] text-blue-700 font-bold mt-0.5">{{ $couriers->first()->badge ?? 'Kurir Resmi Pandawa' }}</div>
              </div>
            </div>
          </div>

        </div>

      </div>

    </section>

    <!-- ================= 4. TAB: LAYANAN JASTIP ================= -->
    <section id="tab-jastip" class="tab-content hidden space-y-6">
      
      <!-- Jastip Header Banner (Navy & Gold) -->
      <div class="bg-gradient-to-r from-navy-950 via-navy-900 to-amber-700 text-white p-6 sm:p-8 rounded-3xl shadow-xl relative overflow-hidden border border-navy-800">
        <div class="relative z-10 max-w-xl space-y-2">
          <div class="inline-flex items-center gap-1.5 bg-amber-400 text-navy-950 px-3 py-1 rounded-full text-xs font-black">
            <i class="fa-solid fa-bag-shopping"></i> PANDAWA JASTIP
          </div>
          <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Titip Belanja dari Toko & Pasar Mana Saja</h2>
          <p class="text-xs sm:text-sm text-slate-200">
            Mau belanja di toko roti viral, pasar tradisional, atau mall? Kurir Pandawa siap membelikan, menalangi dengan nota resmi, dan mengantar langsung ke rumah Anda!
          </p>
        </div>
        <div class="absolute -right-6 -bottom-8 opacity-15 pointer-events-none text-[180px] text-white">
          <i class="fa-solid fa-bag-shopping"></i>
        </div>
      </div>

      <!-- Jastip Form -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-7 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-5">
          <h3 class="text-base font-bold text-navy-950 flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-amber-500"></i> Form Permintaan Jastip Pandawa
          </h3>

          <form id="jastipForm" onsubmit="submitJastipToDB(event)" class="space-y-4">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemesan</label>
                <input type="text" id="jastipCustName" required placeholder="Nama Anda" class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none" value="Bambang Wijaya">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">No. WhatsApp</label>
                <input type="tel" id="jastipCustPhone" required placeholder="0812xxxxxxxx" class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none" value="081234567890">
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Nama Toko / Lokasi Pembelian</label>
              <input type="text" id="jastipStoreName" required placeholder="Contoh: Pasar Mayestik / Toko Kue Ibu Haji" class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none" value="Pasar Mayestik - Kios Bumbu & Daging Segar">
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Toko (Opsional)</label>
              <input type="text" id="jastipStoreAddress" placeholder="Jl. Tebah III, Kebayoran Baru" class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none" value="Jl. Tebah III, Kebayoran Baru, Jakarta Selatan">
            </div>

            <!-- Dynamic Items -->
            <div class="space-y-3">
              <div class="flex justify-between items-center">
                <label class="text-xs font-bold text-slate-700">Daftar Barang yang Dititip Beli:</label>
                <button type="button" onclick="addJastipItemRow()" class="text-xs font-bold text-blue-700 hover:text-blue-800 flex items-center gap-1">
                  <i class="fa-solid fa-plus text-[10px]"></i> Tambah Barang
                </button>
              </div>

              <div id="jastipItemsContainer" class="space-y-2">
                <div class="jastip-item-row flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                  <input type="text" placeholder="Nama barang" class="jastip-name flex-1 text-xs bg-white p-2 border border-slate-200 rounded-lg focus:outline-none focus:border-navy-700" value="Daging Sapi Segar 1 Kg" required>
                  <input type="number" placeholder="Est. Harga" oninput="calculateJastipTotal()" class="jastip-price w-28 text-xs bg-white p-2 border border-slate-200 rounded-lg focus:outline-none focus:border-navy-700" value="130000" required>
                  <button type="button" onclick="removeJastipRow(this)" class="text-slate-400 hover:text-rose-500 px-2 py-1">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                  </button>
                </div>
                <div class="jastip-item-row flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                  <input type="text" placeholder="Nama barang" class="jastip-name flex-1 text-xs bg-white p-2 border border-slate-200 rounded-lg focus:outline-none focus:border-navy-700" value="Bumbu Gulai Spesial Padang" required>
                  <input type="number" placeholder="Est. Harga" oninput="calculateJastipTotal()" class="jastip-price w-28 text-xs bg-white p-2 border border-slate-200 rounded-lg focus:outline-none focus:border-navy-700" value="15000" required>
                  <button type="button" onclick="removeJastipRow(this)" class="text-slate-400 hover:text-rose-500 px-2 py-1">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                  </button>
                </div>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Khusus untuk Kurir</label>
              <textarea id="jastipNotes" rows="2" placeholder="Catatan untuk kurir..." class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none">Pilih potongan daging tanpa lemak dan sertakan struk belanja resmi.</textarea>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Tujuan Pengantaran (Rumah Anda)</label>
              <textarea id="jastipDestination" required rows="2" placeholder="Alamat lengkap penerima..." class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none">Jl. Kemang Raya No. 42, RT 02 / RW 05, Bangka, Mampang Prapatan</textarea>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-navy-950 font-black rounded-2xl shadow-lg transition active:scale-95 flex items-center justify-center gap-2">
              <i class="fa-solid fa-paper-plane"></i> Simpan ke DB & Kirim Pesanan Jastip
            </button>
          </form>

        </div>

        <!-- Right: Perkiraan Biaya Jastip -->
        <div class="lg:col-span-5 space-y-5">
          
          <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-navy-950 flex items-center gap-2">
              <i class="fa-solid fa-calculator text-amber-500"></i> Perkiraan Biaya Jastip Pandawa
            </h3>

            <div class="space-y-2.5 text-xs">
              <div class="flex justify-between text-slate-600">
                <span>Perkiraan Talangan Belanja:</span>
                <span id="jastipTalanganDisplay" class="font-bold text-slate-800">Rp 145.000</span>
              </div>
              <div class="flex justify-between text-slate-600">
                <span>Biaya Jasa Titip (Jastip Fee):</span>
                <span id="jastipFeeDisplay" class="font-semibold text-slate-800">Rp 10.000</span>
              </div>
              <div class="flex justify-between text-slate-600">
                <span>Ongkos Kirim Kurir Pandawa:</span>
                <span id="jastipOngkirDisplay" class="font-semibold text-slate-800">Rp 12.000</span>
              </div>
              <div class="flex justify-between text-slate-600">
                <span>Biaya Layanan Sistem:</span>
                <span class="font-semibold text-slate-800">Rp 1.000</span>
              </div>
              
              <div class="border-t border-dashed border-slate-200 pt-2 flex justify-between text-sm font-bold text-navy-950">
                <span>Total Estimasi Bayar:</span>
                <span id="jastipGrandTotalDisplay" class="text-amber-600 text-base">Rp 168.000</span>
              </div>
            </div>

            <div class="bg-navy-950 text-white p-4 rounded-2xl space-y-2 text-xs border border-navy-800">
              <div class="font-bold text-amber-300 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-info"></i> Keuntungan Jastip Pandawa:
              </div>
              <ul class="space-y-1 text-[11px] text-slate-300 list-disc list-inside">
                <li>Kurir internal terlatih belanja produk segar & teliti nota belanja.</li>
                <li>Uang talangan ditalangi kurir terlebih dahulu.</li>
                <li>Bayar tunai (COD) atau QRIS saat barang sampai di pintu Anda.</li>
                <li>Data transaksi tersimpan rapi di database Laravel MySQL.</li>
              </ul>
            </div>
          </div>

        </div>

      </div>

    </section>

    <!-- ================= 5. TAB: LIVE TRACKING (PELACAKAN KURIR) ================= -->
    <section id="tab-tracking" class="tab-content hidden space-y-6">
      
      <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
          <div>
            <div class="flex items-center gap-2">
              <h2 class="text-lg font-bold text-navy-950">Pelacakan Pesanan & Kurir Pandawa</h2>
              <span id="trackingStatusBadge" class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full pulse-navy">
                Sedang Mengantar
              </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Order ID: <span id="trackingOrderId" class="font-mono font-bold text-navy-900">PDW-849201</span> • Estimasi tiba: <span class="font-bold text-slate-800">10-15 Menit</span></p>
          </div>

          <div class="flex items-center gap-2">
            <button onclick="advanceTrackingStepInDB()" class="bg-navy-900 hover:bg-navy-800 text-amber-300 font-bold text-xs px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 shadow">
              <i class="fa-solid fa-forward-step"></i> Simulasikan Langkah (Update DB)
            </button>
          </div>
        </div>

        <!-- Simulated Map View Area (Navy Roads & Accent) -->
        <div class="relative w-full h-72 sm:h-80 bg-slate-900 rounded-3xl overflow-hidden border border-navy-800 shadow-inner flex flex-col justify-between p-4">
          
          <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#60a5fa 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>
          
          <!-- Roads SVG -->
          <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <path d="M 50 180 Q 250 80 500 180 T 950 140" fill="none" stroke="#1E293B" stroke-width="24" stroke-linecap="round"/>
            <path d="M 50 180 Q 250 80 500 180 T 950 140" fill="none" stroke="#3B82F6" stroke-width="6" class="dashed-route-navy" stroke-linecap="round"/>
          </svg>

          <!-- Top Overlay -->
          <div class="relative z-10 flex items-center justify-between bg-navy-950/90 backdrop-blur-md px-4 py-2.5 rounded-2xl shadow border border-navy-800 text-xs text-white">
            <div class="flex items-center gap-2 font-bold">
              <i class="fa-solid fa-store text-amber-400"></i>
              <span id="trackingStoreLabel">Merchant / Titik Jemput</span>
              <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
              <i class="fa-solid fa-location-dot text-rose-400"></i>
              <span id="trackingDestLabel">Alamat Rumah Pemesan</span>
            </div>
            <div class="text-amber-400 font-extrabold flex items-center gap-1">
              <i class="fa-solid fa-bolt"></i> <span>Pandawa Express</span>
            </div>
          </div>

          <!-- Animated Moving Courier Marker -->
          <div class="relative z-10 w-full my-auto flex items-center">
            <div class="absolute animate-courier-marker flex flex-col items-center">
              <div class="bg-navy-950 text-white px-2 py-1 rounded-full shadow-md text-[10px] font-bold border border-blue-500/40 mb-1 whitespace-nowrap flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> <span id="courierMarkerName">Budi Santoso</span>
              </div>
              <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-700 to-navy-900 text-amber-300 flex items-center justify-center text-lg shadow-xl ring-4 ring-blue-500/30 border border-blue-400/50">
                <i class="fa-solid fa-motorcycle"></i>
              </div>
            </div>
          </div>

          <!-- Bottom Overlay -->
          <div class="relative z-10 self-start bg-navy-950/90 backdrop-blur-md text-white px-3.5 py-1.5 rounded-xl text-xs flex items-center gap-2 shadow border border-navy-800">
            <i class="fa-solid fa-route text-blue-400"></i>
            <span>Sisa Jarak: <strong id="trackingRemainingDist" class="text-amber-300">1.8 KM</strong> lagi</span>
          </div>

        </div>

        <!-- Driver Profile Card -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <img id="trackingCourierAvatar" src="{{ $couriers->first()->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=80' }}" alt="Kurir" class="w-14 h-14 rounded-2xl object-cover border border-slate-300">
            <div>
              <div class="flex items-center gap-1.5">
                <h3 id="trackingCourierName" class="font-bold text-navy-950 text-sm">{{ $couriers->first()->name ?? 'Budi Santoso' }}</h3>
                <span class="text-[10px] font-bold bg-navy-900 text-amber-300 px-2 py-0.5 rounded-full">Kurir Resmi</span>
              </div>
              <div class="text-xs text-slate-500" id="trackingCourierVehicle">{{ $couriers->first()->vehicle ?? 'Honda Vario' }} • Plat: <span class="font-bold text-navy-900" id="trackingCourierPlate">{{ $couriers->first()->plate_number ?? 'B 4521 PDW' }}</span></div>
              <div class="flex items-center gap-1 text-amber-500 text-xs font-semibold mt-0.5">
                <i class="fa-solid fa-star text-[10px]"></i> <span id="trackingCourierRating">{{ $couriers->first()->rating ?? '4.96' }}</span> (Armada Dedicated)
              </div>
            </div>
          </div>

          <div class="flex items-center gap-2 w-full sm:w-auto">
            <a id="trackingWaBtn" href="https://wa.me/{{ preg_replace('/^0/', '62', $couriers->first()->phone ?? '081234567890') }}?text=Halo%20Kurir%20Pandawa,%20mau%20konfirmasi%20pesanan%20saya" target="_blank" class="flex-1 sm:flex-initial bg-amber-500 hover:bg-amber-400 text-navy-950 font-black text-xs px-4 py-2.5 rounded-xl transition flex items-center justify-center gap-2 shadow-sm">
              <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp Kurir
            </a>
            <a id="trackingCallBtn" href="tel:{{ $couriers->first()->phone ?? '081234567890' }}" class="flex-1 sm:flex-initial bg-navy-900 hover:bg-navy-800 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition flex items-center justify-center gap-2 shadow-sm">
              <i class="fa-solid fa-phone text-xs"></i> Telepon
            </a>
          </div>
        </div>

        <!-- 4-Step Timeline -->
        <div class="space-y-3 pt-2">
          <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Status Perjalanan Pesanan di Database</h3>
          
          <div class="grid grid-cols-1 sm:grid-cols-4 gap-3" id="timelineStepContainer">
            
            <div class="timeline-box p-3 rounded-2xl bg-blue-50 border border-blue-200 transition" id="trackStep1">
              <div class="flex items-center gap-2 text-blue-700 font-bold text-xs">
                <i class="fa-solid fa-circle-check text-sm"></i> 1. Diterima
              </div>
              <p class="text-[11px] text-slate-600 mt-1">Tersimpan di tabel `orders`</p>
            </div>

            <div class="timeline-box p-3 rounded-2xl bg-blue-50 border border-blue-200 transition" id="trackStep2">
              <div class="flex items-center gap-2 text-blue-700 font-bold text-xs">
                <i class="fa-solid fa-circle-check text-sm"></i> 2. Kurir Ditugaskan
              </div>
              <p class="text-[11px] text-slate-600 mt-1">Kurir mengambil / membeli barang</p>
            </div>

            <div class="timeline-box p-3 rounded-2xl bg-navy-900 text-white border-2 border-amber-400 shadow-md transition" id="trackStep3">
              <div class="flex items-center gap-2 text-amber-300 font-bold text-xs">
                <i class="fa-solid fa-motorcycle text-sm"></i> 3. Sedang Diantar
              </div>
              <p class="text-[11px] text-slate-300 mt-1 font-medium">Kurir otw menuju alamat Anda</p>
            </div>

            <div class="timeline-box p-3 rounded-2xl bg-slate-50 border border-slate-200 opacity-60 transition" id="trackStep4">
              <div class="flex items-center gap-2 text-slate-500 font-bold text-xs">
                <i class="fa-regular fa-circle text-sm"></i> 4. Selesai
              </div>
              <p class="text-[11px] text-slate-500 mt-1">Barang tiba & pembayaran tuntas</p>
            </div>

          </div>
        </div>

      </div>

    </section>

    <!-- ================= 6. TAB: RIWAYAT PESANAN (DATABASE) ================= -->
    <section id="tab-history" class="tab-content hidden space-y-6">
      
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-bold text-navy-950">Riwayat Pesanan (Database MySQL)</h2>
          <p class="text-xs text-slate-500">Semua riwayat pesanan Katalog, Delivery paket, dan Jastip dari database Pandawa</p>
        </div>
        <button onclick="refreshHistoryFromDB()" class="text-xs text-blue-700 hover:text-blue-800 font-bold flex items-center gap-1.5 bg-blue-50 px-3 py-1.5 rounded-xl border border-blue-200">
          <i class="fa-solid fa-rotate text-xs"></i> Refresh DB
        </button>
      </div>

      <div id="historyListContainer" class="space-y-4">
        <!-- Rendered from database -->
      </div>

    </section>

  </main>

  <!-- ================= MOBILE BOTTOM NAVIGATION (NAVY) ================= -->
  <div class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-navy-950/95 backdrop-blur-md border-t border-navy-800 px-2 py-2 flex justify-around items-center shadow-2xl">
    <button onclick="switchTab('home')" class="mobile-nav-btn flex flex-col items-center text-amber-400 text-[10px] font-bold" data-tab="home">
      <i class="fa-solid fa-house text-lg mb-0.5"></i>
      <span>Beranda</span>
    </button>
    <button onclick="switchTab('catalog')" class="mobile-nav-btn flex flex-col items-center text-slate-400 text-[10px] font-medium" data-tab="catalog">
      <i class="fa-solid fa-utensils text-lg mb-0.5"></i>
      <span>Katalog</span>
    </button>
    <button onclick="switchTab('delivery')" class="mobile-nav-btn flex flex-col items-center text-slate-400 text-[10px] font-medium" data-tab="delivery">
      <i class="fa-solid fa-truck-fast text-lg mb-0.5"></i>
      <span>Delivery</span>
    </button>
    <button onclick="switchTab('jastip')" class="mobile-nav-btn flex flex-col items-center text-slate-400 text-[10px] font-medium" data-tab="jastip">
      <i class="fa-solid fa-bag-shopping text-lg mb-0.5"></i>
      <span>Jastip</span>
    </button>
    <button onclick="switchTab('tracking')" class="mobile-nav-btn flex flex-col items-center text-slate-400 text-[10px] font-medium" data-tab="tracking">
      <div class="relative">
        <i class="fa-solid fa-map-location-dot text-lg mb-0.5"></i>
        <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-amber-400"></span>
      </div>
      <span>Lacak</span>
    </button>
  </div>

  <!-- ================= MODAL: KERANJANG BELANJA (CART DRAWER) ================= -->
  <div id="cartModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden flex justify-end transition-opacity duration-300">
    <div class="bg-white w-full max-w-md h-full flex flex-col shadow-2xl">
      
      <div class="p-4 border-b border-navy-800 flex items-center justify-between bg-navy-950 text-white">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-xl bg-amber-400 text-navy-950 flex items-center justify-center text-sm font-black">
            <i class="fa-solid fa-basket-shopping"></i>
          </div>
          <div>
            <h3 class="font-extrabold text-sm">Keranjang Pandawa</h3>
            <span class="text-[11px] text-slate-300"><span id="cartTotalItemsCount">0</span> item dipilih</span>
          </div>
        </div>
        <button onclick="toggleCartModal(false)" class="w-8 h-8 rounded-full bg-navy-800 hover:bg-navy-700 text-slate-300 flex items-center justify-center transition">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Items List -->
      <div id="cartItemsList" class="flex-1 overflow-y-auto p-4 space-y-3"></div>

      <!-- Empty View -->
      <div id="emptyCartView" class="hidden flex-1 flex flex-col items-center justify-center p-6 text-center space-y-3">
        <div class="w-20 h-20 rounded-full bg-slate-100 text-navy-800 flex items-center justify-center text-3xl">
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <h4 class="font-bold text-navy-950">Keranjang Masih Kosong</h4>
        <p class="text-xs text-slate-500 max-w-xs">Pilih menu favorit atau kebutuhan sembako di katalog untuk mulai memesan!</p>
        <button onclick="toggleCartModal(false); switchTab('catalog')" class="bg-navy-900 text-amber-300 font-bold text-xs px-4 py-2.5 rounded-xl shadow-md">
          Mulai Belanja Sekarang
        </button>
      </div>

      <!-- Footer & Voucher -->
      <div id="cartFooter" class="p-4 border-t border-slate-200 bg-white space-y-3">
        
        <!-- Voucher Promo DB Check -->
        <div class="flex gap-2">
          <input type="text" id="voucherCodeInput" placeholder="Kode Promo (misal: ONGKIRGRATIS)" class="flex-1 text-xs uppercase p-2 border border-slate-200 rounded-xl focus:outline-none focus:border-navy-700">
          <button onclick="applyVoucherFromDB()" class="bg-navy-900 hover:bg-navy-800 text-amber-300 text-xs font-black px-3.5 py-2 rounded-xl transition shadow">
            Pakai
          </button>
        </div>
        <div id="voucherMessage" class="hidden text-[11px] font-semibold"></div>

        <div class="space-y-1.5 text-xs text-slate-600 pt-1">
          <div class="flex justify-between">
            <span>Subtotal Produk:</span>
            <span id="cartSubtotalDisplay" class="font-semibold text-slate-800">Rp 0</span>
          </div>
          <div class="flex justify-between">
            <span>Ongkir Kurir Pandawa:</span>
            <span id="cartOngkirDisplay" class="font-semibold text-slate-800">Rp 8.000</span>
          </div>
          <div id="discountRow" class="hidden flex justify-between text-blue-700 font-semibold">
            <span>Diskon Voucher:</span>
            <span id="cartDiscountDisplay">- Rp 0</span>
          </div>
          <div class="flex justify-between">
            <span>Biaya Layanan:</span>
            <span class="font-semibold text-slate-800">Rp 1.000</span>
          </div>
          <div class="border-t border-slate-200 pt-2 flex justify-between text-sm font-bold text-navy-950">
            <span>Total Bayar:</span>
            <span id="cartGrandTotalDisplay" class="text-blue-700 text-base">Rp 0</span>
          </div>
        </div>

        <button onclick="openCheckoutModal()" class="w-full py-3 bg-gradient-to-r from-navy-900 to-blue-800 hover:from-navy-800 hover:to-blue-700 text-white font-bold rounded-2xl shadow-lg transition active:scale-95 flex items-center justify-center gap-2">
          <span>Lanjut ke Checkout</span>
          <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>

      </div>

    </div>
  </div>

  <!-- ================= MODAL: CHECKOUT & METODE BAYAR ================= -->
  <div id="checkoutModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
      
      <div class="p-4 border-b border-navy-800 flex items-center justify-between bg-navy-950 text-white">
        <h3 class="font-bold text-base flex items-center gap-2">
          <i class="fa-solid fa-receipt text-amber-300"></i> Konfirmasi Pesanan Pandawa
        </h3>
        <button onclick="toggleCheckoutModal(false)" class="w-8 h-8 rounded-full bg-navy-800 hover:bg-navy-700 text-slate-300 flex items-center justify-center">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <div class="p-5 overflow-y-auto space-y-4 flex-1">
        
        <div class="space-y-2">
          <label class="block text-xs font-bold text-slate-700">Nama Penerima & No. WhatsApp</label>
          <div class="grid grid-cols-2 gap-2">
            <input type="text" id="checkoutCustName" placeholder="Nama Anda" class="p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none" value="Bambang Wijaya">
            <input type="tel" id="checkoutCustPhone" placeholder="0812xxxxxxxx" class="p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none" value="081234567890">
          </div>
        </div>

        <div class="space-y-2">
          <label class="block text-xs font-bold text-slate-700">Alamat Lengkap Pengantaran</label>
          <textarea id="checkoutCustAddress" rows="2" placeholder="Jalan, No Rumah, Patokan..." class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none">Jl. Jendral Sudirman No. 45, Gedung Plaza Tower Lt. 3</textarea>
        </div>

        <div class="space-y-2">
          <label class="block text-xs font-bold text-slate-700">Catatan Khusus untuk Kurir</label>
          <input type="text" id="checkoutCustNotes" placeholder="Contoh: Titip ke satpam lobby" class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-navy-700 focus:outline-none">
        </div>

        <!-- Payment Method -->
        <div class="space-y-2 pt-2">
          <label class="block text-xs font-bold text-slate-700">Pilih Metode Pembayaran:</label>
          
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
            <label class="cursor-pointer border-2 border-navy-700 bg-blue-50/70 p-3 rounded-2xl flex flex-col items-center text-center payment-option" onclick="selectPaymentMethod('COD', this)">
              <input type="radio" name="paymentMethod" value="COD" checked class="hidden">
              <i class="fa-solid fa-hand-holding-dollar text-xl text-blue-700 mb-1"></i>
              <span class="text-xs font-bold text-navy-950">COD (Tunai)</span>
              <span class="text-[10px] text-slate-500">Bayar ke Kurir</span>
            </label>

            <label class="cursor-pointer border-2 border-slate-200 bg-white hover:bg-slate-50 p-3 rounded-2xl flex flex-col items-center text-center payment-option" onclick="selectPaymentMethod('QRIS', this)">
              <input type="radio" name="paymentMethod" value="QRIS" class="hidden">
              <i class="fa-solid fa-qrcode text-xl text-navy-800 mb-1"></i>
              <span class="text-xs font-bold text-navy-950">QRIS Pandawa</span>
              <span class="text-[10px] text-slate-500">GoPay / OVO / Dana</span>
            </label>

            <label class="cursor-pointer border-2 border-slate-200 bg-white hover:bg-slate-50 p-3 rounded-2xl flex flex-col items-center text-center payment-option" onclick="selectPaymentMethod('Transfer', this)">
              <input type="radio" name="paymentMethod" value="Transfer" class="hidden">
              <i class="fa-solid fa-building-columns text-xl text-amber-600 mb-1"></i>
              <span class="text-xs font-bold text-navy-950">Transfer Bank</span>
              <span class="text-[10px] text-slate-500">BCA / Mandiri</span>
            </label>
          </div>
        </div>

        <!-- Kurir Info -->
        <div class="p-3 bg-navy-950 text-white rounded-2xl border border-navy-800 flex items-center gap-3">
          <img src="{{ $couriers->first()->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=80' }}" alt="Kurir" class="w-10 h-10 rounded-xl object-cover border border-amber-400/40">
          <div class="text-xs">
            <span class="font-bold text-amber-300">Kurir Pengantar: {{ $couriers->first()->name ?? 'Budi Santoso' }}</span>
            <div class="text-slate-300 text-[11px]">{{ $couriers->first()->vehicle ?? 'Honda Vario' }} • {{ $couriers->first()->plate_number ?? 'B 4521 PDW' }}</div>
          </div>
        </div>

      </div>

      <!-- Action Buttons -->
      <div class="p-4 border-t border-slate-200 bg-slate-50 space-y-2">
        <button onclick="submitCatalogOrderToDB(true)" class="w-full py-3.5 bg-amber-500 hover:bg-amber-400 text-navy-950 font-black rounded-2xl shadow-lg transition active:scale-95 flex items-center justify-center gap-2">
          <i class="fa-brands fa-whatsapp text-lg"></i>
          <span>Simpan ke DB & Buka WhatsApp Driver</span>
        </button>
        <button onclick="submitCatalogOrderToDB(false)" class="w-full py-2.5 bg-navy-900 hover:bg-navy-800 text-white font-bold text-xs rounded-xl transition">
          Simpan ke Database Saja (Simulasi)
        </button>
      </div>

    </div>
  </div>

  <!-- Toast Notification Container -->
  <div id="toastContainer" class="fixed top-4 right-4 z-50 flex flex-col gap-2 pointer-events-none"></div>

  <!-- Status login customer + helper wajib login -->
  <script>
    window.IS_LOGGED_IN = @json(auth('member')->check());
    window.LOGIN_URL = @json(route('customer.login'));

    function requireLogin(evt) {
      if (window.IS_LOGGED_IN) return true;
      if (evt && evt.preventDefault) evt.preventDefault();
      alert('Silakan masuk atau daftar dulu untuk memesan.');
      window.location.href = window.LOGIN_URL;
      return false;
    }
  </script>

  <!-- Core JavaScript -->
  <script src="{{ asset('js/pandawa.js') }}"></script>

  <!-- Bungkus fungsi pesan dari pandawa.js supaya wajib login -->
  <script>
    ['openCheckoutModal', 'submitCatalogOrderToDB', 'submitDeliveryToDB', 'submitJastipToDB'].forEach(function (name) {
      var original = window[name];
      if (typeof original !== 'function') return;
      window[name] = function () {
        if (!requireLogin(arguments[0])) return;
        return original.apply(this, arguments);
      };
    });

    // Kalau server menolak pesanan (401, misalnya sesi habis), arahkan ke login
    var originalFetch = window.fetch;
    window.fetch = function () {
      var input = arguments[0];
      var url = typeof input === 'string' ? input : ((input && input.url) || '');
      return originalFetch.apply(this, arguments).then(function (res) {
        if (res.status === 401 && url.indexOf('/api/order/') !== -1) {
          window.location.href = window.LOGIN_URL;
        }
        return res;
      });
    };
  </script>
</body>
</html>