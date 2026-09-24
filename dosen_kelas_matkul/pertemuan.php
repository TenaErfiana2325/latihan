<?php
require_once '../database/koneksi.php';
$authority = @$_SESSION['peran'];
if ($authority != 'A') {
  echo '<script>alert("Akun ini melakukan cross authority, akan segera di logout");</script>';
  echo '<script>window.location.href="../logout.php"</script>';
} else {
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
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
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

    <!-- Main content -->
    <div class="content">
      <?php 
      $kode_kelas = isset($_GET['kode_kelas']) ? $_GET['kode_kelas'] : (isset($_GET['id']) ? $_GET['id'] : '');
      $panggil_kelas = mysqli_query($con, "SELECT * FROM tbl_kelasmatkul WHERE kode_kelas = '$kode_kelas'") or die(mysqli_error($con));
      $data = mysqli_fetch_array($panggil_kelas);

      $nama_kelas   = isset($data['nama_kelas']) ? $data['nama_kelas'] : '-';
      $kode_akd     = isset($data['kode_akd']) ? $data['kode_akd'] : '';
      $kode_matkul  = isset($data['kode_matkul']) ? $data['kode_matkul'] : '';
      $kode_jurusan = isset($data['kode_jurusan']) ? $data['kode_jurusan'] : '';
      $nik          = isset($data['nik']) ? $data['nik'] : '';
      ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Detail Data Kelas Mata Kuliah</h3>
              </div>
              <div class="card-body">
                <table width="100%" cellpadding="8">
                  <tbody>
                    <tr>
                      <td width="15%">NAMA KELAS</td>
                      <td width="2%">:</td>
                      <td width="33%"><?= $nama_kelas; ?></td>
                      <td width="15%">DOSEN</td>
                      <td width="2%">:</td>
                      <td width="33%">
                        <?php 
                        $query_dosen = mysqli_query($con, "SELECT nama FROM tbl_dosen WHERE nik = '$nik'") or die(mysqli_error($con));
                        $data_dosen = mysqli_fetch_array($query_dosen);
                        echo isset($data_dosen['nama']) ? $data_dosen['nama'] : '-';
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>PERIODE AKADEMIK</td>
                      <td>:</td>
                      <td>
                        <?php 
                        $query_akademik = mysqli_query($con, "SELECT tahun, semester FROM tbl_akademik WHERE kode_akd = '$kode_akd'") or die(mysqli_error($con));
                        $data_akademik = mysqli_fetch_array($query_akademik);
                        echo isset($data_akademik['tahun']) ? $data_akademik['tahun'] . ' - ' . ($data_akademik['semester'] == 'GN' ? 'Genap' : 'Ganjil') : '-';
                        ?>
                      </td>
                      <td>JURUSAN</td>
                      <td>:</td>
                      <td>
                        <?php 
                        $query_jurusan = mysqli_query($con, "SELECT nama_jurusan FROM tbl_jurusan WHERE kode_jurusan = '$kode_jurusan'") or die(mysqli_error($con));
                        $data_jurusan = mysqli_fetch_array($query_jurusan);
                        echo isset($data_jurusan['nama_jurusan']) ? $data_jurusan['nama_jurusan'] : '-';
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>MATA KULIAH</td>
                      <td>:</td>
                      <td colspan="4">
                        <?php 
                        $query_matkul = mysqli_query($con, "SELECT nama_matkul FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'") or die(mysqli_error($con));
                        $data_matkul = mysqli_fetch_array($query_matkul);
                        echo isset($data_matkul['nama_matkul']) ? $data_matkul['nama_matkul'] : '-';
                        ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="card card-primary">
              <div class="card-body">
                <h3><b>List Pertemuan</b></h3>
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah"> 
                  <i class="fas fa-plus"></i><b> Tambah Data</b>
                </button>
                <a href="../admin_data_kelas_makul/" class="btn btn-warning mb-2 text-white"><i class="fas fa-arrow-left"></i> <b>Kembali</b></a>
                
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th width="5%">No</th>
                      <th>Pertemuan Ke</th>
                      <th>Judul Pertemuan</th>
                      <th>Tanggal</th>
                      <th width="15%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $panggil_data_pertemuan = mysqli_query($con, "SELECT * FROM tbl_pertemuan WHERE kode_kelas='$kode_kelas'") or die(mysqli_error($con));
                  $no = 1;
                  $rv = mysqli_num_rows($panggil_data_pertemuan);
                  
                  if ($rv > 0) {
                    while ($data = mysqli_fetch_array($panggil_data_pertemuan)) {
                      ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data['pertemuan_ke'] ?></td>
                        <td><?= $data['judul_pertemuan'] ?></td>
                        <td><?= $data['tgl'] ?></td>
                        <td>
                          <a href='presensi.php?kode_pertemuan=<?=$data['kode_pertemuan'] ?>' class='btn btn-warning btn-sm'> <i class="fas fa-user-check"></i>Presensi</a>
                        </td>
                      </tr>
                      <?php
                    }
                  } else {
                    echo '<tr><td colspan="5" class="text-center">Data Tidak Ditemukan</td></tr>';
                  }
                  ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL TAMBAH DATA -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Pertemuan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="tambah_pertemuan.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="kode_kelas">Kode Kelas</label>
              <input type="text" name="kode_kelas" class="form-control" value="<?= $kode_kelas; ?>" readonly required>
            </div>
            <div class="form-group">
              <label for="tgl">Tanggal</label>
              <input type="date" name="tgl" class="form-control" value="<?= date('Y-m-d'); ?>" readonly required>
            </div>
            <div class="form-group">
              <label for="judul_pertemuan">Judul Pertemuan</label>
              <input type="text" name="judul_pertemuan" class="form-control" placeholder="Masukan Judul Pertemuan" required>
            </div>
            <div class="form-group">
              <label for="pertemuan_ke">Pertemuan Ke</label>
              <input type="number" name="pertemuan_ke" class="form-control" placeholder="Masukan Pertemuan Ke-" required>
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <select name="status" class="form-control" required>
                <option value="">--Pilih Status--</option>
                <option value="1">Aktif</option>
                <option value="0">Non-Aktif</option>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="btn_tambah" class="btn btn-primary">Tambah</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- MODAL IMPOR DATA -->
  <div class="modal fade" id="modal-impor">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="impor.php" method="post" enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title">Impor Data Mahasiswa Kelas Matkul</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="kode_kelas" value="<?= $kode_kelas; ?>">
            <div class="form-group">
              <label>Download File Template</label><br>
              <a href="template/template_mahasiswa_kelas.xls" download class="btn btn-success btn-sm">Download Template</a>
            </div>
            <div class="form-group">
              <label>Upload File Template</label>
              <input type="file" class="form-control" name="file_excel" required>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="btn_impor" class="btn btn-primary">Impor</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Main Footer -->
  <?php include '../footer.php'; ?>
</div>

<!-- REQUIRED SCRIPTS -->
<?php include '../script.php'; ?>
</body>
</html>
<?php
}
?>