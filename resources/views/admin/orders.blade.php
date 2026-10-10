@extends('admin.layout')

@section('title', 'Manajemen Pesanan')
@section('page_title', 'Manajemen Pesanan')
@section('page_subtitle', 'Kelola semua pesanan katalog, delivery, dan jastip')

@section('content')

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
  <form method="GET" action="{{ route('admin.orders') }}" class="flex flex-wrap items-center gap-3">
    <div class="relative flex-1 min-w-[200px]">
      <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nama, atau telepon..." class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40 focus:border-blue-400">
    </div>
    <select name="status" class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
      <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
      <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
      <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
      <option value="on_delivery" {{ request('status') == 'on_delivery' ? 'selected' : '' }}>On Delivery</option>
      <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
      <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
    <select name="type" class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
      <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>Semua Tipe</option>
      <option value="catalog" {{ request('type') == 'catalog' ? 'selected' : '' }}>Katalog</option>
      <option value="delivery" {{ request('type') == 'delivery' ? 'selected' : '' }}>Delivery</option>
      <option value="jastip" {{ request('type') == 'jastip' ? 'selected' : '' }}>Jastip</option>
    </select>
    <button type="submit" class="px-4 py-2.5 bg-navy-950 text-white rounded-xl text-xs font-bold hover:bg-navy-800 transition">
      <i class="fa-solid fa-filter mr-1"></i> Filter
    </button>
  </form>
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
  <div class="p-4 border-b border-slate-100">
    <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-box-open text-amber-500 mr-2"></i>Daftar Pesanan ({{ $orders->count() }})</h3>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-xs">
      <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider">
        <tr>
          <th class="px-4 py-2.5 text-left font-bold">Kode Pesanan</th>
          <th class="px-4 py-2.5 text-left font-bold">Pelanggan</th>
          <th class="px-4 py-2.5 text-left font-bold">Tipe</th>
          <th class="px-4 py-2.5 text-left font-bold">Detail Item</th>
          <th class="px-4 py-2.5 text-right font-bold">Total</th>
          <th class="px-4 py-2.5 text-center font-bold">Bayar</th>
          <th class="px-4 py-2.5 text-center font-bold">Status</th>
          <th class="px-4 py-2.5 text-center font-bold">Tanggal</th>
          <th class="px-4 py-2.5 text-center font-bold">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($orders as $order)
        <tr class="hover:bg-slate-50 transition">
          <td class="px-4 py-3 font-bold text-navy-950">{{ $order->order_code }}</td>
          <td class="px-4 py-3">
            <div class="font-bold text-slate-700">{{ $order->customer_name }}</div>
            <div class="text-[10px] text-slate-400">{{ $order->customer_phone }}</div>
          </td>
          <td class="px-4 py-3">
            @if($order->order_type === 'catalog')
              <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold">Katalog</span>
            @elseif($order->order_type === 'delivery')
              <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Delivery</span>
            @else
              <span class="px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-bold">Jastip</span>
            @endif
          </td>
          <td class="px-4 py-3 text-slate-600 max-w-[200px]">
            @if($order->items->count() > 0)
              {{ $order->items->take(2)->pluck('item_name')->join(', ') }}
              @if($order->items->count() > 2)
                <span class="text-slate-400">+{{ $order->items->count() - 2 }} lainnya</span>
              @endif
            @elseif($order->delivery)
              {{ $order->delivery->package_type }} ({{ $order->delivery->armada_type }})
            @elseif($order->jastip)
              Belanja di {{ $order->jastip->store_name }}
            @else
              -
            @endif
          </td>
          <td class="px-4 py-3 text-right font-bold text-navy-950">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
          <td class="px-4 py-3 text-center">
            @if($order->payment_status === 'paid')
              <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Lunas</span>
            @else
              <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 text-[10px] font-bold">Belum</span>
            @endif
          </td>
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
          <td class="px-4 py-3 text-center text-slate-400 text-[10px]">{{ $order->created_at->format('d/m/Y H:i') }}</td>
          <td class="px-4 py-3 text-center">
            <div class="flex items-center justify-center gap-1">
              <button onclick="openStatusModal({{ $order->id }}, '{{ $order->status }}', '{{ $order->courier_id }}')" class="w-7 h-7 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 flex items-center justify-center transition" title="Ubah Status">
                <i class="fa-solid fa-pen-to-square text-[10px]"></i>
              </button>
              <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition" title="Cetak Invoice">
                <i class="fa-solid fa-print text-[10px]"></i>
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="px-4 py-12 text-center text-slate-400">
            <i class="fa-solid fa-inbox text-3xl mb-3 block"></i>
            <div class="font-bold">Tidak ada pesanan ditemukan.</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Update Status Modal --}}
<div id="statusModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4" style="display:none;">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-4">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-pen-to-square text-blue-500 mr-2"></i>Ubah Status Pesanan</h3>
      <button onclick="closeStatusModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <form id="statusForm" method="POST" class="space-y-4">
      @csrf
      <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Status Pesanan</label>
        <select name="status" id="modalStatus" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
          <option value="pending">Pending</option>
          <option value="assigned">Assigned (Ditugaskan)</option>
          <option value="on_delivery">On Delivery (Sedang Dikirim)</option>
          <option value="completed">Completed (Selesai)</option>
          <option value="cancelled">Cancelled (Dibatalkan)</option>
        </select>
      </div>

      <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Assign Kurir (Opsional)</label>
        <select name="courier_id" id="modalCourier" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
          <option value="">-- Pilih Kurir --</option>
          @foreach($couriers as $courier)
            <option value="{{ $courier->id }}">{{ $courier->name }} ({{ $courier->vehicle }})</option>
          @endforeach
        </select>
      </div>

      <div class="flex gap-2 pt-2">
        <button type="button" onclick="closeStatusModal()" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition">
          <i class="fa-solid fa-check mr-1"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
  function openStatusModal(orderId, currentStatus, currentCourier) {
    const modal = document.getElementById('statusModal');
    const form = document.getElementById('statusForm');
    const statusSelect = document.getElementById('modalStatus');
    const courierSelect = document.getElementById('modalCourier');

    form.action = `/admin/orders/${orderId}/status`;
    statusSelect.value = currentStatus;
    if (currentCourier) courierSelect.value = currentCourier;

    modal.style.display = 'flex';
  }

  function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
  }

  document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatusModal();
  });
</script>
@endsection
