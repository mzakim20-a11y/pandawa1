<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - Pandawa Delivery & Jastip</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            navy: { 800: '#0F2942', 900: '#0B1D32', 950: '#06101E' },
            amber: { 400: '#FBBF24', 500: '#F59E0B', 600: '#D97706' }
          },
          fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
        }
      }
    }
  </script>
</head>
<body class="bg-gradient-to-br from-navy-950 via-navy-900 to-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4 antialiased font-sans">

  <div class="w-full max-w-md bg-navy-900/90 border border-navy-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">

    <div class="text-center space-y-2">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-700 via-navy-800 to-amber-500 flex items-center justify-center mx-auto border border-white/10">
        <i class="fa-solid fa-bolt-lightning text-2xl text-amber-300"></i>
      </div>
      <h1 class="text-xl sm:text-2xl font-extrabold text-white pt-1">Masuk ke Pandawa</h1>
      <p class="text-xs text-slate-400">Pantau pesanan, riwayat antar, dan jastip kamu.</p>
    </div>

    @if(session('success'))
      <div class="p-3 bg-emerald-950/80 border border-emerald-500/40 text-emerald-200 text-xs rounded-xl flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-emerald-400"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if($errors->any())
      <div class="p-3 bg-rose-950/80 border border-rose-500/40 text-rose-200 text-xs rounded-xl flex items-center gap-2" role="alert">
        <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
        <span>{{ $errors->first() }}</span>
      </div>
    @endif

    <form action="{{ route('customer.login.submit') }}" method="POST" class="space-y-4">
      @csrf

      <div class="space-y-1.5">
        <label for="email" class="block text-xs font-bold text-slate-300">Email</label>
        <div class="relative">
          <i class="fa-regular fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
          <input type="email" name="email" id="email" required autofocus autocomplete="email"
                 value="{{ old('email') }}" placeholder="nama@email.com"
                 class="w-full pl-10 pr-4 py-3 bg-navy-950 border border-navy-800 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 transition">
        </div>
      </div>

      <div class="space-y-1.5">
        <label for="password" class="block text-xs font-bold text-slate-300">Kata sandi</label>
        <div class="relative">
          <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
          <input type="password" name="password" id="password" required autocomplete="current-password"
                 placeholder="Kata sandi kamu"
                 class="w-full pl-10 pr-10 py-3 bg-navy-950 border border-navy-800 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 transition">
          <button type="button" onclick="togglePw()" aria-label="Lihat kata sandi"
                  class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-200 text-xs">
            <i class="fa-regular fa-eye" id="pwIcon"></i>
          </button>
        </div>
      </div>

      <label class="flex items-center gap-2 cursor-pointer select-none text-xs text-slate-300">
        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-navy-950 border-navy-800 text-amber-500 focus:ring-0">
        <span>Ingat saya</span>
      </label>

      <button type="submit"
              class="w-full py-3.5 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-navy-950 font-extrabold rounded-xl shadow-lg transition active:scale-95 text-sm">
        Masuk
      </button>
    </form>

    <div class="text-center text-xs text-slate-400 space-y-3 pt-1">
      <p>Belum punya akun?
        <a href="{{ route('customer.register') }}" class="text-amber-300 font-bold hover:underline">Daftar sekarang</a>
      </p>
      <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 hover:text-amber-300 font-semibold transition">
        <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali ke toko
      </a>
    </div>
  </div>

  <script>
    function togglePw() {
      const pw = document.getElementById('password');
      const icon = document.getElementById('pwIcon');
      const show = pw.type === 'password';
      pw.type = show ? 'text' : 'password';
      icon.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
    }
  </script>
</body>
</html>