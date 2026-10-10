@extends('admin.layout')

@section('title', 'Laporan Keuangan')
@section('page_title', 'Laporan Keuangan')
@section('page_subtitle', 'Rekapitulasi pendapatan dan kinerja kurir')

@section('content')

{{-- Period Filter Tabs --}}
<div class="bg-white rounded-2xl border border-slate-200 p-3 shadow-sm">
  <div class="flex flex-wrap gap-2">
    @php
      $periods = ['today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini', 'all' => 'Semua Waktu'];
    @endphp
    @foreach($periods as $key => $label)
      <a href="{{ route('admin.reports', ['period' => $key]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $period === $key ? 'bg-navy-950 text-white shadow' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
        {{ $label }}
      </a>
    @endforeach
  </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-[11px] font-bold text-slate-400 uppercase">Total Pendapatan</div>
        <div class="text-xl font-extrabold text-navy-950 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
      </div>
      <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
        <i class="fa-solid fa-wallet text-lg"></i>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-[11px] font-bold text-slate-400 uppercase">Total Pesanan</div>
        <div class="text-xl font-extrabold text-navy-950 mt-1">{{ number_format($totalOrders) }}</div>
      </div>
      <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
        <i class="fa-solid fa-box-open text-lg"></i>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-[11px] font-bold text-slate-400 uppercase">Ongkir Terkumpul</div>
        <div class="text-xl font-extrabold text-navy-950 mt-1">Rp {{ number_format($totalShippingEarned, 0, ',', '.') }}</div>
      </div>
      <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
        <i class="fa-solid fa-truck text-lg"></i>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
    <div class="flex items-center justify-between">
      <div>
        <div class="text-[11px] font-bold text-slate-400 uppercase">Pesanan Selesai</div>
        <div class="text-xl font-extrabold text-navy-950 mt-1">{{ number_format($totalCompleted) }}</div>
      </div>
      <div class="w-11 h-11 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
        <i class="fa-solid fa-circle-check text-lg"></i>
      </div>
    </div>
  </div>
</div>

{{-- Revenue Breakdown --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
  @php
    $maxRevenue = max($revenueCatalog, $revenueDelivery, $revenueJastip, 1);
  @endphp
  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-store text-xs"></i></div>
      <div class="text-xs font-bold text-slate-500">Pendapatan Katalog</div>
    </div>
    <div class="text-lg font-extrabold text-navy-950">Rp {{ number_format($revenueCatalog, 0, ',', '.') }}</div>
    <div class="w-full bg-slate-100 rounded-full h-2 mt-3">
      <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($revenueCatalog / $maxRevenue) * 100 }}%"></div>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-truck-fast text-xs"></i></div>
      <div class="text-xs font-bold text-slate-500">Pendapatan Delivery</div>
    </div>
    <div class="text-lg font-extrabold text-navy-950">Rp {{ number_format($revenueDelivery, 0, ',', '.') }}</div>
    <div class="w-full bg-slate-100 rounded-full h-2 mt-3">
      <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ ($revenueDelivery / $maxRevenue) * 100 }}%"></div>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
    <div class="flex items-center gap-3 mb-3">
      <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center"><i class="fa-solid fa-bag-shopping text-xs"></i></div>
      <div class="text-xs font-bold text-slate-500">Pendapatan Jastip</div>
    </div>
    <div class="text-lg font-extrabold text-navy-950">Rp {{ number_format($revenueJastip, 0, ',', '.') }}</div>
    <div class="w-full bg-slate-100 rounded-full h-2 mt-3">
      <div class="bg-purple-500 h-2 rounded-full" style="width: {{ ($revenueJastip / $maxRevenue) * 100 }}%"></div>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

  {{-- Kinerja Kurir --}}
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="p-4 border-b border-slate-100">
      <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-trophy text-amber-500 mr-2"></i>Kinerja Kurir</h3>
    </div>
    <div class="p-3 space-y-2 max-h-80 overflow-y-auto">
      @forelse($couriersPerformance->sortByDesc('orders_count') as $courier)
      <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition">
        <div class="w-8 h-8 rounded-xl bg-navy-950 text-amber-400 flex items-center justify-center text-xs font-extrabold">
          {{ strtoupper(substr($courier->name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="text-xs font-bold text-navy-950 truncate">{{ $courier->name }}</div>
          <div class="text-[10px] text-slate-400"><i class="fa-solid fa-star text-amber-400 mr-0.5"></i>{{ $courier->rating }}</div>
        </div>
        <div class="text-right">
          <div class="text-xs font-extrabold text-navy-950">{{ $courier->orders_count }}</div>
          <div class="text-[10px] text-slate-400">selesai</div>
        </div>
      </div>
      @empty
      <div class="text-center py-6 text-slate-400 text-xs">Belum ada data kurir.</div>
      @endforelse
    </div>
  </div>

  {{-- Daftar Pesanan Periode --}}
  <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="p-4 border-b border-slate-100">
      <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-list text-blue-500 mr-2"></i>Daftar Pesanan ({{ $periods[$period] }})</h3>
    </div>
    <div class="overflow-x-auto max-h-96 overflow-y-auto">
      <table class="w-full text-xs">
        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider sticky top-0">
          <tr>
            <th class="px-4 py-2.5 text-left font-bold">Kode</th>
            <th class="px-4 py-2.5 text-left font-bold">Pelanggan</th>
            <th class="px-4 py-2.5 text-left font-bold">Tipe</th>
            <th class="px-4 py-2.5 text-right font-bold">Total</th>
            <th class="px-4 py-2.5 text-center font-bold">Status</th>
            <th class="px-4 py-2.5 text-center font-bold">Tanggal</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($orders as $order)
          <tr class="hover:bg-slate-50 transition">
            <td class="px-4 py-2.5 font-bold text-navy-950">{{ $order->order_code }}</td>
            <td class="px-4 py-2.5 text-slate-600">{{ $order->customer_name }}</td>
            <td class="px-4 py-2.5">
              @if($order->order_type === 'catalog')
                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold">Katalog</span>
              @elseif($order->order_type === 'delivery')
                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Delivery</span>
              @else
                <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-bold">Jastip</span>
              @endif
            </td>
            <td class="px-4 py-2.5 text-right font-bold text-navy-950">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            <td class="px-4 py-2.5 text-center">
              @if($order->status === 'pending')
                <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">Pending</span>
              @elseif($order->status === 'completed')
                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Selesai</span>
              @elseif($order->status === 'on_delivery')
                <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold">On Delivery</span>
              @elseif($order->status === 'assigned')
                <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold">Assigned</span>
              @else
                <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 text-[10px] font-bold">Dibatalkan</span>
              @endif
            </td>
            <td class="px-4 py-2.5 text-center text-slate-400 text-[10px]">{{ $order->created_at->format('d/m/Y') }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="px-4 py-8 text-center text-slate-400">
              <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
              Tidak ada pesanan di periode ini.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
