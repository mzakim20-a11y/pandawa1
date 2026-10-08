// ========================================================
// PANDAWA DELIVERY & JASTIP - Frontend Controller & API Client
// Connects with Laravel 13 & MySQL Backend
// ========================================================

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

const STATE = {
  activeTab: 'home',
  selectedCategory: 'all',
  searchQuery: '',
  sortBy: 'popular',
  cart: [],
  appliedVoucher: null,
  selectedPayment: 'COD',
  currentAddress: 'Jl. Jend. Sudirman No. 45, Jakarta',
  delivery: {
    armada: 'motor_instant',
    km: 5,
    baseRate: 10000,
    perKmRate: 2500,
    serviceFee: 1000
  },
  activeTrackingOrder: {
    order_code: 'PDW-849201',
    current_step: 3,
    remaining_km: 1.8,
    courier: {
      name: 'Budi Santoso',
      phone: '081234567890',
      vehicle: 'Honda Vario 160 (Navy Edition)',
      plate_number: 'B 4521 PDW',
      rating: 4.96,
      avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=80'
    },
    store_name: 'Merchant Pandawa',
    dest_name: 'Jl. Jend. Sudirman No. 45'
  }
};

// ================= INITIALIZATION =================
document.addEventListener('DOMContentLoaded', () => {
  loadLocalCart();
  fetchProductsFromDB();
  updateCartBadge();
  updateDeliveryCalculation();
  calculateJastipTotal();
  refreshHistoryFromDB();
  updateTrackingUI();
});

function loadLocalCart() {
  try {
    const saved = localStorage.getItem('pandawa_cart');
    if (saved) STATE.cart = JSON.parse(saved);
  } catch (e) {
    console.error(e);
  }
}

function saveLocalCart() {
  localStorage.setItem('pandawa_cart', JSON.stringify(STATE.cart));
}

// ================= CURRENCY FORMAT =================
function formatRupiah(amount) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(amount);
}

// ================= TOAST NOTIFICATION =================
function showToast(message, type = 'success') {
  const container = document.getElementById('toastContainer');
  if (!container) return;

  const toast = document.createElement('div');
  const bgClass = type === 'success' 
    ? 'bg-navy-950 text-white border-l-4 border-amber-400' 
    : 'bg-rose-700 text-white border-l-4 border-white';

  toast.className = `${bgClass} shadow-2xl rounded-2xl px-4 py-3 text-xs sm:text-sm font-semibold flex items-center gap-2.5 transform transition-all duration-300 pointer-events-auto border border-navy-800`;
  toast.innerHTML = `
    <i class="fa-solid ${type === 'success' ? 'fa-circle-check text-amber-400' : 'fa-circle-exclamation text-white'} text-base"></i>
    <span>${message}</span>
  `;

  container.appendChild(toast);

  setTimeout(() => {
    toast.classList.add('opacity-0', 'translate-y-[-10px]');
    setTimeout(() => toast.remove(), 300);
  }, 3200);
}

