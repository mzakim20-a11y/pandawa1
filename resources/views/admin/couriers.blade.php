@extends('admin.layout')

@section('title', 'Manajemen Kurir')
@section('page_title', 'Manajemen Kurir')
@section('page_subtitle', 'Kelola data kurir dan status ketersediaan')

@section('content')

{{-- Filter & Add Button --}}
<div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
  <div class="flex flex-wrap items-center gap-3">
    <form method="GET" action="{{ route('admin.couriers') }}" class="flex flex-wrap items-center gap-3 flex-1">
      <div class="relative flex-1 min-w-[200px]">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, telepon, atau plat..." class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
      </div>
      <select name="status" class="px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
        <option value="busy" {{ request('status') == 'busy' ? 'selected' : '' }}>Busy</option>
        <option value="offline" {{ request('status') == 'offline' ? 'selected' : '' }}>Offline</option>
      </select>
      <button type="submit" class="px-4 py-2.5 bg-navy-950 text-white rounded-xl text-xs font-bold hover:bg-navy-800 transition">
        <i class="fa-solid fa-filter mr-1"></i> Filter
      </button>
    </form>
    <button onclick="openAddModal()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">
      <i class="fa-solid fa-plus mr-1"></i> Tambah Kurir
    </button>
  </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
  <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-4">
    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-users text-sm"></i></div>
    <div>
      <div class="text-[11px] font-bold text-slate-400 uppercase">Total Kurir</div>
      <div class="text-lg font-extrabold text-navy-950">{{ $couriers->count() }}</div>
    </div>
  </div>
  <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-4">
    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-circle-check text-sm"></i></div>
    <div>
      <div class="text-[11px] font-bold text-slate-400 uppercase">Available</div>
      <div class="text-lg font-extrabold text-navy-950">{{ $couriers->where('status', 'available')->count() }}</div>
    </div>
  </div>
  <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-4">
    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center"><i class="fa-solid fa-circle-xmark text-sm"></i></div>
    <div>
      <div class="text-[11px] font-bold text-slate-400 uppercase">Offline</div>
      <div class="text-lg font-extrabold text-navy-950">{{ $couriers->where('status', 'offline')->count() }}</div>
    </div>
  </div>
</div>

