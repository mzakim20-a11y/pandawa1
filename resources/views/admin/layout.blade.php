<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Dashboard') - Pandawa Delivery & Jastip</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome Icons -->
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
            amber: {
              400: '#FBBF24',
              500: '#F59E0B',
              600: '#D97706',
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
    body { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 9999px; }
  </style>
</head>
<body class="bg-slate-100 text-slate-800 flex h-screen overflow-hidden antialiased">

  <!-- ================= SIDEBAR (NAVY THEME) ================= -->
  <aside id="adminSidebar" class="w-64 bg-navy-950 text-white flex flex-col justify-between shrink-0 transition-all duration-300 z-50 fixed md:static inset-y-0 left-0 -translate-x-full md:translate-x-0 shadow-2xl border-r border-navy-800">
    <div>
      <!-- Brand Logo -->
      <div class="p-5 border-b border-navy-800/80 flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-700 to-amber-500 flex items-center justify-center text-white shadow-lg border border-white/10">
            <i class="fa-solid fa-bolt-lightning text-amber-300 text-lg"></i>
          </div>
          <div>
            <div class="font-extrabold text-white text-base tracking-tight leading-none">PANDAWA</div>
            <span class="text-[10px] uppercase font-bold text-amber-400 tracking-wider">Admin Panel</span>
          </div>
        </a>
        <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white p-1">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>

      <!-- Navigation Links -->
      <nav class="p-3 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-900/80 text-amber-300 border border-blue-500/30 shadow' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
          <i class="fa-solid fa-chart-pie w-5 text-center text-sm {{ request()->routeIs('admin.dashboard') ? 'text-amber-400' : 'text-blue-400' }}"></i>
          <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.orders*') ? 'bg-blue-900/80 text-amber-300 border border-blue-500/30 shadow' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
          <i class="fa-solid fa-box-open w-5 text-center text-sm {{ request()->routeIs('admin.orders*') ? 'text-amber-400' : 'text-amber-400' }}"></i>
          <span>Manajemen Pesanan</span>
        </a>

        <a href="{{ route('admin.couriers') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.couriers*') ? 'bg-blue-900/80 text-amber-300 border border-blue-500/30 shadow' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
          <i class="fa-solid fa-motorcycle w-5 text-center text-sm {{ request()->routeIs('admin.couriers*') ? 'text-amber-400' : 'text-emerald-400' }}"></i>
          <span>Manajemen Kurir</span>
        </a>

        <a href="{{ route('admin.members') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.members*') ? 'bg-blue-900/80 text-amber-300 border border-blue-500/30 shadow' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
          <i class="fa-solid fa-users w-5 text-center text-sm {{ request()->routeIs('admin.members*') ? 'text-amber-400' : 'text-cyan-400' }}"></i>
          <span>Data Member</span>
        </a>

        <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.reports*') ? 'bg-blue-900/80 text-amber-300 border border-blue-500/30 shadow' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
          <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm {{ request()->routeIs('admin.reports*') ? 'text-amber-400' : 'text-purple-400' }}"></i>
          <span>Laporan Keuangan</span>
        </a>

        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('admin.settings*') ? 'bg-blue-900/80 text-amber-300 border border-blue-500/30 shadow' : 'text-slate-300 hover:bg-navy-900 hover:text-white' }}">
          <i class="fa-solid fa-gear w-5 text-center text-sm {{ request()->routeIs('admin.settings*') ? 'text-amber-400' : 'text-slate-400' }}"></i>
          <span>Pengaturan & Tarif</span>
        </a>
      </nav>
    </div>

    <!-- Sidebar Bottom: Toko link, Admin Profile & Logout -->
    <div class="p-3 border-t border-navy-800/80 space-y-3">
      <!-- Quick link to Storefront -->
      <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-3 py-2 rounded-xl bg-navy-900 hover:bg-navy-800 text-[11px] font-bold text-amber-300 border border-navy-800 transition">
        <span class="flex items-center gap-2"><i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Website Toko</span>
        <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
      </a>

      <!-- Admin Profile Card & Logout -->
      <div class="flex items-center justify-between bg-navy-900/80 p-2.5 rounded-2xl border border-navy-800">
        <div class="flex items-center gap-2.5 min-w-0">
          <div class="w-8 h-8 rounded-xl bg-blue-700 text-white flex items-center justify-center text-xs font-extrabold shadow">
            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
          </div>
          <div class="min-w-0">
            <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</div>
            <div class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@pandawa.com' }}</div>
          </div>
        </div>

        <form action="{{ route('admin.logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
          @csrf
          <button type="submit" title="Logout" class="w-8 h-8 rounded-xl bg-rose-500/20 hover:bg-rose-500 text-rose-400 hover:text-white flex items-center justify-center transition">
            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
          </button>
        </form>
      </div>
    </div>
  </aside>

  <!-- Sidebar Overlay for Mobile -->
  <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden md:hidden"></div>

  <!-- ================= MAIN CONTENT WRAPPER ================= -->
  <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
    
    <!-- Topbar -->
    <header class="bg-white border-b border-slate-200 px-4 sm:px-6 py-3 flex items-center justify-between shrink-0 shadow-sm">
      <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="md:hidden text-slate-600 hover:text-navy-950 p-2 rounded-xl bg-slate-100">
          <i class="fa-solid fa-bars text-base"></i>
        </button>
        <div>
          <h1 class="text-base sm:text-lg font-extrabold text-navy-950 leading-tight">@yield('page_title', 'Dashboard')</h1>
          <div class="text-[11px] text-slate-400 hidden sm:block">@yield('page_subtitle', 'Panel Kontrol Pandawa Delivery & Jastip')</div>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <div class="hidden sm:flex items-center gap-2 bg-slate-100 px-3 py-1.5 rounded-full text-xs font-semibold text-slate-600">
          <i class="fa-regular fa-calendar text-blue-600"></i>
          <span>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>

        <a href="{{ route('admin.settings') }}" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition">
          <i class="fa-solid fa-gear"></i>
        </a>
      </div>
    </header>

    <!-- Page Body (Scrollable) -->
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6">
      
      <!-- Flash Alert Messages -->
      @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-sm animate-in fade-in duration-200">
          <div class="flex items-center gap-2.5">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1"><i class="fa-solid fa-xmark"></i></button>
        </div>
      @endif

      @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-sm animate-in fade-in duration-200">
          <div class="flex items-center gap-2.5">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span>{{ session('error') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 p-1"><i class="fa-solid fa-xmark"></i></button>
        </div>
      @endif

      @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1 shadow-sm">
          <div class="flex items-center gap-2 font-bold text-rose-900">
            <i class="fa-solid fa-triangle-exclamation"></i> Terdapat beberapa kesalahan input:
          </div>
          <ul class="list-disc list-inside space-y-0.5 text-[11px] pl-4">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')
    </main>

  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('adminSidebar');
      const overlay = document.getElementById('sidebarOverlay');
      if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
      } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
      }
    }
  </script>
  @yield('scripts')
</body>
</html>
