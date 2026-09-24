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

  $hal ='akademik';
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
            <h3 class="card-title">Edit Akademik</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <?php 
            $kode_akd = @$_GET['kode_akd'];
            $query = mysqli_query($con, "SELECT * FROM tbl_akademik WHERE kode_akd = '$kode_akd'") or die(mysqli_error($con));
            $data = mysqli_fetch_assoc($query);
            $semester  = $data['semester'] ?? '';
            $tahun     = $data['tahun'] ?? '';
            $is_active = $data['is_active'] ?? '0';
            ?>
            <form action="ubah.php" method="post">
              <div class="form-group">
                <label for="kode_akd">Kode Akademik</label>
                <input type="text" name="kode_akd_disable" class="form-control" id="kode_akd" value="<?= $kode_akd; ?>" placeholder="Masukkan kode_akd" disabled>
                <input type="text" name="kode_akd" class="form-control" value="<?= $kode_akd; ?>" hidden>
              </div>
              <div class="form-group">
                <label for="semester">Semester</label>
                <select name="semester" class="form-control" id="semester" required>
                  <option value="GN" <?= ($semester == 'GN') ? 'selected' : ''; ?>>Ganjil (GN)</option>
                  <option value="GL" <?= ($semester == 'GL') ? 'selected' : ''; ?>>Genap (GL)</option>
                </select>
              </div>
              <div class="form-group">
                <label for="tahun">Tahun</label>
                <input type="text" name="tahun" class="form-control" id="tahun" value="<?= $tahun; ?>" placeholder="Masukkan tahun" maxlength="4" required>
              </div>
              <div class="form-group">
                <label for="is_active">Status Aktif</label>
                <select name="is_active" class="form-control" id="is_active" required>
                  <option value="1" <?= ($is_active == '1') ? 'selected' : ''; ?>>Aktif (1)</option>
                  <option value="0" <?= ($is_active == '0') ? 'selected' : ''; ?>>Tidak Aktif (0)</option>
                </select>
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