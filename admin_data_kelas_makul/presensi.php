<?php
session_start(); 
require_once '../database/koneksi.php';

// Set timezone secara global di awal script
date_default_timezone_set('Asia/Jakarta');

if (!isset($con) && isset($koneksi)) {
    $con = $koneksi;
}

$authority = isset($_SESSION['peran']) ? $_SESSION['peran'] : '';
if ($authority != 'A') {
    echo '<script>alert("Akun ini melakukan cross authority, akan segera di logout");</script>';
    echo '<script>window.location.href="../logout.php"</script>';
    exit;
}

// Ambil parameter URL
$kode_pertemuan = isset($_GET['kode_pertemuan']) ? trim($_GET['kode_pertemuan']) : ''; 
$kode_kelas_param = isset($_GET['kode_kelas']) ? trim($_GET['kode_kelas']) : '';

// Mengambil data pertemuan menggunakan Prepared Statement
if (!empty($kode_pertemuan)) {
    $stmt = mysqli_prepare($con, "SELECT * FROM tbl_pertemuan WHERE kode_pertemuan = ?");
    mysqli_stmt_bind_param($stmt, "s", $kode_pertemuan);
    mysqli_stmt_execute($stmt);
    $ambil_pertemuan = mysqli_stmt_get_result($stmt);
} else {
    $stmt = mysqli_prepare($con, "SELECT * FROM tbl_pertemuan WHERE kode_kelas = ? ORDER BY kode_pertemuan DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $kode_kelas_param);
    mysqli_stmt_execute($stmt);
    $ambil_pertemuan = mysqli_stmt_get_result($stmt);
}

$data_pertemuan = mysqli_fetch_array($ambil_pertemuan);

// Tentukan kembali kode_pertemuan jika awalnya kosong (diambil dari hasil query)
if (empty($kode_pertemuan) && isset($data_pertemuan['kode_pertemuan'])) {
    $kode_pertemuan = $data_pertemuan['kode_pertemuan'];
}

$status_pertemuan = isset($data_pertemuan['status']) && $data_pertemuan['status'] !== '' ? $data_pertemuan['status'] : '0';
$waktu_dibuka = isset($data_pertemuan['waktu_dibuka']) && !empty($data_pertemuan['waktu_dibuka']) ? strtotime($data_pertemuan['waktu_dibuka']) : 0;
$durasi_detik = 30; // 30 detik untuk pengujian
$waktu_tutup = $waktu_dibuka + $durasi_detik;
$sisa_detik = $waktu_tutup - time();
if ($sisa_detik < 0) {
    $sisa_detik = 0;
}

// Otomatis ubah status di database jika status '1' tapi waktu sudah habis
if ($status_pertemuan == '1' && $sisa_detik <= 0 && !empty($kode_pertemuan)) {
    header("Location: ubah_status.php?kode_pertemuan=" . urlencode($kode_pertemuan) . "&status=0");
    exit;
}
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
         <?= htmlspecialchars(isset($_SESSION['nama']) ? $_SESSION['nama'] : ''); ?> - [<?= htmlspecialchars(isset($_SESSION['peran']) ? $_SESSION['peran'] : ''); ?>] <i class="far fa-user"></i>
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
      <div class="container-fluid">

        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <?php  
                $kode_kelas = isset($data_pertemuan['kode_kelas']) ? $data_pertemuan['kode_kelas'] : $kode_kelas_param;
                $kode_kls = $kode_kelas; 
                $tgl = isset($data_pertemuan['tgl']) ? $data_pertemuan['tgl'] : date('Y-m-d'); 
                $hari = date('l', strtotime($tgl)); 
                $pertemuan = isset($data_pertemuan['pertemuan_ke']) ? $data_pertemuan['pertemuan_ke'] : '-';
                
                $stmt_kls = mysqli_prepare($con, "SELECT * FROM tbl_kelasmatkul WHERE kode_kelas = ?");
                mysqli_stmt_bind_param($stmt_kls, "s", $kode_kelas);
                mysqli_stmt_execute($stmt_kls);
                $data_kls = mysqli_fetch_array(mysqli_stmt_get_result($stmt_kls)); 
                
                $nik = isset($data_kls['nik']) ? $data_kls['nik'] : ''; 
                $kode_matkul = isset($data_kls['kode_matkul']) ? $data_kls['kode_matkul'] : ''; 
                $nama_kelas = isset($data_kls['nama_kelas']) ? $data_kls['nama_kelas'] : ''; 
                $kode_jurusan = isset($data_kls['kode_jurusan']) ? $data_kls['kode_jurusan'] : '';
                
                $stmt_dsn = mysqli_prepare($con, "SELECT * FROM tbl_dosen WHERE nik = ?");
                mysqli_stmt_bind_param($stmt_dsn, "s", $nik);
                mysqli_stmt_execute($stmt_dsn);
                $data_dsn = mysqli_fetch_array(mysqli_stmt_get_result($stmt_dsn)); 
                
                $nama = isset($data_dsn['nama']) ? $data_dsn['nama'] : ''; 
                $kelamin = isset($data_dsn['kelamin']) ? $data_dsn['kelamin'] : ''; 
                $foto = isset($data_dsn['img']) ? $data_dsn['img'] : ''; 
                ?>
                <h3 class="card-title">
                  <i class="fas fa-chalkboard-teacher mr-2"></i>
                  Kelas Mata Kuliah <?= htmlspecialchars($kode_kls); ?>
                </h3>
              </div>

              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-md-3 text-center">
                    <?php 
                    $foto_default = ($kelamin == 'L') ? '../aset_web/img/dosen_lk.jpe' : '../aset_web/img/dosen_pr.jpe'; 
                    $foto_tampil = !empty($foto) ? $foto : $foto_default; 
                    ?>
                    <div class="border rounded p-2">
                      <img src="<?= htmlspecialchars($foto_tampil); ?>" alt="Dosen" class="img-fluid rounded">
                    </div>
                    <br>
                    
                    <?php if ($status_pertemuan == '1') { ?>
                      <div class="alert alert-warning text-center fw-bold py-2 mb-2">
                          Sisa Waktu Presensi: <span id="timer">--:--</span>
                      </div>
                      <a href="ubah_status.php?kode_pertemuan=<?= urlencode($kode_pertemuan) ?>&status=0" class="btn btn-danger w-100 mb-2" 
                          onclick="return confirm('APAKAH ANDA YAKIN INGIN MENUTUP PRESENSI INI?')">
                          <i class="fas fa-toggle-off mr-1"></i> Tutup Presensi
                      </a>

                      <script>
                          let sisaDetik = <?= $sisa_detik ?>;
                          const timerElement = document.getElementById('timer');

                          const countdown = setInterval(() => {
                              if (sisaDetik <= 0) {
                                  clearInterval(countdown);
                                  window.location.href = "ubah_status.php?kode_pertemuan=<?= urlencode($kode_pertemuan) ?>&status=0";
                              } else {
                                  let menit = Math.floor(sisaDetik / 60);
                                  let detik = sisaDetik % 60;
                                  
                                  menit = menit < 10 ? '0' + menit : menit;
                                  detik = detik < 10 ? '0' + detik : detik;
                                  
                                  timerElement.innerText = menit + ":" + detik;
                                  sisaDetik--;
                              }
                          }, 1000);
                      </script>
                    <?php } else { ?>
                      <a href="ubah_status.php?kode_pertemuan=<?= urlencode($kode_pertemuan) ?>&status=1" class="btn btn-success w-100 mb-2" 
                          onclick="return confirm('APAKAH ANDA YAKIN INGIN MEMBUKA PRESENSI INI?')">
                          <i class="fas fa-toggle-on mr-1"></i> Buka Presensi
                      </a>
                    <?php } ?>

                    <button type="button" class="btn btn-primary btn-block" id="btn-simpan"><i class="fas fa-save mr-1"></i>Simpan</button>
                    <a href="../admin_data_kelas_makul/pertemuan.php?kode_kelas=<?= urlencode($kode_kls) ?>" class="btn btn-warning btn-block">
                      <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group row mb-2">
                      <table width="70%" cellpadding="8">
                        <tbody>
                          <tr>
                            <td><b>NIK</b></td>
                            <td>:</td>
                            <td><?= htmlspecialchars($nik); ?></td>
                          </tr>
                          <tr>
                            <td><b>NAMA</b></td>
                            <td>:</td>
                            <td><?= htmlspecialchars($nama); ?></td>
                          </tr>
                          <tr>
                            <td><b>MATA KULIAH</b></td>
                            <td>:</td>
                            <td>
                              <?php 
                              $stmt_mt = mysqli_prepare($con, "SELECT nama_matkul FROM tbl_matkul WHERE kode_matkul = ?");
                              mysqli_stmt_bind_param($stmt_mt, "s", $kode_matkul);
                              mysqli_stmt_execute($stmt_mt);
                              $data_matkul = mysqli_fetch_array(mysqli_stmt_get_result($stmt_mt));
                              echo htmlspecialchars(isset($data_matkul['nama_matkul']) ? $data_matkul['nama_matkul'] : '');
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <td><b>KELAS</b></td>
                            <td>:</td>
                            <td><?= htmlspecialchars($nama_kelas); ?></td>
                          </tr>
                          <tr>
                            <td><b>JURUSAN</b></td>
                            <td>:</td>
                            <td>
                              <?php 
                              $stmt_jr = mysqli_prepare($con, "SELECT nama_jurusan FROM tbl_jurusan WHERE kode_jurusan = ?");
                              mysqli_stmt_bind_param($stmt_jr, "s", $kode_jurusan);
                              mysqli_stmt_execute($stmt_jr);
                              $data_jurusan = mysqli_fetch_array(mysqli_stmt_get_result($stmt_jr));
                              echo htmlspecialchars(isset($data_jurusan['nama_jurusan']) ? $data_jurusan['nama_jurusan'] : '');
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <td><b>HARI</b></td>
                            <td>:</td>
                            <td><?= htmlspecialchars($hari); ?></td>
                          </tr>
                          <tr>
                            <td><b>TANGGAL</b></td>
                            <td>:</td>
                            <td><?= htmlspecialchars($tgl); ?></td>
                          </tr>
                          <tr>
                            <td><b>PERTEMUAN KE - </b></td>
                            <td>:</td>
                            <td><?= htmlspecialchars($pertemuan); ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div class="col-md-3 text-center">
                    <div class="border rounded p-3">
                      <div id="qrcode" class="d-flex justify-content-center">
                        <?php
                        include_once('../aset_web/phpqrcode/qrlib.php');
                        
                        $isi_qr = $kode_pertemuan;
                        // Penamaan file yang statis berdasarkan kode_pertemuan agar tidak terus memenuhi folder
                        $fileName = 'QR-presensi-'.$kode_pertemuan.'.png';
                        $alamat_tujuan = 'qr/';
                        
                        if (!file_exists($alamat_tujuan)) {
                            mkdir($alamat_tujuan, 0777, true);
                        }
                        $alamat_qr = $alamat_tujuan . $fileName;
                        
                        QRcode::png($isi_qr, $alamat_qr);
                        ?>
                        <img src="<?= htmlspecialchars($alamat_qr); ?>" alt="qr_presensi" class="img-fluid mb-2" style="width: 250px;">
                      </div>
                    </div>
                    <p class="text-muted mt-2 mb-0">
                      <i class="fas fa-qrcode mr-1"></i> Scan QR untuk presensi
                    </p>
                  </div>
                </div>

                <hr>

                <div class="row mt-4">
                  <div class="col-lg-12">
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th width="5%">No</th>
                          <th>Mahasiswa</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                      $stmt_prs = mysqli_prepare($con, "SELECT * FROM tbl_presensi WHERE kode_pertemuan = ?");
                      mysqli_stmt_bind_param($stmt_prs, "s", $kode_pertemuan);
                      mysqli_stmt_execute($stmt_prs);
                      $panggil_data_presensi = mysqli_stmt_get_result($stmt_prs);

                      $no = 1;
                      if (mysqli_num_rows($panggil_data_presensi) > 0) {
                        while ($data = mysqli_fetch_array($panggil_data_presensi)) {
                          $id_presensi = $data['id_presensi'];
                          $nim = $data['nim'];
                          $status = $data['status_kehadiran'];
                          ?>
                          <tr>
                            <td><?= $no++; ?></td>
                            <td>
                              <?php 
                              $stmt_mhs = mysqli_prepare($con, "SELECT nama FROM tbl_mahasiswa WHERE nim = ?");
                              mysqli_stmt_bind_param($stmt_mhs, "s", $nim);
                              mysqli_stmt_execute($stmt_mhs);
                              $data_mahasiswa = mysqli_fetch_array(mysqli_stmt_get_result($stmt_mhs));
                              $nama_mhs = isset($data_mahasiswa['nama']) ? $data_mahasiswa['nama'] : 'Nama tidak ditemukan';
                              echo htmlspecialchars($nim . ' - ' . $nama_mhs);
                              ?>
                            </td>
                            <td>
                              <?php 
                              if (strcasecmp($status, 'Hadir') == 0) {
                                  echo '<span class="badge badge-success">Hadir</span>'; 
                              } elseif (strcasecmp($status, 'Alfa') == 0) {
                                  echo '<span class="badge badge-danger">Alfa</span>'; 
                              } elseif (strcasecmp($status, 'Sakit') == 0) {
                                  echo '<span class="badge badge-info">Sakit</span>'; 
                              } elseif (strcasecmp($status, 'Izin') == 0) {
                                  echo '<span class="badge badge-warning">Izin</span>'; 
                              } else {
                                  echo '<span class="badge badge-secondary">' . htmlspecialchars($status) . '</span>';
                              }
                              ?>
                            </td>
                            <td>
                              <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit" data-id="<?= htmlspecialchars($id_presensi) ?>" data-status="<?= htmlspecialchars($status) ?>">
                                <i class="fas fa-edit"></i>
                              </button>
                              <a href="hapus.php?id=<?= urlencode($id_presensi) ?>&kode_kelas=<?= urlencode($kode_kls) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus mahasiswa ini?')"> 
                                <i class="fas fa-trash"></i>
                              </a>
                            </td>
                          </tr>
                          <?php
                        }
                      } else {
                        echo '<tr><td colspan="4" class="text-center">Data Tidak Ditemukan</td></tr>';
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
    </div>
  </div>

  <!-- MODAL EDIT STATUS KEHADIRAN -->
  <div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Status Kehadiran</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="ubah_kehadiran.php" method="post">
          <div class="modal-body">
            <input type="hidden" name="kode_pertemuan" value="<?= htmlspecialchars($kode_pertemuan) ?>">
            <input type="hidden" name="id_presensi" id="id_presensi">

            <div class="form-group mb-3">
              <label for="status_kehadiran" class="d-block text-left w-100">Status Kehadiran</label>
              <select name="status_kehadiran" id="status_kehadiran" class="form-control w-100" required>
                <option value="">-- Pilih Status Kehadiran --</option>
                <option value="Hadir">Hadir</option>
                <option value="Alfa">Alfa</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            <button type="submit" name="btn_ubah_kehadiran" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include '../footer.php'; ?>
</div>

<?php include '../script.php'; ?>

<script>
  $('#modal-edit').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var status = button.data('status');
    
    var modal = $(this);
    modal.find('#id_presensi').val(id);
    modal.find('#status_kehadiran').val(status);
  });
</script>

</body>
</html>
<?php 
?>