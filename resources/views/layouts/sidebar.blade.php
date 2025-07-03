@php
    use Illuminate\Support\Facades\Route;
@endphp

{{-- <div class="container d-flex flex-column justify-content-between text-center w-full py-2 px-0 vh-100 bg-[#FFE5E5]">
  <div>
    <div class="logo_wrapper bg-[#FFE5E5]">
      <img src="/assets/images/LogoSabiraEdit.jpg.webp" style="max-width: 170px">
    </div>
    <div>
      <ul class="nav flex-column nav-pills nav-fill">
        <li class="nav-item">
          <a id="nav_link"
            class="nav-link text-start d-flex align-items-center gap-2 {{ request()->is('dashboard*') ? 'active' : '' }}"
            href="">
            <i class="bi bi-house-door-fill"></i>
            <p class="mb-0">Dashboard</p>
          </a>
        </li>
      </ul>
      <ul class="nav flex-column nav-pills nav-fill">
        <li class="nav-item">
          <a id="nav_link"
            class="nav-link text-start d-flex align-items-center gap-2 {{ request()->is('dashboard*') ? 'active' : '' }}"
            href="">
            <i class="bi bi-house-door-fill"></i>
            <p class="mb-0">Dashboard</p>
          </a>
        </li>
      </ul>
    </div>
  </div>
  <div>
    <div class="container-fluid text-start w-full p-0 mb-3">
      <form method="POST" action="">
        @csrf
        <button style="background-color: #F3797E;" onmouseleave="this.style.backgroundColor='#F3797E';"
          onmouseenter="this.style.backgroundColor='rgb(241, 79, 85)';" class="btn btn-sm btn-danger w-100"
          type="submit">
          <i class="bi bi-box-arrow-left" style="font-size:16px;"></i>
          <span>Logout</span>
        </button>
      </form>
    </div>
    <div class="container-fluid w-full border-top border-3">
      <p id="copyright" style="font-size: 12px;">&copy; 2025 CV Sejahtera Mandiri Solusi</p>
    </div>
  </div>
</div> --}}


{{-- <div class="d-flex flex-column justify-content-between position-fixed vh-100 bg-white" style="width: 250px;"> --}}
@if (!Route::is('qrcode.render'))
  
<div class="d-none d-md-flex flex-column justify-content-between position-fixed vh-100 bg-white" style="width: 250px;">
  <div>
    <!-- Logo -->
    <div class="p-4 text-center" style="background-color: #F0F0F0">
      <img src="/assets/images/LogoSabiraEdit.jpg.webp" alt="Logo" class="img-fluid mb-3" style="max-width: 200px;">
    </div>
    <!-- Navigation -->
    <nav class="nav flex-column">
      {{-- <div class=" "> --}}
        <a href="{{ route('dashboard') }}"
          class="nav-link d-flex align-items-center py-3 gap-2 {{ request()->is('dashboard*') ? 'active bg-success text-white' : 'text-dark' }}">
          <i class="bi bi-house-door"></i>
          <span>Dashboard</span>
        </a>
      {{-- </div> --}}
      <a href="{{ route('inventaris.index') }}"
        class="nav-link d-flex align-items-center gap-2 py-3 {{ request()->is('inventaris*') ? 'active bg-success text-white' : 'text-dark' }}">
        <i class="bi bi-archive"></i>
        <span>Inventaris</span>
      </a>
      <a href="{{ route('peminjaman.index') }}"
        class="nav-link d-flex align-items-center gap-2 py-3 {{ request()->is('peminjaman*') ? 'active bg-success text-white' : 'text-dark' }}">
        <i class="bi bi-journal-arrow-up"></i>
        <span>Peminjaman</span>
      </a>
      <a href="{{ route('pembayaran.index') }}"
        class="nav-link d-flex align-items-center gap-2 py-3 {{ request()->is('pembayaran*') ? 'active bg-success text-white' : 'text-dark' }}">
        <i class="bi bi-receipt"></i>
        <span>Pembayaran</span>
      </a>
    </nav>  
  </div>

  

  <!-- Bottom Settings and Logout -->
  <div class="p-4">
    <a href="#"
      class="nav-link d-flex align-items-center gap-2 text-muted">
      <i class="bi bi-gear"></i>
      <span>Settings</span>
    </a>

    <form method="POST" action="{{ route('logout') }}" class="mt-2">
      @csrf
      <button type="submit" class="btn btn-link nav-link d-flex align-items-center gap-2 text-muted p-0">
        <i class="bi bi-box-arrow-left"></i>
        <span>Logout</span>
      </button>
    </form>
  </div>
</div>
@endif



@if (Route::is('qrcode.render*'))
  <div class="d-flex d-md-none justify-content-around fixed-bottom bg-success text-white py-2 shadow">
    <div class="text-center">
      <i class="bi bi-qr-code-scan fs-4"></i>
      <div style="font-size: 12px;">Scan</div>
    </div>
    <div class="text-center">
      <i class="bi bi-house-door fs-4"></i>
      <div style="font-size: 12px;">Home</div>
    </div>
    <div class="text-center">
      <i class="bi bi-box-arrow-left fs-4"></i>
      <div style="font-size: 12px;">Logout</div>
    </div>
  </div>
@endif