// ================= TAB SWITCHING =================
function switchTab(tabId) {
  STATE.activeTab = tabId;

  document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

  const target = document.getElementById(`tab-${tabId}`);
  if (target) {
    target.classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // Update Desktop Nav
  document.querySelectorAll('.nav-tab').forEach(btn => {
    if (btn.dataset.tab === tabId) {
      btn.className = 'nav-tab active-tab px-4 py-2.5 text-xs sm:text-sm font-bold flex items-center gap-2 border-b-2 border-amber-400 text-amber-400 transition';
    } else {
      btn.className = 'nav-tab px-4 py-2.5 text-xs sm:text-sm font-semibold flex items-center gap-2 border-b-2 border-transparent text-slate-300 hover:text-white transition';
    }
  });

  // Update Mobile Nav
  document.querySelectorAll('.mobile-nav-btn').forEach(btn => {
    if (btn.dataset.tab === tabId) {
      btn.classList.add('text-amber-400', 'font-bold');
      btn.classList.remove('text-slate-400', 'font-medium');
    } else {
      btn.classList.remove('text-amber-400', 'font-bold');
      btn.classList.add('text-slate-400', 'font-medium');
    }
  });
}

// ================= FETCH PRODUCTS FROM LARAVEL DB =================
async function fetchProductsFromDB() {
  const searchInput = document.getElementById('catalogSearchInput');
  const sortSelect = document.getElementById('catalogSortSelect');
  const grid = document.getElementById('catalogGrid');
  const emptyState = document.getElementById('catalogEmptyState');

  STATE.searchQuery = searchInput ? searchInput.value.trim() : '';
  STATE.sortBy = sortSelect ? sortSelect.value : 'popular';

  try {
    const url = new URL('/api/products', window.location.origin);
    if (STATE.selectedCategory !== 'all') url.searchParams.append('category', STATE.selectedCategory);
    if (STATE.searchQuery) url.searchParams.append('search', STATE.searchQuery);
    if (STATE.sortBy) url.searchParams.append('sortBy', STATE.sortBy);

    const res = await fetch(url);
    const products = await res.json();

    if (!grid) return;

    if (products.length === 0) {
      grid.innerHTML = '';
      if (emptyState) emptyState.classList.remove('hidden');
    } else {
      if (emptyState) emptyState.classList.add('hidden');
      grid.innerHTML = products.map(p => `
        <div class="card-hover bg-white rounded-3xl border border-slate-200 overflow-hidden flex flex-col justify-between shadow-sm group">
          <div class="relative w-full h-36 sm:h-44 bg-slate-100 overflow-hidden">
            <img src="${p.image}" alt="${p.name}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            ${p.badge ? `
              <span class="absolute top-2.5 left-2.5 bg-navy-950/90 text-amber-300 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full shadow border border-amber-400/30">
                ${p.badge}
              </span>
            ` : ''}
            <div class="absolute bottom-2 left-2 bg-navy-950/80 text-white text-[10px] font-semibold px-2 py-0.5 rounded-lg flex items-center gap-1">
              <i class="fa-solid fa-clock text-[9px] text-amber-400"></i> ${p.prep_time}
            </div>
          </div>
          <div class="p-3.5 sm:p-4 flex-1 flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-1">
                <span class="text-amber-500 font-bold text-[11px]"><i class="fa-solid fa-star text-[10px] mr-1"></i> ${p.rating}</span>
                <span>•</span>
                <span class="text-[11px]">${p.sold_count} terjual</span>
              </div>
              <h3 class="font-bold text-navy-950 text-xs sm:text-sm line-clamp-2">${p.name}</h3>
              <p class="text-[11px] text-slate-500 line-clamp-2 mt-1 hidden sm:block">${p.description || ''}</p>
            </div>
            <div class="pt-3 mt-2 border-t border-slate-100 flex items-center justify-between gap-2">
              <div>
                ${p.original_price ? `
                  <span class="text-[10px] text-slate-400 line-through block leading-none">
                    ${formatRupiah(p.original_price)}
                  </span>
                ` : ''}
                <span class="text-xs sm:text-sm font-extrabold text-navy-900">
                  ${formatRupiah(p.price)}
                </span>
              </div>
              <button onclick="addToCart(${p.id}, '${escapeHtml(p.name)}', ${p.price}, '${p.image}')" class="bg-navy-950 hover:bg-blue-900 text-white w-8 h-8 rounded-xl flex items-center justify-center transition active:scale-90 shadow">
                <i class="fa-solid fa-plus text-xs"></i>
              </button>
            </div>
          </div>
        </div>
      `).join('');
    }
  } catch (err) {
    console.error('Error fetching products from DB:', err);
  }
}

function escapeHtml(text) {
  return text.replace(/'/g, "\\'");
}

function filterCategoryBySlug(slug) {
  STATE.selectedCategory = slug;

  document.querySelectorAll('.cat-pill').forEach(btn => {
    if (btn.dataset.category === slug) {
      btn.className = 'cat-pill active-pill px-4 py-2 rounded-xl whitespace-nowrap bg-navy-950 text-amber-300 transition shadow-sm border border-amber-400/30';
    } else {
      btn.className = 'cat-pill px-4 py-2 rounded-xl whitespace-nowrap bg-slate-100 hover:bg-slate-200 text-slate-700 transition';
    }
  });

  fetchProductsFromDB();
}

// ================= CART OPERATIONS =================
function addToCart(id, name, price, image) {
  const existing = STATE.cart.find(i => i.id === id);
  if (existing) {
    existing.qty += 1;
  } else {
    STATE.cart.push({ id, name, price, image, qty: 1 });
  }

  saveLocalCart();
  updateCartBadge();
  showToast(`"${name}" ditambahkan ke keranjang!`);
}

function updateCartQty(id, delta) {
  const index = STATE.cart.findIndex(i => i.id === id);
  if (index === -1) return;

  STATE.cart[index].qty += delta;
  if (STATE.cart[index].qty <= 0) {
    STATE.cart.splice(index, 1);
  }

  saveLocalCart();
  renderCartDrawer();
  updateCartBadge();
}

function removeCartItem(id) {
  STATE.cart = STATE.cart.filter(i => i.id !== id);
  saveLocalCart();
  renderCartDrawer();
  updateCartBadge();
  showToast('Item dihapus dari keranjang.');
}

function updateCartBadge() {
  const count = STATE.cart.reduce((sum, i) => sum + i.qty, 0);
  const badge = document.getElementById('cartCountBadge');
  if (badge) badge.textContent = count;
}

function toggleCartModal(show) {
  const modal = document.getElementById('cartModal');
  if (!modal) return;
  if (show) {
    renderCartDrawer();
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  } else {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  }
}

function renderCartDrawer() {
  const listContainer = document.getElementById('cartItemsList');
  const emptyView = document.getElementById('emptyCartView');
  const cartFooter = document.getElementById('cartFooter');
  const totalItemsCount = document.getElementById('cartTotalItemsCount');

  if (!listContainer) return;

  const totalQty = STATE.cart.reduce((sum, i) => sum + i.qty, 0);
  if (totalItemsCount) totalItemsCount.textContent = totalQty;

  if (STATE.cart.length === 0) {
    listContainer.classList.add('hidden');
    cartFooter.classList.add('hidden');
    emptyView.classList.remove('hidden');
    return;
  }

  listContainer.classList.remove('hidden');
  cartFooter.classList.remove('hidden');
  emptyView.classList.add('hidden');

  listContainer.innerHTML = STATE.cart.map(item => `
    <div class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200/80 rounded-2xl">
      <img src="${item.image}" alt="${item.name}" class="w-14 h-14 object-cover rounded-xl border border-slate-200">
      <div class="flex-1 min-w-0">
        <h4 class="font-bold text-navy-950 text-xs truncate">${item.name}</h4>
        <div class="text-blue-700 font-extrabold text-xs mt-0.5">${formatRupiah(item.price)}</div>
      </div>
      <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-2 py-1 shadow-sm">
        <button onclick="updateCartQty(${item.id}, -1)" class="w-5 h-5 rounded flex items-center justify-center text-slate-500 hover:text-rose-600">
          <i class="fa-solid fa-minus text-[10px]"></i>
        </button>
        <span class="text-xs font-bold text-navy-950 w-4 text-center">${item.qty}</span>
        <button onclick="updateCartQty(${item.id}, 1)" class="w-5 h-5 rounded flex items-center justify-center text-slate-500 hover:text-blue-700">
          <i class="fa-solid fa-plus text-[10px]"></i>
        </button>
      </div>
      <button onclick="removeCartItem(${item.id})" class="text-slate-400 hover:text-rose-500 p-1">
        <i class="fa-solid fa-trash-can text-xs"></i>
      </button>
    </div>
  `).join('');

  calculateCartTotals();
}

function calculateCartTotals() {
  const subtotal = STATE.cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
  const ongkir = 8000;
  const layanan = 1000;
  let discount = 0;

  if (STATE.appliedVoucher) {
    if (STATE.appliedVoucher.discount_type === 'fixed') {
      discount = STATE.appliedVoucher.discount_amount;
    } else if (STATE.appliedVoucher.discount_type === 'percent') {
      discount = Math.round((subtotal * STATE.appliedVoucher.discount_amount) / 100);
    }
  }

  const grandTotal = Math.max(0, subtotal + ongkir + layanan - discount);

  document.getElementById('cartSubtotalDisplay').textContent = formatRupiah(subtotal);
  document.getElementById('cartOngkirDisplay').textContent = formatRupiah(ongkir);
  document.getElementById('cartGrandTotalDisplay').textContent = formatRupiah(grandTotal);

  const discountRow = document.getElementById('discountRow');
  const discountDisplay = document.getElementById('cartDiscountDisplay');
  if (discount > 0) {
    discountRow.classList.remove('hidden');
    discountDisplay.textContent = `- ${formatRupiah(discount)}`;
  } else {
    discountRow.classList.add('hidden');
  }

  return { subtotal, ongkir, layanan, discount, grandTotal };
}

// Check voucher with Laravel database
async function applyVoucherFromDB() {
  const input = document.getElementById('voucherCodeInput');
  const msg = document.getElementById('voucherMessage');
  if (!input || !msg) return;

  const code = input.value.trim().toUpperCase();
  if (!code) return;

  try {
    const res = await fetch('/api/voucher/check', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
      },
      body: JSON.stringify({ code })
    });

    const data = await res.json();

    if (res.ok && data.valid) {
      STATE.appliedVoucher = data;
      msg.className = 'text-[11px] font-semibold text-blue-700';
      msg.textContent = `${data.message} (Diskon: ${data.discount_type === 'percent' ? data.discount_amount + '%' : formatRupiah(data.discount_amount)})`;
      msg.classList.remove('hidden');
      calculateCartTotals();
      showToast('Voucher database berhasil diterapkan!');
    } else {
      STATE.appliedVoucher = null;
      msg.className = 'text-[11px] font-semibold text-rose-500';
      msg.textContent = data.message || 'Voucher tidak valid.';
      msg.classList.remove('hidden');
      calculateCartTotals();
    }
  } catch (err) {
    console.error('Voucher check error:', err);
  }
}

