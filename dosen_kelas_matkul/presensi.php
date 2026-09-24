<?php
session_start(); 
require_once '../database/koneksi.php';

if (!isset($con) && isset($koneksi)) {
    $con = $koneksi;
}

// Cek hak akses DOSEN
$authority = @$_SESSION['peran'];
if ($authority != 'D') {
  echo '<script>alert("Akun ini melakukan cross authority, akan segera di logout");</script>';
  echo '<script>window.location.href="../logout.php"</script>';
  exit;
}

$kode_pertemuan = @$_GET['kode_pertemuan']; 
$kode_kelas_param = @$_GET['kode_kelas'];

if (!empty($kode_pertemuan)) {
  $ambil_pertemuan = mysqli_query($con, "SELECT * FROM tbl_pertemuan WHERE kode_pertemuan = '$kode_pertemuan'") or die (mysqli_error($con));
} else {
  $ambil_pertemuan = mysqli_query($con, "SELECT * FROM tbl_pertemuan WHERE kode_kelas = '$kode_kelas_param' ORDER BY kode_pertemuan DESC LIMIT 1") or die (mysqli_error($con));
}

$data_pertemuan = mysqli_fetch_array($ambil_pertemuan);
$status_pertemuan = isset($data_pertemuan['status']) && $data_pertemuan['status'] !== '' ? $data_pertemuan['status'] : '0';
$waktu_dibuka = isset($data_pertemuan['waktu_dibuka']) && !empty($data_pertemuan['waktu_dibuka']) ? strtotime($data_pertemuan['waktu_dibuka']) : 0;
$durasi_detik = 30; // Timer 30 detik
$waktu_tutup = $waktu_dibuka + $durasi_detik;
$sisa_detik = $waktu_tutup - time();

