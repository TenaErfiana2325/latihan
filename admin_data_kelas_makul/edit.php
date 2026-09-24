<?php
require_once '../database/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
  include '../css.php';

  $hal ='beranda_kelasmatkul';
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
            <i class="fas fa-user"></i> Profile
          </a>
          <a href="#" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i> logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">Sistem Manajemen</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <?php include '../sidebar_admin.php'; ?>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Edit Data Kelas Matkul</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <?php 
          $kode_akd = @$_GET['data'] ?? @$_GET['kode_akd'];
          $query = mysqli_query($con, "SELECT * FROM tbl_kelasmatkul WHERE kode_akd = '$kode_akd'") or die(mysqli_error($con));
          $data = mysqli_fetch_assoc($query);

          $id = $data['id'] ?? '';
          $kode_akd = $data['kode_akd'] ?? '';
          $kode_matkul = $data['kode_matkul'] ?? '';
          $kode_jurusan = $data['kode_jurusan'] ?? '';
          $nik = $data['nik'] ?? '';
          $nama_kelas = $data['nama_kelas'] ?? '';
          $bobot_presensi = $data['bobot_presensi'] ?? 10;
          ?>
            <form action="ubah.php" method="post">
              <div class="form-group">
                <label for="id">ID</label>
                <input type="number" name="id_disable" class="form-control" id="id" value="<?= $id; ?>" placeholder="Masukan ID" disabled>
                <input type="number" name="id" class="form-control" value="<?= $id; ?>" hidden>
              </div>
              <div class="form-group">
                <label for="kode_akd">Kode Akademik</label>
                <input type="text" name="kode_akd" class="form-control" id="kode_akd" value="<?= $kode_akd; ?>" placeholder="Masukan Kode Akademik" required>
              </div>
              <div class="form-group">
                <label for="kode_matkul">Kode Matkul</label>
                <input type="text" name="kode_matkul" class="form-control" id="kode_matkul" value="<?= $kode_matkul; ?>" placeholder="Masukan Kode Matkul" required>
              </div>
              <div class="form-group">
                <label for="kode_jurusan">Kode Jurusan</label>
                <input type="text" name="kode_jurusan" class="form-control" id="kode_jurusan" value="<?= $kode_jurusan; ?>" placeholder="Masukan Kode Jurusan" required>
              </div>
              <div class="form-group">
                <label for="nik">NIK</label>
                <input type="text" name="nik" class="form-control" id="nik" value="<?= $nik; ?>" placeholder="Masukan NIK" required>
              </div>
              <div class="form-group">
                <label for="nama_kelas">Nama Kelas</label>
                <input type="text" name="nama_kelas" class="form-control" id="nama_kelas" value="<?= $nama_kelas; ?>" placeholder="Masukan Nama Kelas" required>
              </div>
              <div class="form-group">
                <label for="bobot_presensi">Bobot Presensi (%)</label>
                <input type="number" name="bobot_presensi" class="form-control" id="bobot_presensi" value="<?= $bobot_presensi; ?>" min="1" max="100" placeholder="Contoh: 10" required>
              </div>

              <div class="modal-footer justify-content-between px-0 pb-0">
                <button type="submit" name="btn_edit" class="btn btn-primary btn-block">Edit</button>
              </div>
            </form>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
  </aside>
  <!-- /.control-sidebar -->

  <!-- Modal Tambah -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Pengguna</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="tambah.php" method="post">
        </form>
      </div>
    </div>
  </div>

  <!-- Main Footer -->
  <?php include '../footer.php'; ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include '../script.php'; ?>
</body>
</html>