// ================= CHECKOUT & SAVE CATALOG ORDER TO DATABASE =================
function openCheckoutModal() {
  toggleCartModal(false);
  const modal = document.getElementById('checkoutModal');
  if (modal) modal.classList.remove('hidden');
}

function toggleCheckoutModal(show) {
  const modal = document.getElementById('checkoutModal');
  if (!modal) return;
  if (show) modal.classList.remove('hidden');
  else modal.classList.add('hidden');
}

function selectPaymentMethod(method, el) {
  STATE.selectedPayment = method;
  document.querySelectorAll('.payment-option').forEach(item => {
    item.classList.remove('border-navy-700', 'bg-blue-50/70');
    item.classList.add('border-slate-200', 'bg-white');
  });
  el.classList.remove('border-slate-200', 'bg-white');
  el.classList.add('border-navy-700', 'bg-blue-50/70');
}

async function submitCatalogOrderToDB(openWhatsApp = true) {
  if (STATE.cart.length === 0) {
    showToast('Keranjang masih kosong!', 'error');
    return;
  }

  const name = document.getElementById('checkoutCustName').value.trim() || 'Pelanggan';
  const phone = document.getElementById('checkoutCustPhone').value.trim() || '-';
  const address = document.getElementById('checkoutCustAddress').value.trim() || 'Alamat Belum Diisi';
  const notes = document.getElementById('checkoutCustNotes').value.trim();

  const payload = {
    customer_name: name,
    customer_phone: phone,
    customer_address: address,
    notes: notes,
    payment_method: STATE.selectedPayment,
    voucher_code: STATE.appliedVoucher ? STATE.appliedVoucher.code : null,
    items: STATE.cart.map(i => ({ id: i.id, qty: i.qty }))
  };

  try {
    const res = await fetch('/api/order/catalog', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
      },
      body: JSON.stringify(payload)
    });

    const data = await res.json();

    if (data.success) {
      showToast(`Pesanan ${data.order.order_code} tersimpan di Database!`);

      // Set active tracking from DB response
      STATE.activeTrackingOrder = {
        order_code: data.order.order_code,
        current_step: data.order.current_step,
        remaining_km: 2.4,
        courier: data.order.courier || {
          name: 'Budi Santoso',
          phone: '081234567890',
          vehicle: 'Honda Vario 160',
          plate_number: 'B 4521 PDW',
          rating: 4.96
        },
        store_name: 'Merchant Pandawa',
        dest_name: address
      };

      if (openWhatsApp && data.whatsapp_url) {
        window.open(data.whatsapp_url, '_blank');
      }

      // Reset cart
      STATE.cart = [];
      saveLocalCart();
      updateCartBadge();
      toggleCheckoutModal(false);

      updateTrackingUI();
      refreshHistoryFromDB();
      switchTab('tracking');
    } else {
      showToast(data.message || 'Gagal menyimpan pesanan.', 'error');
    }
  } catch (err) {
    console.error('Error submitting order to DB:', err);
    showToast('Terjadi kesalahan saat menghubungi server.', 'error');
  }
}

