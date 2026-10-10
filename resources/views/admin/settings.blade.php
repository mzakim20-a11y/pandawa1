@extends('admin.layout')

@section('title', 'Pengaturan')
@section('page_title', 'Pengaturan & Tarif')
@section('page_subtitle', 'Atur profil admin, informasi perusahaan, dan tarif layanan')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

  {{-- Company Settings & Tarif --}}
  <div class="space-y-6">
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
      @csrf

      {{-- Informasi Perusahaan --}}
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="p-4 border-b border-slate-100">
          <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-building text-blue-500 mr-2"></i>Informasi Perusahaan</h3>
        </div>
        <div class="p-5 space-y-4">
          <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Nama Perusahaan</label>
            <input type="text" name="company_name" value="{{ $settings['company_name'] }}" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">No. Telepon</label>
              <input type="text" name="company_phone" value="{{ $settings['company_phone'] }}" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
            </div>
            <div>
              <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Email</label>
              <input type="email" name="company_email" value="{{ $settings['company_email'] }}" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
            </div>
          </div>
          <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Alamat</label>
            <textarea name="company_address" rows="2" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40 resize-none">{{ $settings['company_address'] }}</textarea>
          </div>
        </div>
      </div>

      {{-- Tarif Layanan --}}
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="p-4 border-b border-slate-100">
          <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-tags text-amber-500 mr-2"></i>Tarif Layanan</h3>
        </div>
        <div class="p-5 space-y-4">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tarif Dasar Delivery (Rp)</label>
              <input type="number" name="delivery_base_rate" value="{{ $settings['delivery_base_rate'] }}" required min="0" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
            </div>
            <div>
              <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Tarif Per KM (Rp)</label>
              <input type="number" name="delivery_per_km_rate" value="{{ $settings['delivery_per_km_rate'] }}" required min="0" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Biaya Jastip (Rp)</label>
              <input type="number" name="jastip_fee" value="{{ $settings['jastip_fee'] }}" required min="0" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
            </div>
            <div>
              <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Biaya Layanan (Rp)</label>
              <input type="number" name="service_fee" value="{{ $settings['service_fee'] }}" required min="0" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
            </div>
          </div>
        </div>
      </div>

      <button type="submit" class="w-full py-3 rounded-xl bg-navy-950 hover:bg-navy-800 text-white text-xs font-bold transition">
        <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Pengaturan & Tarif
      </button>
    </form>
  </div>

  {{-- Admin Profile --}}
  <div>
    <form action="{{ route('admin.settings.profile') }}" method="POST">
      @csrf
      <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="p-4 border-b border-slate-100">
          <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-user-shield text-purple-500 mr-2"></i>Profil Admin</h3>
        </div>
        <div class="p-5 space-y-4">

          {{-- Avatar Display --}}
          <div class="flex items-center gap-4 pb-4 border-b border-slate-100">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-navy-950 to-blue-700 text-amber-300 flex items-center justify-center text-2xl font-extrabold shadow-lg">
              {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
            </div>
            <div>
              <div class="text-sm font-extrabold text-navy-950">{{ $admin->name }}</div>
              <div class="text-xs text-slate-400">{{ $admin->email }}</div>
              <div class="text-[10px] text-emerald-500 font-bold mt-1"><i class="fa-solid fa-shield-halved mr-1"></i>Administrator</div>
            </div>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ $admin->name }}" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
          </div>
          <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Email</label>
            <input type="email" name="email" value="{{ $admin->email }}" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
          </div>
          <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Password Baru</label>
            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
            <p class="text-[10px] text-slate-400 mt-1">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.</p>
          </div>
          <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
          </div>
        </div>
      </div>

      <button type="submit" class="w-full mt-4 py-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold transition">
        <i class="fa-solid fa-user-pen mr-1"></i> Update Profil Admin
      </button>
    </form>
  </div>
</div>

@endsection
