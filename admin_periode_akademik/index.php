<?php
 require_once  '../database/koneksi.php';
$authority = @$_SESSION['peran'];
if ($authority != 'A') {
  echo '<script>alert("akun ini melakukan cross authority, akan segera di logout");</script>';
  echo '<script>window.location.href= "../logout.php" </script>';
}else {
?>
<?php
$hal = 'akademik';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Periode Akademik</title>
  <?php include '../css.php'; ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
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
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">Sistem Manajemen</a>
        </div>
      </div>

      <?php include '../sidebar_admin.php'; ?>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid"></div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Data Periode Akademik</h3>
          </div>
          <div class="card-body">
            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah"> 
              <i class="fas fa-plus"></i><b> Tambah Data</b>
            </button>
            <a href ="halaman_tambah.php" type="button" class="btn btn-success mb-2" >tambah data 2</a>
             <a href="reset.php" type="button" class="btn btn-danger mb-2" onclick="return confirm ('anda yakin ingin mereset data ini')">
            <i class="fa fa-exclamation-triangle">reset data</i></a>

            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th width="5%">No</th>
                <th>Kode Akademik</th>
                <th>Semester</th>
                <th>Tahun</th>
                <th>Status</th>
                <th width="15%">Aksi</th>
              </tr>
              </thead>
              <tbody>
              <?php 
              $panggil_data_akd = mysqli_query($con, "SELECT * FROM tbl_akademik") or die(mysqli_error($con));
              $no = 1;
              $rv = mysqli_num_rows($panggil_data_akd);
              if ($rv > 0) {
                while ($data = mysqli_fetch_array($panggil_data_akd)) {
                  $kode_akd  = $data['kode_akd'] ?? '';
                  $semester  = $data['semester'] ?? '';
                  $tahun     = $data['tahun'] ?? '';
                  $is_active = $data['is_active'] ?? '';
                  ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($kode_akd) ?></td>
                   <td>
                      <?php 
                      if ($semester == 'GN') {
                        echo 'Genap';
                      } elseif ($semester == 'GL') {
                        echo 'Ganjil';
                      } else {
                        echo '-';
                      }
                      ?>
                    </td>
                    <td><?= htmlspecialchars($tahun) ?></td>
                    <td>
                      <?php 
                      if ($is_active == '1') {
                        echo '<span class="badge badge-success">Aktif</span>';
                      } else {
                        echo '<span class="badge badge-secondary">Tidak Aktif</span>';
                      }
                      ?>
                    </td>
                    <td>
                      <a href='edit.php?kode_akd=<?= urlencode($kode_akd); ?>' class='btn btn-warning btn-sm'><i class="fas fa-pen"></i> Edit</a>  
                      <a href='hapus.php?kode_akd=<?= urlencode($kode_akd); ?>' class='btn btn-danger btn-sm' onclick="return confirm('Yakin ingin hapus ini?')"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                  </tr>
                  <?php
                }
              } else {
                echo '<tr><td colspan="6" class="text-center">Data Tidak Ditemukan</td></tr>';
              }
              ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <aside class="control-sidebar control-sidebar-dark"></aside>

  <!-- Modal Tambah Data Periode Akademik -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Periode Akademik</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="tambah.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="kode_akd">Kode Akademik</label>
              <input type="text" maxlength="10" name="kode_akd" class="form-control" id="kode_akd" placeholder="Masukan Kode Akademik" required>
            </div>
            <div class="form-group">
              <label for="semester">Semester</label>
              <select class="form-control" name="semester" id="semester" required>
                <option value="">-- Pilih Semester --</option>
                <option value="GL">Ganjil (GL)</option>
                <option value="GN">Genap (GN)</option>
              </select>
            </div>
            <div class="form-group">
              <label for="tahun">Tahun</label>
              <input type="text" maxlength="4" name="tahun" class="form-control" id="tahun" placeholder="Masukan Tahun (Contoh: 2026)" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
            </div>
            <div class="form-group">
              <label for="is_active">Status Aktif</label>
              <select class="form-control" name="is_active" id="is_active" required>
                <option value="">-- Pilih Status --</option>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="btn-tambah" class="btn btn-primary">Tambah</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include '../footer.php'; ?>
</div>

<?php include '../script.php'; ?>
</body>
</html>
<?php
}
?>