// ================= DELIVERY / GOSEND TO DATABASE =================
function selectArmada(armadaType, el) {
  STATE.delivery.armada = armadaType;
  if (armadaType === 'motor_instant') {
    STATE.delivery.baseRate = 10000;
    STATE.delivery.perKmRate = 2500;
  } else if (armadaType === 'motor_cargo') {
    STATE.delivery.baseRate = 15000;
    STATE.delivery.perKmRate = 3500;
  } else if (armadaType === 'mobil') {
    STATE.delivery.baseRate = 35000;
    STATE.delivery.perKmRate = 5000;
  }

  document.querySelectorAll('.armada-option').forEach(item => {
    item.classList.remove('border-navy-700', 'bg-blue-50/60');
    item.classList.add('border-slate-200', 'bg-white');
  });
  el.classList.remove('border-slate-200', 'bg-white');
  el.classList.add('border-navy-700', 'bg-blue-50/60');

  updateDeliveryCalculation();
}

function updateDeliveryCalculation() {
  const slider = document.getElementById('deliveryKmSlider');
  const kmDisplay = document.getElementById('deliveryKmDisplay');
  if (!slider || !kmDisplay) return;

  const km = parseFloat(slider.value);
  STATE.delivery.km = km;
  kmDisplay.textContent = km;

  const baseKm = 3;
  const extraKm = Math.max(0, km - baseKm);
  const extraPrice = extraKm * STATE.delivery.perKmRate;
  const total = STATE.delivery.baseRate + extraPrice + STATE.delivery.serviceFee;

  document.getElementById('calcArmadaName').textContent = 
    STATE.delivery.armada === 'motor_instant' ? 'Motor Instant' :
    STATE.delivery.armada === 'motor_cargo' ? 'Motor Kargo' : 'Mobil Kurir';
  document.getElementById('calcBasePrice').textContent = formatRupiah(STATE.delivery.baseRate);
  document.getElementById('calcExtraKm').textContent = extraKm;
  document.getElementById('calcRatePerKm').textContent = formatRupiah(STATE.delivery.perKmRate);
  document.getElementById('calcExtraPrice').textContent = formatRupiah(extraPrice);
  document.getElementById('calcTotalPrice').textContent = formatRupiah(total);
  document.getElementById('btnDeliveryTotal').textContent = formatRupiah(total);
}

