<?php
require_once "../database/koneksi.php";
$authority = @$_SESSION['peran'];
if ($authority != 'D') {
  echo '<script>alert("Akun ini melakukan cross authority, akan segera di logout";</script>';
  echo '<script>window.location.href="../logout.php"</script>';
}else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  include '../css.php';
  $hal = 'ganti_password';
  ?>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i> 
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Profile
          </a>
          <div class="dropdown-divider"></div>
          <a href="../logout.php" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> logout
          </a>
        </div>
      </li>
      <li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">SISTEM MANAJEMEN</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <?php
      include '../sidebar_dosen.php';
      ?>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class ="row">
            <div class="col-lg-4">
                <div class="card card-primary">
                    <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-lock"></i>Ganti Password</h3>
                </div>
                 <div class="card-body">
                    <form action= "" method= "post"> 
                    <div class="form-group">
                        <label for="passsword_lama">Password Lama</label>
                        <?php
                        $pengguna =@$_SESSION['username'];
                        ?>
                        <input type="text" value="<?= $pengguna; ?>" name="pengguna" class="form-control" hidden>
                        <input type="password" name="password_lama" class="form-control" placeholder="input password lama">
                    </div>
                    <div class="form-group">
                        <label for="passsword_baru">Password Baru</label>
                        <input type="password" name="password_baru" class="form-control" maxlength="10" placeholder="input password baru Max 10 Char">
                    </div>
                    <div class="form-group">
                        <label for="pin2fa">PIN</label>
                        <input type="number" pin="pin" class="form-control" maxlength="6" placeholder="input pin anda" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" name ="edit_pw" class="btn btn-primary btn-block"><i class="fas fa-edit"></i> Edit </button>
                    </div>
                    </form>
                    <?php
                    if (isset($_POST['edit_pw'])) {  //triger button edit ketika ditekan
                        $pengguna       = trim(mysqli_real_escape_string($con, $_POST['pengguna']));  //menyimpan value pengguna pd variabel lokal pengguna 
                        $query_pengguna = mysqli_query($con, "SELECT sandi, pin FROM tbl_pengguna WHERE username = '$pengguna'") or die (mysqli_error($con));  //memanggil pin dari tbl pengguna
                        $arr            = mysqli_fetch_assoc($query_pengguna);  //menbdefinisikan variabel dolar arr untk menyimpan array dari query
                        $sandi          = $arr['sandi']; //menampung value sandi pd array kdalam variabek dolar sandi
                        $pin            = $arr ['pin'];  //meampung value pin pd array kdalam variabel $pin
                        $inputan_sandi      = sha1(trim(mysqli_real_escape_string($con, $_POST['password_lama']))); //meyimpan value inputan sandi dari sandi dari user ke variabel lokal $inputan_sandi
                        $inputan_sandi_baru = sha1(trim(mysqli_real_escape_string($con, $_POST['password_baru'])));   //meyimpan value inputan sandi dari sandi dari user ke variabel lokal $inputan_sandi
                        $pin_input = $_POST['pin'] ?? '';
                        $pin = mysqli_real_escape_string($con, $pin_input);

                        if ($inputan_sandi == $sandi && $inputan_pin == $pin)  {
                            $query_update = mysqli_query ($con,"UPDATE tbl_pengguna SET sandi = '$inputan_sandi_baru' WHERE username = '$pengguna' ") or die(mysqli_error($con));  //query utk update sandi
                            echo '<script> alert("password Berhasil Di Update!!") </script>';
                            echo '<script> window.location.href="../dosen_ganti_password" </script>';
                        }else { 
                            echo '<script> alert("password atau Pin salah!!") </script>';
                        }    
                    }
                    ?>
                </div>
                </div>
            </div>
            </div>
       </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
   <?php
    include '../footer.php';
    ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php
include '../script.php';
?>
</body>

</html>
<?php
}
?>