{{-- Courier Cards Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
  @forelse($couriers as $courier)
  <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
    <div class="flex items-start gap-4">
      <img src="{{ $courier->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($courier->name) . '&background=0F2942&color=FBBF24&size=80' }}" alt="{{ $courier->name }}" class="w-14 h-14 rounded-2xl object-cover border-2 border-slate-100">
      <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between">
          <h4 class="text-sm font-extrabold text-navy-950 truncate">{{ $courier->name }}</h4>
          @if($courier->status === 'available')
            <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold shrink-0">Online</span>
          @elseif($courier->status === 'busy')
            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold shrink-0">Sibuk</span>
          @else
            <span class="px-2 py-0.5 rounded-full bg-slate-200 text-slate-500 text-[10px] font-bold shrink-0">Offline</span>
          @endif
        </div>
        <div class="text-[11px] text-slate-400 mt-0.5"><i class="fa-solid fa-phone mr-1"></i>{{ $courier->phone }}</div>
        <div class="text-[11px] text-slate-400"><i class="fa-solid fa-motorcycle mr-1"></i>{{ $courier->vehicle }} • {{ $courier->plate_number }}</div>
        <div class="flex items-center gap-3 mt-2 text-[11px]">
          <span class="text-amber-500 font-bold"><i class="fa-solid fa-star mr-0.5"></i>{{ $courier->rating }}</span>
          <span class="text-slate-400">{{ $courier->total_trips }}</span>
          @if($courier->badge)
            <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold">{{ $courier->badge }}</span>
          @endif
        </div>
      </div>
    </div>
    <div class="flex gap-2 mt-4 pt-3 border-t border-slate-100">
      <button onclick="openEditModal({{ json_encode($courier) }})" class="flex-1 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-600 text-[11px] font-bold transition">
        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
      </button>
      <form action="{{ route('admin.couriers.delete', $courier->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus kurir {{ $courier->name }}?');">
        @csrf
        <button type="submit" class="w-full py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-[11px] font-bold transition">
          <i class="fa-solid fa-trash-can mr-1"></i> Hapus
        </button>
      </form>
    </div>
  </div>
  @empty
  <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 shadow-sm text-center text-slate-400">
    <i class="fa-solid fa-motorcycle text-3xl mb-3 block"></i>
    <div class="font-bold text-sm">Belum ada data kurir.</div>
    <p class="text-[11px] mt-1">Klik tombol "Tambah Kurir" untuk menambahkan kurir baru.</p>
  </div>
  @endforelse
</div>

{{-- Add Courier Modal --}}
<div id="addModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 items-center justify-center p-4" style="display:none;">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 space-y-4">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-plus text-emerald-500 mr-2"></i>Tambah Kurir Baru</h3>
      <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="{{ route('admin.couriers.store') }}" method="POST" class="space-y-3">
      @csrf
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Nama Lengkap *</label>
          <input type="text" name="name" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">No. Telepon *</label>
          <input type="text" name="phone" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Kendaraan *</label>
          <input type="text" name="vehicle" required placeholder="cth: Honda Vario 160" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Plat Nomor *</label>
          <input type="text" name="plate_number" required placeholder="cth: B 1234 XYZ" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Badge (opsional)</label>
          <input type="text" name="badge" placeholder="cth: Top Rider" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">URL Avatar (opsional)</label>
          <input type="url" name="avatar" placeholder="https://..." class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
      </div>
      <div class="flex gap-2 pt-2">
        <button type="button" onclick="closeAddModal()" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition">
          <i class="fa-solid fa-check mr-1"></i> Simpan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Edit Courier Modal --}}
<div id="editModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 items-center justify-center p-4" style="display:none;">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 space-y-4">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-pen-to-square text-blue-500 mr-2"></i>Edit Data Kurir</h3>
      <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form id="editForm" method="POST" class="space-y-3">
      @csrf
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Nama Lengkap *</label>
          <input type="text" name="name" id="editName" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">No. Telepon *</label>
          <input type="text" name="phone" id="editPhone" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Kendaraan *</label>
          <input type="text" name="vehicle" id="editVehicle" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Plat Nomor *</label>
          <input type="text" name="plate_number" id="editPlate" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
      </div>
      <div class="grid grid-cols-3 gap-3">
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Status *</label>
          <select name="status" id="editStatus" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
            <option value="available">Available</option>
            <option value="busy">Busy</option>
            <option value="offline">Offline</option>
          </select>
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Badge</label>
          <input type="text" name="badge" id="editBadge" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Avatar URL</label>
          <input type="url" name="avatar" id="editAvatar" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
        </div>
      </div>
      <div class="flex gap-2 pt-2">
        <button type="button" onclick="closeEditModal()" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">Batal</button>
        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition">
          <i class="fa-solid fa-check mr-1"></i> Update
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
  function openAddModal() { document.getElementById('addModal').style.display = 'flex'; }
  function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }

  function openEditModal(courier) {
    document.getElementById('editForm').action = `/admin/couriers/${courier.id}`;
    document.getElementById('editName').value = courier.name;
    document.getElementById('editPhone').value = courier.phone;
    document.getElementById('editVehicle').value = courier.vehicle;
    document.getElementById('editPlate').value = courier.plate_number;
    document.getElementById('editStatus').value = courier.status;
    document.getElementById('editBadge').value = courier.badge || '';
    document.getElementById('editAvatar').value = courier.avatar || '';
    document.getElementById('editModal').style.display = 'flex';
  }
  function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }

  // Close modals on backdrop click
  ['addModal', 'editModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) { if (e.target === this) this.style.display = 'none'; });
  });
</script>
@endsection