async function submitDeliveryToDB(event) {
  event.preventDefault();

  const payload = {
    sender_name: document.getElementById('delivSenderName').value.trim(),
    sender_phone: document.getElementById('delivSenderPhone').value.trim(),
    sender_address: document.getElementById('delivSenderAddress').value.trim(),
    receiver_name: document.getElementById('delivReceiverName').value.trim(),
    receiver_phone: document.getElementById('delivReceiverPhone').value.trim(),
    receiver_address: document.getElementById('delivReceiverAddress').value.trim(),
    package_type: document.getElementById('delivPackageType').value,
    package_note: document.getElementById('delivPackageNote').value.trim(),
    armada_type: STATE.delivery.armada,
    distance_km: STATE.delivery.km
  };

  try {
    const res = await fetch('/api/order/delivery', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
      },
      body: JSON.stringify(payload)
    });

    const data = await res.json();

    if (data.success) {
      showToast(`Order Delivery ${data.order.order_code} tersimpan di Database!`);

      STATE.activeTrackingOrder = {
        order_code: data.order.order_code,
        current_step: 2,
        remaining_km: STATE.delivery.km,
        courier: data.order.courier || {
          name: 'Budi Santoso',
          phone: '081234567890',
          vehicle: 'Honda Vario 160',
          plate_number: 'B 4521 PDW',
          rating: 4.96
        },
        store_name: payload.sender_name + ' (Jemput)',
        dest_name: payload.receiver_name + ' (Tujuan)'
      };

      if (data.whatsapp_url) {
        window.open(data.whatsapp_url, '_blank');
      }

      updateTrackingUI();
      refreshHistoryFromDB();
      switchTab('tracking');
    }
  } catch (err) {
    console.error('Error submitting delivery:', err);
    showToast('Gagal memproses delivery.', 'error');
  }
}

// ================= JASTIP OPERATIONS TO DATABASE =================
function addJastipItemRow() {
  const container = document.getElementById('jastipItemsContainer');
  if (!container) return;

  const row = document.createElement('div');
  row.className = 'jastip-item-row flex items-center gap-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200';
  row.innerHTML = `
    <input type="text" placeholder="Nama barang" class="jastip-name flex-1 text-xs bg-white p-2 border border-slate-200 rounded-lg focus:outline-none focus:border-navy-700" required>
    <input type="number" placeholder="Est. Harga" oninput="calculateJastipTotal()" class="jastip-price w-28 text-xs bg-white p-2 border border-slate-200 rounded-lg focus:outline-none focus:border-navy-700" value="25000" required>
    <button type="button" onclick="removeJastipRow(this)" class="text-slate-400 hover:text-rose-500 px-2 py-1">
      <i class="fa-solid fa-trash-can text-xs"></i>
    </button>
  `;
  container.appendChild(row);
  calculateJastipTotal();
}

