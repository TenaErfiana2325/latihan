<?php
session_start();
require_once '../database/koneksi.php';

if (!isset($con) && isset($koneksi)) {
    $con = $koneksi;
}

// Cek hak akses MAHASISWA ('M')
if (@$_SESSION['peran'] != 'M') {
    echo '<script>alert("Akses ditolak, khusus Mahasiswa."); window.location.href="../logout.php";</script>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scan QR Presensi</title>
  <?php 
  include '../css.php';
  $hal = 'mahasiswa_kelas';
  ?>
  <!-- Library Kamera HTML5 QR Code -->
  <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
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
         <?= $_SESSION['nama'] ?? ''; ?> - [Mahasiswa] <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="../logout.php" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">Sistem Manajemen</a>
        </div>
      </div>
      <?php include '../sidebar_mahasiswa.php'; ?>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <h1 class="m-0">Scan QR Presensi</h1>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-camera mr-2"></i>Arahkan Kamera ke QR Code Dosen</h3>
          </div>
          <div class="card-body text-center">

            <!-- Container Kamera Scanner -->
            <div id="reader" style="width: 100%; max-width: 450px; margin: 0 auto;" class="border rounded p-2"></div>
            
            <!-- Alert Notifikasi Hasil Scan -->
            <div id="result" class="mt-3"></div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include '../footer.php'; ?>
</div>

<?php include '../script.php'; ?>

<script>
function onScanSuccess(decodedText, decodedResult) {
  console.log(`Scan result: ${decodedText}`, decodedResult);
  // Hentikan scanner setelah berhasil membaca QR agar tidak me-redirect berulang kali
  html5QrcodeScanner.clear();
  // Redirect ke proses_presensi.php membawa hasil scan kode_pertemuan
  window.location.href = 'proses_presensi.php?kode_pertemuan=' + encodeURIComponent(decodedText);
}

function onScanError(errorMessage) {
  // Abaikan error pembacaan frame biasa saat mencari QR
}

const html5QrcodeScanner = new Html5QrcodeScanner(
  "reader", { fps: 10, qrbox: 250 });
html5QrcodeScanner.render(onScanSuccess, onScanError);
</script>
</body>
</html>