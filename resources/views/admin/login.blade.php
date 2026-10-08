<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Pandawa Delivery & Jastip</title>

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
</head>
<body class="bg-gradient-to-br from-navy-950 via-navy-900 to-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased font-sans relative overflow-hidden">

  <!-- Background Ambient Glow -->
  <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
  <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

  <!-- Login Card Container -->
  <div class="w-full max-w-md bg-navy-900/90 backdrop-blur-xl border border-navy-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 relative z-10">
    
    <!-- Header & Brand Logo -->
    <div class="text-center space-y-2">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-700 via-navy-800 to-amber-500 flex items-center justify-center text-white mx-auto shadow-xl border border-white/10">
        <i class="fa-solid fa-bolt-lightning text-2xl text-amber-300"></i>
      </div>
      <div class="pt-1">
        <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">PANDAWA ADMIN</h1>
        <p class="text-xs text-slate-400">Masuk untuk mengelola pesanan, kurir, dan laporan</p>
      </div>
    </div>

    <!-- Flash Error / Info Alerts -->
    @if(session('info'))
      <div class="p-3 bg-blue-950/80 border border-blue-500/40 text-blue-200 text-xs rounded-xl flex items-center gap-2">
        <i class="fa-solid fa-circle-info text-blue-400"></i>
        <span>{{ session('info') }}</span>
      </div>
    @endif

    @if($errors->any())
      <div class="p-3 bg-rose-950/80 border border-rose-500/40 text-rose-200 text-xs rounded-xl flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
        <span>{{ $errors->first() }}</span>
      </div>
    @endif

    <!-- Login Form -->
    <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
      @csrf

      <!-- Email Field -->
      <div class="space-y-1.5">
        <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Email Admin</label>
        <div class="relative">
          <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
          <input 
            type="email" 
            name="email" 
            id="email" 
            required 
            autocomplete="email"
            value="{{ old('email', 'admin@pandawa.com') }}" 
            placeholder="admin@pandawa.com" 
            class="w-full pl-10 pr-4 py-3 bg-navy-950 border border-navy-800 rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 transition"
          >
        </div>
      </div>

      <!-- Password Field -->
      <div class="space-y-1.5">
        <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Password</label>
        <div class="relative">
          <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
          <input 
            type="password" 
            name="password" 
            id="password" 
            required 
            autocomplete="current-password"
            value="admin123"
            placeholder="••••••••" 
            class="w-full pl-10 pr-10 py-3 bg-navy-950 border border-navy-800 rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 transition"
          >
          <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200 text-xs">
            <i class="fa-regular fa-eye" id="pwdToggleIcon"></i>
          </button>
        </div>
      </div>

      <!-- Remember Me -->
      <div class="flex items-center justify-between text-xs">
        <label class="flex items-center gap-2 cursor-pointer select-none text-slate-300">
          <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-navy-950 border-navy-800 text-amber-500 focus:ring-0">
          <span>Ingat saya</span>
        </label>
        <span class="text-slate-500 text-[11px]">Sesi aman 120 menit</span>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-navy-950 font-black rounded-xl shadow-lg transition active:scale-95 flex items-center justify-center gap-2 text-sm">
        <i class="fa-solid fa-right-to-bracket"></i>
        <span>Masuk ke Dashboard</span>
      </button>
    </form>

    <!-- Quick Credentials Hint -->
    <div class="p-3 bg-navy-950/70 border border-navy-800/80 rounded-2xl space-y-1 text-center">
      <div class="text-[11px] font-bold text-amber-300">Akun Login Bawaan (Default):</div>
      <div class="text-[11px] text-slate-300 font-mono">
        Email: <span class="text-white font-bold">admin@pandawa.com</span> | Password: <span class="text-white font-bold">admin123</span>
      </div>
    </div>

    <!-- Back to Storefront Link -->
    <div class="text-center pt-2">
      <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-amber-300 font-semibold inline-flex items-center gap-1.5 transition">
        <i class="fa-solid fa-arrow-left text-[10px]"></i>
        <span>Kembali ke Halaman Toko (Website)</span>
      </a>
    </div>

  </div>

  <script>
    function togglePasswordVisibility() {
      const pwd = document.getElementById('password');
      const icon = document.getElementById('pwdToggleIcon');
      if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'fa-regular fa-eye-slash';
      } else {
        pwd.type = 'password';
        icon.className = 'fa-regular fa-eye';
      }
    }
  </script>
</body>
</html>