function removeJastipRow(btn) {
  const container = document.getElementById('jastipItemsContainer');
  if (container.querySelectorAll('.jastip-item-row').length <= 1) {
    showToast('Minimal 1 barang jastip harus diisi!', 'error');
    return;
  }
  btn.closest('.jastip-item-row').remove();
  calculateJastipTotal();
}

function calculateJastipTotal() {
  const priceInputs = document.querySelectorAll('.jastip-price');
  let talangan = 0;
  priceInputs.forEach(input => {
    talangan += parseFloat(input.value) || 0;
  });

  const jastipFee = 10000;
  const ongkir = 12000;
  const layanan = 1000;
  const total = talangan + jastipFee + ongkir + layanan;

  document.getElementById('jastipTalanganDisplay').textContent = formatRupiah(talangan);
  document.getElementById('jastipFeeDisplay').textContent = formatRupiah(jastipFee);
  document.getElementById('jastipOngkirDisplay').textContent = formatRupiah(ongkir);
  document.getElementById('jastipGrandTotalDisplay').textContent = formatRupiah(total);

  return { talangan, jastipFee, ongkir, total };
}

async function submitJastipToDB(event) {
  event.preventDefault();

  const itemRows = document.querySelectorAll('.jastip-item-row');
  const items = [];
  itemRows.forEach(row => {
    const name = row.querySelector('.jastip-name').value.trim();
    const price = parseFloat(row.querySelector('.jastip-price').value) || 0;
    if (name) items.push({ name, price });
  });

  if (items.length === 0) {
    showToast('Silakan isi barang titipan!', 'error');
    return;
  }

  const payload = {
    customer_name: document.getElementById('jastipCustName').value.trim(),
    customer_phone: document.getElementById('jastipCustPhone').value.trim(),
    store_name: document.getElementById('jastipStoreName').value.trim(),
    store_address: document.getElementById('jastipStoreAddress').value.trim(),
    destination_address: document.getElementById('jastipDestination').value.trim(),
    shopping_notes: document.getElementById('jastipNotes').value.trim(),
    items: items
  };

  try {
    const res = await fetch('/api/order/jastip', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
      },
      body: JSON.stringify(payload)
    });

    const data = await res.json();

    if (data.success) {
      showToast(`Pesanan Jastip ${data.order.order_code} tersimpan di Database!`);

      STATE.activeTrackingOrder = {
        order_code: data.order.order_code,
        current_step: 2,
        remaining_km: 3.2,
        courier: data.order.courier || {
          name: 'Rian Hidayat',
          phone: '081298765432',
          vehicle: 'Yamaha NMAX 155 (Navy)',
          plate_number: 'B 6319 PDW',
          rating: 4.93
        },
        store_name: payload.store_name,
        dest_name: payload.destination_address
      };

      if (data.whatsapp_url) {
        window.open(data.whatsapp_url, '_blank');
      }

      updateTrackingUI();
      refreshHistoryFromDB();
      switchTab('tracking');
    }
  } catch (err) {
    console.error('Error submitting jastip:', err);
    showToast('Gagal memproses pesanan jastip.', 'error');
  }
}

