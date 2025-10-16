<div id="overlay" onclick="closeSidebar()" class="fixed inset-0 bg-[rgba(0,0,0,0.75)] z-30 hidden lg:hidden"></div>



<!-- Sidebar -->
<aside id="sidebar" class="fixed z-40 top-0 left-0 w-64 min-h-screen bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto">
    <div class="p-4 flex items-center gap-2">
    <img src="/images/logo-pasteur.png" alt="Logo" class="w-8 h-8" />
    <div>
        <h1 class="font-bold text-sm">FasterRoom</h1>
        <p class="text-xs text-gray-500">Staff</p>
    </div>
    </div>
    <nav class="mt-4 space-y-2 text-sm">
    <!-- Single link -->
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fas fa-home w-5 h-5 pt-1 text-gray-600"></i>
        Dashboard
    </a>
    @hasrole('staff')
    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fa-solid fa-user w-5 h-5 pt-1 text-gray-600"></i>
        Daftar Pengguna
    </a>
    <a href="{{ route('rooms.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fa-solid fa-door-open w-5 h-5 pt-1 text-gray-600"></i>
        Daftar Ruangan
    </a>
    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fa-solid fa-clipboard-list w-5 h-5 pt-1 text-gray-600"></i>
        Daftar Peminjaman
    </a>
    <a href="{{ route('approvals.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
        <i class="fa-solid fa-thumbs-up w-5 h-5 pt-1 text-gray-600"></i>
        Approval Peminjaman
    </a>
    @endhasrole


    {{-- <!-- Transaksi Dropdown -->
    <div class="group">
        <button onclick="toggleSidebarDropdown('transaksiDropdown')" class="flex items-center justify-between w-full px-3 py-2 hover:bg-gray-100">
        <div class="flex items-center gap-4">
            <i class="fa-solid fa-handshake w-5 h-5 pt-1 text-gray-600"></i>
            Transaksi
        </div>
        <i class="fas fa-chevron-right text-gray-500 transition-transform group-hover:rotate-90"></i>
        </button>
        <div id="transaksiDropdown" class="dropdown-transition pl-12 mt-1 space-y-1 text-gray-600">
        <a href="{{ route('transactions.orders.index') }}" class="block hover:text-black">Penjualan</a>
        <a href="{{ route('transactions.purchases.index') }}" class="block hover:text-black">Pembelian</a>
        <a href="{{ route('transactions.inters.index') }}" class="block hover:text-black">Antar Divisi</a>
        <a href="{{ route('transactions.rents.index') }}" class="block hover:text-black">Sewa Alat Berat</a>
        </div>
    </div> --}}
    {{-- @hasrole('management')
    <div class="group">
        <button onclick="toggleSidebarDropdown('approvalsDropdown')" class="flex items-center justify-between w-full px-3 py-2 hover:bg-gray-100">
        <div class="flex items-center gap-4">
            <i class="fa-solid fa-thumbs-up w-5 h-5 pt-1 text-gray-600"></i>
            Approvals
        </div>
        <i class="fas fa-chevron-right text-gray-500 transition-transform group-hover:rotate-90"></i>
        </button>
        <div id="approvalsDropdown" class="dropdown-transition pl-12 mt-1 space-y-1 text-gray-600">
        <a href="{{ route('approvals.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
            <i class="fa-solid fa-store w-5 h-5 pt-1 text-gray-600"></i>
            Approval Operasional
        </a>
        <a href="{{ route('approvalFinances.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
            <i class="fa-solid fa-wallet w-5 h-5 pt-1 text-gray-600"></i>
            Approval Keuangan
        </a>
        </div>
    </div>
    @endhasrole --}}
    </nav>
</aside>
