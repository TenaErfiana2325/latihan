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
    <div class="content-header">
      <div class="container-fluid"></div>
    </div>

    <!-- Main content -->
    <div class="content">
      <?php 
      $kode_kelas = $_GET['kode_kelas'];
      $panggil_kelas = mysqli_query($con, "SELECT * FROM tbl_kelasmatkul WHERE kode_kelas = '$kode_kelas'") or die(mysqli_error($con));
      
      while ($data = mysqli_fetch_array($panggil_kelas)) {
        $kode_kelas = $data['kode_kelas'];
        $kode_akd = $data['kode_akd'];
        $kode_matkul = $data['kode_matkul'];
        $kode_jurusan = $data['kode_jurusan'];
        $nik = $data['nik'];
        $nama_kelas = $data['nama_kelas'];
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
                      <td>NAMA KELAS</td>
                      <td>:</td>
                      <td><?= $nama_kelas; ?></td>
                      <td> </td>
                      <td>DOSEN</td>
                      <td>:</td>
                      <td>
                        <?php 
                        $query_dosen = mysqli_query($con,"SELECT nama FROM tbl_dosen WHERE nik = '$nik'") or die(mysqli_error($con));
                        $data_dosen = mysqli_fetch_array($query_dosen);
                        echo $data_dosen['nama'];
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>PERIODE AKADEMIK</td>
                      <td>:</td>
                      <td>
                        <?php 
                        $query_akademik = mysqli_query($con,"SELECT tahun,semester FROM tbl_akademik WHERE kode_akd = '$kode_akd'") or die(mysqli_error($con));
                        $data_akademik = mysqli_fetch_array($query_akademik);
                        echo $data_akademik['tahun']. ' - '.($data_akademik['semester']=='GN'? 'Genap' : 'Ganjil');
                        ?>
                      </td>
                      <td> </td>
                      <td>JURUSAN</td>
                      <td>:</td>
                      <td>
                        <?php 
                        $query_jurusan = mysqli_query($con,"SELECT nama_jurusan FROM tbl_jurusan WHERE kode_jurusan = '$kode_jurusan'") or die(mysqli_error($con));
                        $data_jurusan = mysqli_fetch_array($query_jurusan);
                        echo $data_jurusan['nama_jurusan'];
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>MATA KULIAH</td>
                      <td>:</td>
                      <td>
                        <?php 
                        $query_matkul = mysqli_query($con,"SELECT nama_matkul FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'") or die(mysqli_error($con));
                        $data_matkul = mysqli_fetch_array($query_matkul);
                        echo $data_matkul['nama_matkul'];
                        ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Perbaikan class card-primary -->
            <div class="card card-primary">
              <div class="card-body">
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah"> <i class="fas fa-plus"></i><b> Tambah Data</b></button>
                <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#modal-impor"> <i class="fas fa-file-excel"></i><b> Impor Data</b></button>
                <a href="../admin_data_kelas_makul/" class="btn btn-warning mb-2"> <i class="fas fa-arrow-left"></i> Kembali</a>
                
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th width="5%">No</th>
                      <th>Mahasiswa</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $panggil_data_peserta = mysqli_query($con, "SELECT * FROM tbl_peserta WHERE kode_kelas='$kode_kelas'") or die(mysqli_error($con));
                  $no = 1;
                  $rv = mysqli_num_rows($panggil_data_peserta);
                  if ($rv > 0) {
                    while ($data_p = mysqli_fetch_array($panggil_data_peserta)) {
                      $nim = $data_p['nim'];
                      ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td>
                          <?php 
                          $query_mahasiswa = mysqli_query($con, "SELECT nim, nama FROM tbl_mahasiswa WHERE nim = '$nim'") or die(mysqli_error($con));
                          $data_mahasiswa = mysqli_fetch_array($query_mahasiswa);

                          // Gunakan perbaikan ini:
                          $nim_mhs  = $data_mahasiswa['nim'] ?? $nim;
                          $nama_mhs = $data_mahasiswa['nama'] ?? 'Mahasiswa Tidak Ditemukan';

                          echo $nim_mhs . ' - ' . $nama_mhs;
                          ?>
                        </td>
                        <td>
                          <a href='hapus.php?id=<?=$data_p['id'] ?>&kode_kelas=<?= $data_p['kode_kelas'] ?>' class='btn btn-danger btn-sm' onclick="return confirm('Yakin ingin hapus mahasiswa ini?')"> <i class="fas fa-trash"></i></a>
                        </td>
                      </tr>
                      <?php
                    }
                  } else {
                    echo '<tr><td colspan="3" class="text-center">Data Tidak Ditemukan</td></tr>';
                  }
                  ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>

  <aside class="control-sidebar control-sidebar-dark"></aside>

  <!-- Modal Tambah Data (Perbaikan Tag Form) -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Mahasiswa Mata Kuliah</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="tambah.php" method="post">
          <div class="modal-body">
            <?php 
            $query_mahasiswa = mysqli_query($con, "SELECT * FROM tbl_mahasiswa") or die(mysqli_error($con));
            ?>
            <div class="form-group">
              <input type="hidden" name="kode_kelas" id="kode_kelas" value="<?= $kode_kelas ?>">
            </div>

            <div class="form-group">
              <label for="nim">Mahasiswa</label>
              <select name="nim" id="nim" class="form-control" required>
                <option value="">--Pilih Mahasiswa--</option>
                <?php while ($dm = mysqli_fetch_array($query_mahasiswa)) { ?>
                  <option value="<?= $dm['nim']?>"><?= $dm['nama'] ?></option>
                <?php } ?>
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

  <!-- Modal Impor Data (Perbaikan Tag Form) -->
  <div class="modal fade" id="modal-impor">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Impor Data Mahasiswa Kelas Matkul</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="impor.php" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">
              <input type="hidden" name="kode_kelas" value="<?= $kode_kelas ?>">
              <label>Download File Template</label><br>
              <a href="template/template_mahasiswa_kelas.xls" download class="btn btn-success btn-sm">Download</a>
              <br><br>
              <label>Download Data Mahasiswa</label><br>
              <a href="../admin_data_mahasiswa/excel.php" download class="btn btn-success btn-sm">Download</a>
            </div>
            <div class="form-group">
              <label for="file_excel">Upload File Template</label>
              <input type="file" class="form-control" name="file_excel" id="file_excel" required>
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

  <?php include '../footer.php'; ?>
</div>

<?php include '../script.php'; ?>
</body>
</html>
<?php
}
?>