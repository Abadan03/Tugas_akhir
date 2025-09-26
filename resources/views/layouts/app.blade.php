<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  {{-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}


  <title>Sabira IIBS Management Inventory</title>
  <link rel="stylesheet" href="{{ asset("/assets/css/home.css") }}">
  {{-- <link rel="stylesheet" href="{{ secure_asset("/assets/css/home.css") }}"> --}}
  {{-- <link rel="stylesheet" href="{{ request()->secure() ? secure_asset('assets/css/home.css') : asset('assets/css/home.css') }}"> --}}


  {{-- template cdn for tables --}}
  <!-- Bootstrap 5 CSS -->
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> --}}
<!-- Data Table CSS -->
<link rel='stylesheet' href='https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css'>
<!-- Font Awesome CSS -->
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css'>

<i class="bi bi-eye"></i>

  {{-- <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> --}}

  {{-- QR CODE ===== --}}
  <script src=
    "https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body class="">
  {{-- <main class="flex">
    <div class="shadow sidebar">
      @include('layouts.sidebar')
    </div>
    <div id="main_container" class="content flex">
      @yield('content')
    </div>
  </main> --}}
  @php
    use Illuminate\Support\Facades\Route;
  @endphp

  <main class="d-flex">
    {{-- <div class="sidebar">
        @include('layouts.sidebar')
    </div>
    <div class="content flex-grow-1" style="margin-left: 250px;">
        @yield('content')
    </div> --}}

    {{-- @if (!Route::is('qrcode.render*'))
      <div class="sidebar d-none d-md-block">
        @include('layouts.sidebar')
      </div>
      @endif --}}
    <div class="sidebar">
      @include('layouts.sidebar')
    </div>

    <div class="content flex-grow-1" style="{{ Route::is('qrcode.render*') ? '' : 'margin-left: 250px;' }}">
      @yield('content')
    </div>
  </main>
  {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
  <script src="https://unpkg.com/html5-qrcode"></script>
  {{-- @vite(['resources/js/app.js']) --}}
  @vite(['resources/sass/app.scss', 'resources/js/app.js'])

  @stack('scripts')

</body>