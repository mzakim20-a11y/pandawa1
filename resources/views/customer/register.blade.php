<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar - Pandawa Delivery & Jastip</title>

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
      <h1 class="text-xl sm:text-2xl font-extrabold text-white pt-1">Buat akun Pandawa</h1>
      <p class="text-xs text-slate-400">Daftar gratis untuk mulai pesan antar dan jastip.</p>
    </div>

    @if($errors->any())
      <div class="p-3 bg-rose-950/80 border border-rose-500/40 text-rose-200 text-xs rounded-xl" role="alert">
        <div class="flex items-center gap-2 mb-1">
          <i class="fa-solid fa-circle-exclamation text-rose-400"></i>
          <span class="font-bold">Periksa kembali isian kamu:</span>
        </div>
        <ul class="list-disc pl-6 space-y-0.5">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('customer.register.submit') }}" method="POST" class="space-y-4">
      @csrf

      <div class="space-y-1.5">
        <label for="name" class="block text-xs font-bold text-slate-300">Nama lengkap</label>
        <input type="text" name="name" id="name" required autofocus autocomplete="name"
               value="{{ old('name') }}" placeholder="Nama kamu"
               class="w-full px-4 py-3 bg-navy-950 border border-navy-800 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 transition">
      </div>

      <div class="space-y-1.5">
        <label for="phone" class="block text-xs font-bold text-slate-300">Nomor WhatsApp</label>
        <input type="tel" name="phone" id="phone" required autocomplete="tel"
               value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
               class="w-full px-4 py-3 bg-navy-950 border border-navy-800 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 transition">
      </div>

      <div class="space-y-1.5">
        <label for="email" class="block text-xs font-bold text-slate-300">Email</label>
        <input type="email" name="email" id="email" required autocomplete="email"
               value="{{ old('email') }}" placeholder="nama@email.com"
               class="w-full px-4 py-3 bg-navy-950 border border-navy-800 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 transition">
      </div>

      <div class="space-y-1.5">
        <label for="password" class="block text-xs font-bold text-slate-300">Kata sandi</label>
        <input type="password" name="password" id="password" required minlength="8" autocomplete="new-password"
               placeholder="Minimal 8 karakter"
               class="w-full px-4 py-3 bg-navy-950 border border-navy-800 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 transition">
      </div>

      <div class="space-y-1.5">
        <label for="password_confirmation" class="block text-xs font-bold text-slate-300">Ulangi kata sandi</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
               placeholder="Ketik ulang kata sandi"
               class="w-full px-4 py-3 bg-navy-950 border border-navy-800 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 transition">
      </div>

      <button type="submit"
              class="w-full py-3.5 px-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-navy-950 font-extrabold rounded-xl shadow-lg transition active:scale-95 text-sm">
        Daftar
      </button>
    </form>

    <p class="text-center text-xs text-slate-400">Sudah punya akun?
      <a href="{{ route('customer.login') }}" class="text-amber-300 font-bold hover:underline">Masuk</a>
    </p>
  </div>
</body>
</html>