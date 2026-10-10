@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Utama')
@section('page_subtitle', 'Ringkasan data pesanan, kurir, dan pendapatan')

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  {{-- Total Pendapatan --}}
  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</div>
        <div class="text-xl font-extrabold text-navy-950 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
      </div>
      <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
        <i class="fa-solid fa-wallet text-lg"></i>
      </div>
    </div>
  </div>

  {{-- Total Pesanan --}}
  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pesanan</div>
        <div class="text-xl font-extrabold text-navy-950 mt-1">{{ number_format($totalOrders) }}</div>
      </div>
      <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
        <i class="fa-solid fa-box-open text-lg"></i>
      </div>
    </div>
  </div>

  {{-- Kurir Aktif --}}
  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kurir Aktif</div>
        <div class="text-xl font-extrabold text-navy-950 mt-1">{{ $activeCouriers }} <span class="text-sm font-semibold text-slate-400">dari {{ $totalCouriers }} kurir</span></div>
      </div>
      <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
        <i class="fa-solid fa-motorcycle text-lg"></i>
      </div>
    </div>
  </div>

  {{-- Total Member --}}
  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Member</div>
        <div class="text-xl font-extrabold text-navy-950 mt-1">{{ number_format($totalMembers) }}</div>
      </div>
      <div class="w-11 h-11 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
        <i class="fa-solid fa-users text-lg"></i>
      </div>
    </div>
  </div>
</div>

{{-- Breakdown per Layanan --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
  <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-4">
    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
      <i class="fa-solid fa-store text-sm"></i>
    </div>
    <div>
      <div class="text-[11px] font-bold text-slate-400 uppercase">Pesanan Katalog</div>
      <div class="text-lg font-extrabold text-navy-950">{{ number_format($catalogOrdersCount) }}</div>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-4">
    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
      <i class="fa-solid fa-truck-fast text-sm"></i>
    </div>
    <div>
      <div class="text-[11px] font-bold text-slate-400 uppercase">Pesanan Delivery</div>
      <div class="text-lg font-extrabold text-navy-950">{{ number_format($deliveryOrdersCount) }}</div>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-4">
    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
      <i class="fa-solid fa-bag-shopping text-sm"></i>
    </div>
    <div>
      <div class="text-[11px] font-bold text-slate-400 uppercase">Pesanan Jastip</div>
      <div class="text-lg font-extrabold text-navy-950">{{ number_format($jastipOrdersCount) }}</div>
    </div>
  </div>
</div>

{{-- Pesanan Terbaru & Status Kurir --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

  {{-- Pesanan Terbaru --}}
  <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
      <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-clock-rotate-left text-blue-500 mr-2"></i>Pesanan Terbaru</h3>
      <a href="{{ route('admin.orders') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-800">Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i></a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-xs">
        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider">
          <tr>
            <th class="px-4 py-2.5 text-left font-bold">Kode</th>
            <th class="px-4 py-2.5 text-left font-bold">Pelanggan</th>
            <th class="px-4 py-2.5 text-left font-bold">Tipe</th>
            <th class="px-4 py-2.5 text-right font-bold">Total</th>
            <th class="px-4 py-2.5 text-center font-bold">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($recentOrders as $order)
          <tr class="hover:bg-slate-50 transition">
            <td class="px-4 py-3 font-bold text-navy-950">{{ $order->order_code }}</td>
            <td class="px-4 py-3 text-slate-600">{{ $order->customer_name }}</td>
            <td class="px-4 py-3">
              @if($order->order_type === 'catalog')
                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold">Katalog</span>
              @elseif($order->order_type === 'delivery')
                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Delivery</span>
              @else
                <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-bold">Jastip</span>
              @endif
            </td>
            <td class="px-4 py-3 text-right font-bold text-navy-950">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            <td class="px-4 py-3 text-center">
              @if($order->status === 'pending')
                <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">Pending</span>
              @elseif($order->status === 'assigned')
                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold">Assigned</span>
              @elseif($order->status === 'on_delivery')
                <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold">On Delivery</span>
              @elseif($order->status === 'completed')
                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Selesai</span>
              @else
                <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 text-[10px] font-bold">Dibatalkan</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-4 py-8 text-center text-slate-400">
              <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
              Belum ada pesanan.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Status Kurir --}}
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
      <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-motorcycle text-emerald-500 mr-2"></i>Status Kurir</h3>
      <a href="{{ route('admin.couriers') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-800">Kelola <i class="fa-solid fa-arrow-right ml-1"></i></a>
    </div>
    <div class="p-3 space-y-2 max-h-96 overflow-y-auto">
      @forelse($couriers as $courier)
      <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition">
        <img src="{{ $courier->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($courier->name) . '&background=0F2942&color=FBBF24&size=80' }}" alt="{{ $courier->name }}" class="w-9 h-9 rounded-xl object-cover">
        <div class="flex-1 min-w-0">
          <div class="text-xs font-bold text-navy-950 truncate">{{ $courier->name }}</div>
          <div class="text-[10px] text-slate-400">{{ $courier->vehicle }} • {{ $courier->plate_number }}</div>
        </div>
        @if($courier->status === 'available')
          <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Online</span>
        @elseif($courier->status === 'busy')
          <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">Sibuk</span>
        @else
          <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-500 text-[10px] font-bold">Offline</span>
        @endif
      </div>
      @empty
      <div class="text-center py-6 text-slate-400 text-xs">
        <i class="fa-solid fa-user-slash text-xl mb-2 block"></i>
        Belum ada data kurir.
      </div>
      @endforelse
    </div>
  </div>
</div>

@endsection