// ================= LIVE TRACKING & DATABASE UPDATE =================
function updateTrackingUI() {
  const track = STATE.activeTrackingOrder;
  if (!track) return;

  const idEl = document.getElementById('trackingOrderId');
  const storeEl = document.getElementById('trackingStoreLabel');
  const destEl = document.getElementById('trackingDestLabel');
  const distEl = document.getElementById('trackingRemainingDist');
  const badgeEl = document.getElementById('trackingStatusBadge');
  const markerName = document.getElementById('courierMarkerName');

  const courierNameEl = document.getElementById('trackingCourierName');
  const courierVehicleEl = document.getElementById('trackingCourierVehicle');
  const courierRatingEl = document.getElementById('trackingCourierRating');
  const courierAvatarEl = document.getElementById('trackingCourierAvatar');
  const waBtn = document.getElementById('trackingWaBtn');
  const callBtn = document.getElementById('trackingCallBtn');

  if (idEl) idEl.textContent = track.order_code;
  if (storeEl) storeEl.textContent = track.store_name || 'Merchant Pandawa';
  if (destEl) destEl.textContent = track.dest_name || 'Alamat Anda';
  if (distEl) distEl.textContent = `${track.remaining_km || 1.8} KM`;

  if (track.courier) {
    if (markerName) markerName.textContent = track.courier.name;
    if (courierNameEl) courierNameEl.textContent = track.courier.name;
    if (courierVehicleEl) courierVehicleEl.innerHTML = `${track.courier.vehicle} • Plat: <span class="font-bold text-navy-900">${track.courier.plate_number}</span>`;
    if (courierRatingEl) courierRatingEl.textContent = track.courier.rating;
    if (courierAvatarEl && track.courier.avatar) courierAvatarEl.src = track.courier.avatar;
    if (waBtn && track.courier.phone) {
      const cleanPhone = track.courier.phone.replace(/^0/, '62');
      waBtn.href = `https://wa.me/${cleanPhone}?text=Halo%20${encodeURIComponent(track.courier.name)},%20mau%20tanya%20pesanan%20${track.order_code}`;
    }
    if (callBtn && track.courier.phone) {
      callBtn.href = `tel:${track.courier.phone}`;
    }
  }

  // Update Steps
  const step = track.current_step || 1;
  for (let i = 1; i <= 4; i++) {
    const box = document.getElementById(`trackStep${i}`);
    if (!box) continue;

    if (i < step) {
      box.className = 'timeline-box p-3 rounded-2xl bg-blue-50 border border-blue-200 transition';
      box.querySelector('i').className = 'fa-solid fa-circle-check text-sm text-blue-700';
    } else if (i === step) {
      box.className = 'timeline-box p-3 rounded-2xl bg-navy-900 text-white border-2 border-amber-400 shadow-md transition';
      box.querySelector('i').className = 'fa-solid fa-motorcycle text-sm text-amber-300';
    } else {
      box.className = 'timeline-box p-3 rounded-2xl bg-slate-50 border border-slate-200 opacity-60 transition';
      box.querySelector('i').className = 'fa-regular fa-circle text-sm text-slate-400';
    }
  }

  if (badgeEl) {
    if (step === 1) badgeEl.textContent = 'Pesanan Diterima';
    else if (step === 2) badgeEl.textContent = 'Kurir Ditugaskan';
    else if (step === 3) badgeEl.textContent = 'Sedang Mengantar';
    else if (step === 4) {
      badgeEl.textContent = 'Pesanan Selesai';
      badgeEl.className = 'bg-navy-950 text-amber-400 text-xs font-bold px-2.5 py-0.5 rounded-full border border-amber-400/40';
    }
  }
}

async function advanceTrackingStepInDB() {
  const currentStep = STATE.activeTrackingOrder.current_step;
  const nextStep = currentStep < 4 ? currentStep + 1 : 1;
  const orderCode = STATE.activeTrackingOrder.order_code;

  try {
    const res = await fetch(`/api/tracking/${orderCode}/step`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
      },
      body: JSON.stringify({ step: nextStep })
    });

    const data = await res.json();
    if (data.success) {
      STATE.activeTrackingOrder.current_step = nextStep;
      STATE.activeTrackingOrder.remaining_km = nextStep === 4 ? 0 : Math.max(0.5, (STATE.activeTrackingOrder.remaining_km - 0.8).toFixed(1));
      updateTrackingUI();

      const stepNames = [
        '',
        'Pesanan Diterima di Database',
        'Kurir Pandawa Ditugaskan & Menuju Lokasi',
        'Kurir Sedang OTW Menuju Lokasi Anda!',
        'Pesanan Selesai & Terbayar! Terima Kasih Telah Menggunakan Pandawa.'
      ];
      showToast(`Status DB: ${stepNames[nextStep]}`);
      refreshHistoryFromDB();
    }
  } catch (err) {
    console.error('Error updating tracking in DB:', err);
    // Fallback client simulation
    STATE.activeTrackingOrder.current_step = nextStep;
    updateTrackingUI();
  }
}

