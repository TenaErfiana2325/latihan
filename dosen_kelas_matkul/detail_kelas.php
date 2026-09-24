<?php
session_start();
require_once '../database/koneksi.php';

// Cek otoritas peran Dosen (misal: 'D')
$authority = @$_SESSION['peran'];
if ($authority != 'D') {
  echo '<script>alert("Akun ini melakukan cross authority, akan segera di logout");</script>';
  echo '<script>window.location.href="../logout.php"</script>';
  exit();
} 

// Tangkap parameter kode_kelas dari URL
if (!isset($_GET['kode_kelas']) || empty($_GET['kode_kelas'])) {
  echo '<script>alert("Kode Kelas tidak ditemukan!"); window.location.href="index.php";</script>';
  exit();
}

$kode_kelas = mysqli_real_escape_string($con, $_GET['kode_kelas']);

// Ambil info lengkap kelas berdasarkan kode_kelas
$query_detail_kelas = mysqli_query($con, "
    SELECT k.*, a.tahun, a.semester, m.nama_matkul, j.nama_jurusan, d.nama AS nama_dosen 
    FROM tbl_kelasmatkul k
    LEFT JOIN tbl_akademik a ON k.kode_akd = a.kode_akd
    LEFT JOIN tbl_matkul m ON k.kode_matkul = m.kode_matkul
    LEFT JOIN tbl_jurusan j ON k.kode_jurusan = j.kode_jurusan
    LEFT JOIN tbl_dosen d ON k.nik = d.nik
    WHERE k.kode_kelas = '$kode_kelas'
") or die(mysqli_error($con));

$data_kelas = mysqli_fetch_array($query_detail_kelas);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Kelas - <?= $data_kelas['nama_kelas'] ?? '' ?></title>
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
      <?php include '../sidebar_dosen.php'; ?>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 align-items-center">
          <div class="col-sm-6">
            <h1 class="m-0">Detail Kelas Mata Kuliah</h1>
          </div>
          <div class="col-sm-6 text-right">
            <a href="pdf_pertemuan.php?kode_kelas=<?= $kode_kelas; ?>" target="_blank" class="btn btn-danger">
              <i class="fas fa-file-pdf"></i> Ekspor PDF
            </a>
            <a href="index.php" class="btn btn-secondary ml-1">
              <i class="fas fa-arrow-left"></i> Kembali
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        
        <!-- Information Card -->
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Informasi Kelas</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr>
                    <th width="35%">Nama Kelas</th>
                    <td>: <?= $data_kelas['nama_kelas']; ?></td>
                  </tr>
                  <tr>
                    <th>Mata Kuliah</th>
                    <td>: <?= $data_kelas['nama_matkul']; ?></td>
                  </tr>
                  <tr>
                    <th>Jurusan</th>
                    <td>: <?= $data_kelas['nama_jurusan']; ?></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr>
                    <th width="35%">Periode</th>
                    <td>: <?= $data_kelas['tahun']; ?> - <?= ($data_kelas['semester'] == 'GN' ? 'Genap' : 'Ganjil'); ?></td>
                  </tr>
                  <tr>
                    <th>Dosen Pengampu</th>
                    <td>: <?= $data_kelas['nama_dosen']; ?></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Student List Card -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Daftar Mahasiswa & Kehadiran</h3>
          </div>
          <div class="card-body p-0">
            <table class="table table-bordered table-striped m-0">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No</th>
                  <th width="50%">Mahasiswa (NIM - Nama)</th>
                  <th width="25%" class="text-center">Status Kehadiran</th>
                  <th width="20%" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                // Query mengambil mahasiswa beserta status presensi TERBARU berdasarkan pertemuan terkini
                $query_mhs = mysqli_query($con, "
                    SELECT 
                      m.nim, 
                      m.nama,
                      (
                        SELECT pr.status_kehadiran 
                        FROM tbl_presensi pr 
                        JOIN tbl_pertemuan pt ON pr.kode_pertemuan = pt.kode_pertemuan 
                        WHERE pr.nim = m.nim AND pt.kode_kelas = '$kode_kelas' 
                        ORDER BY pt.kode_pertemuan DESC, pr.id_presensi DESC 
                        LIMIT 1
                      ) AS status_kehadiran
                    FROM tbl_peserta p
                    JOIN tbl_mahasiswa m ON p.nim = m.nim
                    WHERE p.kode_kelas = '$kode_kelas'
                    ORDER BY m.nim ASC
                ") or die(mysqli_error($con));

                $no = 1;
                if (mysqli_num_rows($query_mhs) > 0) {
                  while ($mhs = mysqli_fetch_array($query_mhs)) {
                    
                    // Normalisasi teks status kehadiran
                    $status_raw = strtolower(trim($mhs['status_kehadiran'] ?? ''));

                    if ($status_raw === 'hadir') {
                      $badge_color = 'badge-success';
                      $status_text  = 'Hadir';
                    } elseif ($status_raw === 'izin') {
                      $badge_color = 'badge-info';
                      $status_text  = 'Izin';
                    } elseif ($status_raw === 'sakit') {
                      $badge_color = 'badge-warning';
                      $status_text  = 'Sakit';
                    } elseif ($status_raw === 'alfa') {
                      $badge_color = 'badge-danger';
                      $status_text  = 'Alfa';
                    } else {
                      $badge_color = 'badge-secondary';
                      $status_text  = 'Belum Presensi';
                    }
                    ?>
                    <tr>
                      <td class="text-center"><?= $no++; ?></td>
                      <td><?= $mhs['nim']; ?> - <?= strtoupper($mhs['nama']); ?></td>
                      <td class="text-center">
                        <span class="badge <?= $badge_color; ?> px-3 py-1"><?= $status_text; ?></span>
                      </td>
                      <td class="text-center">
                        <a href="edit_presensi.php?kode_kelas=<?= $kode_kelas; ?>&nim=<?= $mhs['nim']; ?>" class="btn btn-warning btn-sm text-dark font-weight-bold">
                          <i class="fas fa-edit"></i> Edit
                        </a>
                      </td>
                    </tr>
                    <?php
                  }
                } else {
                  echo '<tr><td colspan="4" class="text-center p-3">Belum ada mahasiswa yang terdaftar di kelas ini</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>

  <?php include '../footer.php' ?>
</div>

<?php include '../script.php' ?>
</body>
</html>