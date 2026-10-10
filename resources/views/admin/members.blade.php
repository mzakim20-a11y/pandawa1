@extends('admin.layout')

@section('title', 'Data Member')
@section('page_title', 'Data Member')
@section('page_subtitle', 'Kelola data pelanggan dan member Pandawa')

@section('content')

{{-- Filter & Add --}}
<div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
  <div class="flex flex-wrap items-center gap-3">
    <form method="GET" action="{{ route('admin.members') }}" class="flex flex-1 min-w-[200px]">
      <div class="relative flex-1">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau telepon..." class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
      </div>
      <button type="submit" class="ml-2 px-4 py-2.5 bg-navy-950 text-white rounded-xl text-xs font-bold hover:bg-navy-800 transition">
        <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari
      </button>
    </form>
    <button onclick="openAddModal()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">
      <i class="fa-solid fa-user-plus mr-1"></i> Tambah Member
    </button>
  </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
  <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-4">
    <div class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center"><i class="fa-solid fa-users text-sm"></i></div>
    <div>
      <div class="text-[11px] font-bold text-slate-400 uppercase">Total Member</div>
      <div class="text-lg font-extrabold text-navy-950">{{ $members->count() }}</div>
    </div>
  </div>
  <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-4">
    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-user-check text-sm"></i></div>
    <div>
      <div class="text-[11px] font-bold text-slate-400 uppercase">Member Aktif</div>
      <div class="text-lg font-extrabold text-navy-950">{{ $members->where('status', 'active')->count() }}</div>
    </div>
  </div>
</div>

{{-- Members Table --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-xs">
      <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider">
        <tr>
          <th class="px-4 py-2.5 text-left font-bold">#</th>
          <th class="px-4 py-2.5 text-left font-bold">Nama</th>
          <th class="px-4 py-2.5 text-left font-bold">Email</th>
          <th class="px-4 py-2.5 text-left font-bold">No. Telepon</th>
          <th class="px-4 py-2.5 text-left font-bold">Alamat</th>
          <th class="px-4 py-2.5 text-right font-bold">Total Pesanan</th>
          <th class="px-4 py-2.5 text-right font-bold">Total Belanja</th>
          <th class="px-4 py-2.5 text-center font-bold">Status</th>
          <th class="px-4 py-2.5 text-center font-bold">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($members as $idx => $member)
        <tr class="hover:bg-slate-50 transition">
          <td class="px-4 py-3 text-slate-400">{{ $idx + 1 }}</td>
          <td class="px-4 py-3 font-bold text-navy-950">{{ $member->name }}</td>
          <td class="px-4 py-3 text-slate-500">{{ $member->email ?? '-' }}</td>
          <td class="px-4 py-3 text-slate-600">{{ $member->phone }}</td>
          <td class="px-4 py-3 text-slate-500 max-w-[180px] truncate">{{ $member->address ?? '-' }}</td>
          <td class="px-4 py-3 text-right font-bold text-navy-950">{{ $member->total_orders }}</td>
          <td class="px-4 py-3 text-right font-bold text-navy-950">Rp {{ number_format($member->total_spent, 0, ',', '.') }}</td>
          <td class="px-4 py-3 text-center">
            @if($member->status === 'active')
              <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-bold">Aktif</span>
            @else
              <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 text-[10px] font-bold">Diblokir</span>
            @endif
          </td>
          <td class="px-4 py-3 text-center">
            <div class="flex items-center justify-center gap-1">
              <button onclick="openEditModal({{ json_encode($member) }})" class="w-7 h-7 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 flex items-center justify-center transition" title="Edit">
                <i class="fa-solid fa-pen-to-square text-[10px]"></i>
              </button>
              <form action="{{ route('admin.members.delete', $member->id) }}" method="POST" onsubmit="return confirm('Hapus member {{ $member->name }}?');">
                @csrf
                <button type="submit" class="w-7 h-7 rounded-lg bg-rose-100 hover:bg-rose-200 text-rose-600 flex items-center justify-center transition" title="Hapus">
                  <i class="fa-solid fa-trash-can text-[10px]"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="px-4 py-12 text-center text-slate-400">
            <i class="fa-solid fa-users-slash text-3xl mb-3 block"></i>
            <div class="font-bold">Belum ada data member.</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Add Member Modal --}}
<div id="addModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 items-center justify-center p-4" style="display:none;">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-4">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-user-plus text-emerald-500 mr-2"></i>Tambah Member Baru</h3>
      <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form action="{{ route('admin.members.store') }}" method="POST" class="space-y-3">
      @csrf
      <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Nama Lengkap *</label>
        <input type="text" name="name" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">No. Telepon *</label>
        <input type="text" name="phone" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Email (opsional)</label>
        <input type="email" name="email" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Alamat (opsional)</label>
        <textarea name="address" rows="2" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40 resize-none"></textarea>
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

{{-- Edit Member Modal --}}
<div id="editModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 items-center justify-center p-4" style="display:none;">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-4">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-extrabold text-navy-950"><i class="fa-solid fa-pen-to-square text-blue-500 mr-2"></i>Edit Data Member</h3>
      <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <form id="editForm" method="POST" class="space-y-3">
      @csrf
      <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Nama Lengkap *</label>
        <input type="text" name="name" id="editName" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">No. Telepon *</label>
        <input type="text" name="phone" id="editPhone" required class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Email</label>
        <input type="email" name="email" id="editEmail" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Alamat</label>
        <textarea name="address" id="editAddress" rows="2" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40 resize-none"></textarea>
      </div>
      <div>
        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Status</label>
        <select name="status" id="editStatus" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-400/40">
          <option value="active">Aktif</option>
          <option value="blocked">Diblokir</option>
        </select>
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

  function openEditModal(member) {
    document.getElementById('editForm').action = `/admin/members/${member.id}`;
    document.getElementById('editName').value = member.name;
    document.getElementById('editPhone').value = member.phone;
    document.getElementById('editEmail').value = member.email || '';
    document.getElementById('editAddress').value = member.address || '';
    document.getElementById('editStatus').value = member.status;
    document.getElementById('editModal').style.display = 'flex';
  }
  function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }

  ['addModal', 'editModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) { if (e.target === this) this.style.display = 'none'; });
  });
</script>
@endsection
