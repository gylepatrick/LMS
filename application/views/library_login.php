<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LMS | Login</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Font Awesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
 <!-- Include SweetAlert2 CSS and JS -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Custom Styles -->
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background: url('https://source.unsplash.com/1600x900/?inventory,warehouse') no-repeat center center/cover;
    }

    .login-wrapper {
      max-width: 600px;
      width: 100%;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }


    .login-input {
      position: relative;
    }

    .login-input i {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      color: #1e3a8a;
    }

    .login-input input {
      padding-left: 45px;
      height: 50px;
      font-size: 16px;
    }

    .login-button .btn {
      width: 50%;
      background: #1e3a8a;
      color: #ffb703;
      font-size: 18px;
      font-weight: 500;
      transition: 0.3s ease;
      border-radius: 5px;
    }

    .login-button .btn:hover {
      background: #ffb703;
      color: #1e3a8a;
    }

    .pass a {
      color: #1e3a8a;
      text-decoration: none;
      font-size: 15px;
    }

    .pass a:hover {
      text-decoration: underline;
    }
    #ims-page {
      margin-bottom: 60px;
    }
  </style>
</head>
<body>

<div class="login-wrapper shadow-lg">

    <div class="text-center" id="ims-page">
    <h1>LMS V1.0</h1>
    <h4>Library Management System</h4>
    </div>
  <form action="<?php echo site_url('library/submit'); ?>" method="post">
    
    <div class="mb-3 login-input">
      <i class="fas fa-user"></i>
      <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>

    <div class="mb-3 login-input">
      <i class="fas fa-lock"></i>
      <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
    </div>

    <div class="mb-2 form-check">
      <input type="checkbox" class="form-check-input" id="showHide">
      <label class="form-check-label" for="showHide">Show Password</label>
    </div>

    <div class="mb-3 pass text-center">
      <a href="#">Forgot password?</a>
    </div>

    <div class="login-button text-center">
      <button type="submit" class="btn">Login</button>
    </div>
  </form>

  <div class="text-center mt-3">
    <a href="<?= base_url("/login") ?>" class="text-decoration-none">Login to IMS </i></a>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.getElementById("showHide").addEventListener("click", function () {
    let pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
  });
</script>

</body>
</html>
  