// ================= ORDER HISTORY FROM DATABASE =================
async function refreshHistoryFromDB() {
  const container = document.getElementById('historyListContainer');
  if (!container) return;

  try {
    const res = await fetch('/api/orders/history');
    const orders = await res.json();

    if (orders.length === 0) {
      container.innerHTML = `
        <div class="text-center py-12 bg-white rounded-3xl border border-slate-200 p-8 space-y-3">
          <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto">
            <i class="fa-solid fa-clock-rotate-left"></i>
          </div>
          <h4 class="font-bold text-slate-700">Belum Ada Riwayat Pesanan di Database</h4>
          <p class="text-xs text-slate-500">Setiap order Katalog, Delivery, dan Jastip akan otomatis tersimpan di MySQL database.</p>
        </div>
      `;
      return;
    }

    container.innerHTML = orders.map(ord => {
      let itemsSummary = '';
      if (ord.order_type === 'catalog') {
        itemsSummary = ord.items?.map(it => `${it.quantity}x ${it.item_name}`).join(', ') || 'Belanja Katalog';
      } else if (ord.order_type === 'delivery') {
        itemsSummary = `Kirim Paket ${ord.delivery?.package_type || ''} (${ord.delivery?.distance_km || 0} KM)`;
      } else if (ord.order_type === 'jastip') {
        itemsSummary = `Jastip Toko: ${ord.jastip?.store_name || ''}`;
      }

      const courierName = ord.courier ? `${ord.courier.name} (${ord.courier.plate_number})` : 'Kurir Pandawa';
      const dateFormatted = new Date(ord.created_at).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
      });

      return `
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div class="space-y-1">
            <div class="flex items-center gap-2">
              <span class="font-mono font-bold text-xs bg-navy-950 text-amber-300 px-2.5 py-0.5 rounded-lg border border-amber-400/30">${ord.order_code}</span>
              <span class="text-xs font-bold text-blue-800 bg-blue-100 px-2.5 py-0.5 rounded-full capitalize">${ord.order_type}</span>
              <span class="text-[11px] text-slate-400">• ${dateFormatted}</span>
            </div>
            <h4 class="font-bold text-navy-950 text-sm mt-1">${itemsSummary}</h4>
            <div class="text-xs text-slate-500 flex flex-wrap items-center gap-2 sm:gap-3">
              <span>Total: <strong class="text-navy-950 font-extrabold">${formatRupiah(ord.total_amount)}</strong></span>
              <span>•</span>
              <span>Metode: <strong class="text-slate-700 font-semibold">${ord.payment_method}</strong></span>
              <span>•</span>
              <span class="text-blue-700 font-medium"><i class="fa-solid fa-motorcycle mr-1"></i>${courierName}</span>
            </div>
          </div>

          <div class="flex items-center gap-2 w-full sm:w-auto">
            <button onclick="loadOrderIntoTracking('${ord.order_code}')" class="flex-1 sm:flex-initial bg-blue-50 hover:bg-blue-100 text-blue-900 font-bold text-xs px-4 py-2.5 rounded-xl transition flex items-center justify-center gap-1.5 border border-blue-200 shadow-sm">
              <i class="fa-solid fa-map-pin text-amber-500"></i> Pantau Kurir
            </button>
          </div>
        </div>
      `;
    }).join('');
  } catch (err) {
    console.error('Error fetching order history:', err);
  }
}

async function loadOrderIntoTracking(orderCode) {
  try {
    const res = await fetch(`/api/tracking/${orderCode}`);
    const ord = await res.json();

    STATE.activeTrackingOrder = {
      order_code: ord.order_code,
      current_step: ord.current_step,
      remaining_km: ord.delivery ? ord.delivery.distance_km : 1.5,
      courier: ord.courier,
      store_name: ord.jastip?.store_name || (ord.delivery ? ord.delivery.sender_name : 'Merchant Pandawa'),
      dest_name: ord.customer_address
    };

    updateTrackingUI();
    switchTab('tracking');
    showToast(`Memuat pelacakan pesanan ${orderCode} dari database.`);
  } catch (err) {
    console.error('Error loading order tracking:', err);
  }
}

function openAddressPrompt() {
  const current = STATE.currentAddress;
  const newAddr = prompt('Ubah alamat tujuan pengantaran Anda:', current);
  if (newAddr && newAddr.trim() !== '') {
    STATE.currentAddress = newAddr.trim();
    const addrHeader = document.getElementById('currentAddressHeader');
    if (addrHeader) addrHeader.textContent = STATE.currentAddress;
    showToast(`Alamat diubah: ${STATE.currentAddress}`);
  }
}
