<?php
require_once '../database/koneksi.php';

// Pastikan session sudah jalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../aset_web/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../aset_web/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../aset_web/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>2 FACTOR AUTHENTICATION</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="number" name="pin" class="form-control" placeholder="Masukan Pin Anda" required autocomplete="off">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="social-auth-links text-center mb-3">
          <button type="submit" name="submit" class="btn btn-block btn-primary">
            <i class="fas fa-sign-in-alt mr-2"></i> Submit PIN
          </button>
        </div>
      </form>

      <?php
      if (isset($_POST['submit'])) {
        // Ambil data session (gunakan null coalescing '??' untuk mencegah undefined index)
        $pin      = $_SESSION['pin'] ?? '';
        $peran    = $_SESSION['peran'] ?? '';
        $username = $_SESSION['username'] ?? '';
        $nama     = $_SESSION['nama'] ?? '';

        $pin_inputan_pengguna = trim(mysqli_real_escape_string($con, $_POST['pin']));

        // PERBAIKAN: Gunakan '==' untuk pembandingan
        if ($pin_inputan_pengguna == $pin && !empty($pin)) {
            if ($peran == 'M') {
                echo '<script>window.location = "../home_mahasiswa/";</script>';
            } elseif ($peran == 'D') {
                echo '<script>window.location = "../home_dosen/";</script>';
            } else {
                echo '<script>window.location = "../home_admin/";</script>';
            }
        } else {
            echo '<script>
                alert("PIN ANDA SALAH! SILAHKAN LOGIN KEMBALI");
                window.location = "../";
            </script>';
        }
      }
      ?>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../aset_web/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../aset_web/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../aset_web/dist/js/adminlte.min.js"></script>
</body>
</html>