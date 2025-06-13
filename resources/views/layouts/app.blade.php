<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


  <title>Sabira IIBS Management Inventory</title>
  <link rel="stylesheet" href="{{ asset("/assets/css/home.css") }}">

  {{-- template cdn for tables --}}
  <!-- Bootstrap 5 CSS -->
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> --}}
<!-- Data Table CSS -->
<link rel='stylesheet' href='https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css'>
<!-- Font Awesome CSS -->
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css'>
  {{-- <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> --}}
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

  <main class="d-flex">
    <div class="sidebar">
        @include('layouts.sidebar')
    </div>
    <div class="content flex-grow-1" style="margin-left: 250px;">
        @yield('content')
    </div>
  </main>
  {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  @vite(['resources/js/app.js'])
  @stack('scripts')

</body>