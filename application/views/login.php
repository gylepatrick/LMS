<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LibMS1.0.2 | Login</title>

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
      background: linear-gradient(to right,#d71908, #e74a3b);
    }

    .login-wrapper {
      max-width: 400px;
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
      background: #e74a3b;
      color:rgb(18, 18, 18);
      font-size: 18px;
      font-weight: 500;
      transition: 0.3s ease;
      border-radius: 5px;
    }

    .login-button .btn:hover {
      background:#730c02;
      color:rgb(244, 244, 245);
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
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

<div class="login-wrapper shadow-lg" style="border: 2px solid rgb(228, 228, 228); bacground-image: url('<?php echo base_url('assets/images/test.jpg'); ?>'); background-size: cover; background-position: center;">

    <div class="text-center" id="ims-page">
    <h3>LibMS V1.0.2 BETA</h3>
    <h5 class="text-danger">Library Management System</h5>
    </div>
  <form action="<?php echo site_url('library/submit'); ?>" method="post">
    <h6 class="text-center">Login to your account</h6>
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

    <div class="login-button text-center">
      <button type="submit" class="btn">Login</button>
    </div>
  </form>

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
  