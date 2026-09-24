<?php
require_once '../database/koneksi.php';
// Cek otoritas peran Dosen (misal: 'D')
$authority = @$_SESSION['peran'];
if ($authority != 'D') {
  echo '<script>alert("Akun ini melakukan cross authority, akan segera di logout");</script>';
  echo '<script>window.location.href="../logout.php"</script>';
} else {
  // Ambil NIK Dosen dari session login
  $nik_dosen = $_SESSION['username']; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
  include '../css.php';
  $hal = 'kelas_matkul';
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
          <?= $_SESSION['nama']; ?> - [<?= $_SESSION['peran']; ?>] <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-user"></i> Profile
          </a>
          <a href="../logout.php" class="dropdown-item">
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
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">Sistem Manajemen</a>
        </div>
      </div>

      <!-- Sidebar Menu Dosen -->
      <?php include '../sidebar_dosen.php'; ?>
    </div>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <form action="" method="post">
                <div class="row">
                    <div class="col-3">
                        <?php 
                        $panggil_periode_akademik = mysqli_query($con, "SELECT * FROM tbl_akademik") or die($con);
                        ?>
                        <div class="form-group">                    
                            <select class="form-control" name="semester" id="">
                                <?php 
                                while ($data_periode = mysqli_fetch_array($panggil_periode_akademik)) {
                                    $kode_akd = $data_periode['kode_akd'];
                                    $semester = $data_periode['semester'];
                                    $tahun = $data_periode['tahun']; ?>
                                <option value="<?= $kode_akd; ?>"><?= $tahun ?> - <?= ($semester == 'GL') ? 'Ganjil' : 'Genap' ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <button type="submit" name="btn_cari" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan Data</button>
                    </div>
                </div>
            </form>

            <?php 
            if (isset($_POST['btn_cari'])) {
              $filter = trim(mysqli_real_escape_string($con, $_POST['semester']));
            ?>
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Data Kelas Mata Kuliah Yang Diampu</h3>
                    </div>
                    <div class="card-body">
                
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th>Nama Kelas</th>
                    <th>Periode Akademik</th>
                    <th>Matkul</th>
                    <th>Jurusan</th>
                    <th>Dosen</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                  // QUERY KUNCI: Menampilkan kelas sesuai kode_akd DAN NIK Dosen Login
                  $panggil_data_jurusan = mysqli_query($con, "SELECT * FROM tbl_kelasmatkul WHERE kode_akd = '$filter' AND nik = '$nik_dosen'") or die(mysqli_error($con));
                  
                  $no = 1;
                  $rv = mysqli_num_rows($panggil_data_jurusan);
                  if ($rv > 0) {
                    while ($data = mysqli_fetch_array($panggil_data_jurusan)) {
                      $kode_kelas = $data['kode_kelas'];
                      $kode_akd = $data['kode_akd'];
                      $kode_matkul = $data['kode_matkul'];
                      $kode_jurusan = $data['kode_jurusan'];
                      $nik = $data['nik'];
                      $nama_kelas = $data['nama_kelas'];
                      ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $nama_kelas ?></td>
                        <td><?php 
                        $query_akademik = mysqli_query($con, "SELECT tahun,semester FROM tbl_akademik WHERE kode_akd = '$kode_akd'") or die(mysqli_error($con));
                        $data_akademik = mysqli_fetch_array($query_akademik);
                        echo $data_akademik['tahun'] . ' - ' . ($data_akademik['semester'] == 'GN' ? 'Genap' : 'Ganjil');
                        ?></td>
                        <td><?php 
                        $query_matkul = mysqli_query($con, "SELECT nama_matkul FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'") or die(mysqli_error($con));
                        $data_matkul = mysqli_fetch_array($query_matkul);
                        echo $data_matkul['nama_matkul'];
                        ?></td>
                        <td><?php 
                        $query_jurusan = mysqli_query($con, "SELECT nama_jurusan FROM tbl_jurusan WHERE kode_jurusan = '$kode_jurusan'") or die(mysqli_error($con));
                        $data_jurusan = mysqli_fetch_array($query_jurusan);
                        echo $data_jurusan['nama_jurusan'];
                        ?></td>
                        <td><?php 
                        $query_dosen = mysqli_query($con, "SELECT nama FROM tbl_dosen WHERE nik = '$nik'") or die(mysqli_error($con));
                        $data_dosen = mysqli_fetch_array($query_dosen);
                        echo $data_dosen['nama'];
                        ?></td>
                       
                        <td>
                          <a href='detail_kelas.php?kode_kelas=<?=$data['kode_kelas'] ?>' class='btn btn-success btn-sm' title="Detail Kelas"> <i class="fas fa-eye"></i></a>
                          <a href='presensi.php?kode_kelas=<?=$data['kode_kelas'] ?>' class='btn btn-warning btn-sm' title="Presensi"> <i class="fas fa-qrcode"></i></a>
                        </td>
                      </tr>
                      <?php
                    }
                  } else {
                    echo '<tr><td colspan="7" class="text-center">Data Tidak Ditemukan / Anda tidak mengampu kelas pada periode ini</td></tr>';
                  }
                  ?>
                  </tbody>
                </table>
              </div>
            </div>
            <?php 
            }
            ?>
        </div>
    </div>
  </div>

  <!-- Main Footer -->
  <?php include '../footer.php' ?>
</div>

<!-- REQUIRED SCRIPTS -->
<?php include '../script.php' ?>

</body>
</html>
<?php
}
?>