// Auto close saat waktu habis
if ($status_pertemuan == '1' && $sisa_detik <= 0 && !empty($kode_pertemuan)) {
    header("Location: ubah_status.php?kode_pertemuan=" . $kode_pertemuan . "&status=0");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Presensi QR Dosen</title>
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
         <?= $_SESSION['nama'] ?? ''; ?> - [<?= $_SESSION['peran'] ?? ''; ?>] <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="../logout.php" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i> logout
          </a>
        </div>
      </li>
    </ul>
  </nav>

  <!-- Sidebar Dosen -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">Sistem Manajemen</a>
        </div>
      </div>
      <?php include '../sidebar_dosen.php'; ?>
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
                $kode_kelas = $data_pertemuan['kode_kelas'] ?? $kode_kelas_param;
                $kode_kls = $kode_kelas; 
                $tgl = $data_pertemuan['tgl'] ?? date('Y-m-d'); 
                $hari = date('l', strtotime($tgl)); 
                $pertemuan = $data_pertemuan['pertemuan_ke'] ?? '-';
                
                $ambil_kls = mysqli_query($con, "SELECT * FROM tbl_kelasmatkul WHERE kode_kelas ='$kode_kelas'") or die(mysqli_error($con)); 
                $data_kls = mysqli_fetch_array($ambil_kls); 
                
                $nik = $data_kls['nik'] ?? $_SESSION['nik'] ?? ''; 
                $kode_matkul = $data_kls['kode_matkul'] ?? ''; 
                $nama_kelas = $data_kls['nama_kelas'] ?? ''; 
                $kode_jurusan = $data_kls['kode_jurusan'] ?? '';
                
                $ambil_dsn = mysqli_query($con,"SELECT * FROM tbl_dosen WHERE nik ='$nik'") or die(mysqli_error($con)); 
                $data_dsn = mysqli_fetch_array($ambil_dsn); 
                $nama = $data_dsn['nama'] ?? $_SESSION['nama'] ?? ''; 
                $kelamin = $data_dsn['kelamin'] ?? ''; 
                $foto = $data_dsn['img'] ?? ''; 
                ?>
                <h3 class="card-title">
                  <i class="fas fa-chalkboard-teacher mr-2"></i>
                  Kelas Mata Kuliah <?= $kode_kls; ?>
                </h3>
              </div>

              <!-- CARD BODY (Foto Dosen, Data Kelas, & QR Code) -->
              <div class="card-body">
                <div class="row align-items-center">
                  
                  <!-- Kolom Foto & Timer -->
                  <div class="col-md-3 text-center">
                    <?php 
                    $foto_default = ($kelamin == 'L') ? '../aset_web/img/dosen_lk.jpe' : '../aset_web/img/dosen_pr.jpe'; 
                    $foto_tampil = !empty($foto) ? $foto : $foto_default; 
                    ?>
                    <div class="border rounded p-2">
                      <img src="<?= $foto_tampil; ?>" alt="Dosen" class="img-fluid rounded">
                    </div>
                    <br>
                    <?php
                    if ($status_pertemuan == '1') {
                        date_default_timezone_set('Asia/Jakarta');
                        $waktu_dibuka = !empty($data_pertemuan['waktu_dibuka']) ? strtotime($data_pertemuan['waktu_dibuka']) : time();
                        $sisa_detik = ($waktu_dibuka + 30) - time();
                        if ($sisa_detik < 0) $sisa_detik = 0;
                        ?>
                        <div class="alert alert-warning text-center fw-bold py-2 mb-2">
                            Sisa Waktu Presensi: <span id="timer">--:--</span>
                        </div>
                        <a href="ubah_status.php?kode_pertemuan=<?= $kode_pertemuan ?>&status=0" class="btn btn-danger w-100 mb-2" 
                          onclick="return confirm('APAKAH ANDA YAKIN INGIN MENUTUP PRESENSI INI?')">
                          <i class="fas fa-toggle-off mr-1"></i> Tutup Presensi
                        </a>

                        <script>
                            let sisaDetik = <?= $sisa_detik ?>;
                            const timerElement = document.getElementById('timer');
                            const countdown = setInterval(() => {
                                if (sisaDetik <= 0) {
                                    clearInterval(countdown);
                                    window.location.href = "ubah_status.php?kode_pertemuan=<?= $kode_pertemuan ?>&status=0";
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
                        <?php
                    } else {
                        ?>
                        <a href="ubah_status.php?kode_pertemuan=<?= $kode_pertemuan ?>&status=1" class="btn btn-success w-100 mb-2" 
                          onclick="return confirm('APAKAH ANDA YAKIN INGIN MEMBUKA PRESENSI INI?')">
                          <i class="fas fa-toggle-on mr-1"></i> Buka Presensi
                        </a>
                        <?php
                    }
                    ?>
                    <a href="index.php" class="btn btn-warning btn-block">
                      <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                  </div>

                  <!-- Kolom Detail Info -->
                  <div class="col-md-6">
                    <table width="100%" cellpadding="8">
                      <tbody>
                          <tr>
                              <td width="30%"><b>NIK</b></td>
                              <td width="5%">:</td>
                              <td><?= $nik; ?></td>
                          </tr>
                          <tr>
                              <td><b>NAMA DOSEN</b></td>
                              <td>:</td>
                              <td><?= $nama; ?></td>
                          </tr>
                          <tr>
                              <td><b>MATA KULIAH</b></td>
                              <td>:</td>
                              <td><?php 
                              $query_matkul = mysqli_query($con,"SELECT nama_matkul FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'") or die(mysqli_error($con));
                              $data_matkul = mysqli_fetch_array($query_matkul);
                              echo $data_matkul['nama_matkul'] ?? '-';
                              ?></td>
                          </tr>
                          <tr>
                              <td><b>KELAS</b></td>
                              <td>:</td>
                              <td><?= $nama_kelas; ?></td>
                          </tr>
                          <tr>
                              <td><b>JURUSAN</b></td>
                              <td>:</td>
                              <td><?php 
                              $query_jurusan = mysqli_query($con,"SELECT nama_jurusan FROM tbl_jurusan WHERE kode_jurusan = '$kode_jurusan'") or die(mysqli_error($con));
                              $data_jurusan = mysqli_fetch_array($query_jurusan);
                              echo $data_jurusan['nama_jurusan'] ?? '-';
                              ?></td>
                          </tr>
                          <tr>
                              <td><b>HARI / TANGGAL</b></td>
                              <td>:</td>
                              <td><?= $hari . ', ' . $tgl; ?></td>
                          </tr>
                          <tr>
                              <td><b>PERTEMUAN KE</b></td>
                              <td>:</td>
                              <td><?= $pertemuan; ?></td>
                          </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- Kolom QR Code -->
                  <div class="col-md-3 text-center">
                    <div class="border rounded p-3">
                      <div id="qrcode" class="d-flex justify-content-center">
                        <?php
                        include('../aset_web/phpqrcode/qrlib.php');
                        $isi_qr = $kode_kls;
                        $fileName = 'QR-presensi-'.$kode_kls.'-'.round(microtime(true)).'.png';
                        $alamat_tujuan = 'qr/';
                        if (!file_exists($alamat_tujuan)) {
                            mkdir($alamat_tujuan, 0777, true);
                        }
                        $alamat_qr = $alamat_tujuan.$fileName;
                        QRcode::png($isi_qr, $alamat_qr);
                        ?>
                        <img src="<?= $alamat_qr ?>" alt="qr_presensi" class="img-fluid mb-2" style="width: 250px;">
                      </div>
                    </div>
                    <p class="text-muted mt-2 mb-0">
                      <i class="fas fa-qrcode mr-1"></i> Scan QR untuk presensi
                    </p>
                  </div>

                </div>
              </div>
              <hr>

              <!-- TABEL PRESENSI MAHASISWA -->
              <div class="row p-3">
                <div class="col-lg-12">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th width="5%">No</th>
                        <th>Mahasiswa</th>
                        <th>Status</th>
                        <th width="15%">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    $panggil_data_presensi = mysqli_query($con, "SELECT * FROM tbl_presensi WHERE kode_pertemuan='$kode_pertemuan'") or die(mysqli_error($con));
                    $no = 1;

                    if (mysqli_num_rows($panggil_data_presensi) > 0) {
                      while ($data = mysqli_fetch_array($panggil_data_presensi)) {
                        $id_presensi = $data['id_presensi'];
                        $nim = $data['nim'];
                        $status = $data['status_kehadiran'];
                        ?>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td><?php 
                          $query_mahasiswa = mysqli_query($con,"SELECT nim, nama FROM tbl_mahasiswa WHERE nim = '$nim'") or die(mysqli_error($con));
                          $data_mahasiswa = mysqli_fetch_array($query_mahasiswa);
                          echo $nim . ' - ' . ($data_mahasiswa['nama'] ?? 'Nama tidak ditemukan');
                          ?></td>
                          <td>
                          <?php 
                          $status_lc = strtolower($status);
                          if ($status_lc == 'hadir') {
                              echo '<span class="badge badge-success">Hadir</span>'; 
                          } elseif ($status_lc == 'alfa') {
                              echo '<span class="badge badge-danger">Alfa</span>'; 
                          } elseif ($status_lc == 'sakit') {
                              echo '<span class="badge badge-info">Sakit</span>'; 
                          } elseif ($status_lc == 'izin') {
                              echo '<span class="badge badge-warning">Izin</span>'; 
                          } else {
                              echo '<span class="badge badge-secondary">' . $status . '</span>';
                          }
                          ?>
                          </td>
                          <td>
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit" data-id="<?= $id_presensi ?>" data-status="<?= $status ?>">
                              <i class="fas fa-edit"></i>
                            </button>
                            <a href='hapus.php?id=<?= $id_presensi ?>&kode_kelas=<?= $kode_kls ?>' class='btn btn-danger btn-sm' onclick="return confirm('Yakin ingin hapus mahasiswa ini?')"> 
                              <i class="fas fa-trash"></i>
                            </a>
                          </td>
                        </tr>
                        <?php
                      }
                    } else {
                      echo '<tr><td colspan="4" class="text-center">Belum Ada Mahasiswa Presensi</td></tr>';
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
            <input type="hidden" name="kode_pertemuan" value="<?= $kode_pertemuan ?>">
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