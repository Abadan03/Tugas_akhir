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

  <title>Sabira IIBS Management Inventory</title>

  <!-- Bootstrap core CSS -->
  {{-- <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
  <div class="container-fluid d-grid align-items-center justify-content-center vh-100"
    style="background-color: #4B49AC; background-image: url('assets/images/Asrama.webp');">
    @include('layouts.flash-message')
    <div class="shadow border border-2 rounded-3 p-4 text-end" style="background-color: #F5F7FF;">
      <h3 class="text-center mb-4 text-secondary">Login Form</h3>
      <div class="w-full d-flex align-items-center justify-content-center">
        <img src="assets/images/LogoSabiraEdit.jpg.webp" style="max-width: 50%;" class="d-flex">
      </div>
      <div class="">
        <form action="" method="POST" class="w-full">
          @csrf
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            <label for="email">Email</label>
          </div>

          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            <label for="password">Password</label>
          </div>

          <button type="submit" class="btn btn-primary ">Login</button>
        </form>
        <!-- 
        <div class="text-center mt-3">
          <p>Belum punya akun?</p>
          <a href="/register" class="btn btn-orange w-100">Register</a>
        </div> -->
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>