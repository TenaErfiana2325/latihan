<?php
session_start();
require_once '../database/koneksi.php';

if (!isset($con) && isset($koneksi)) {
    $con = $koneksi;
}

if (@$_SESSION['peran'] != 'M') {
    header("Location: ../logout.php");
    exit;
}

$kode_kelas_param = $_GET['kode_kelas'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Scan Presensi QR</title>
  <?php 
  include '../css.php';
  $hal = 'mahasiswa_kelas';
  ?>
  <!-- Library Kamera HTML5 QR Code -->
  <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info"><a href="#" class="d-block">Sistem Manajemen</a></div>
      </div>
      <?php include '../sidebar_mahasiswa.php'; ?>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <h1 class="m-0">Scan QR Presensi</h1>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-camera mr-2"></i>Arahkan Kamera ke QR Code Dosen</h3>
          </div>
          <div class="card-body text-center">

            <!-- Container Kamera -->
            <div id="reader" style="width: 100%; max-width: 450px; margin: 0 auto;" class="border rounded p-2"></div>
            
            <!-- Alert Notifikasi -->
            <div id="result" class="mt-3"></div>

            <a href="index.php" class="btn btn-secondary mt-3">
              <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Kelas
            </a>

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

    // Hentikan scan otomatis jika sudah terbaca
    html5QrcodeScanner.clear();

    document.getElementById('result').innerHTML = `
        <div class="alert alert-info">Sedang memproses presensi untuk Kode Kelas: <b>${decodedText}</b>...</div>
    `;

    // Kirim hasil scan ke file proses_presensi.php lewat AJAX Fetch
    let formData = new FormData();
    formData.append('kode_kelas', decodedText);

  fetch('proses_presensi.php', {
    method: 'POST',
    body: formData
})
.then(async response => {
    // Menangkap jika ada error HTTP / Syntax PHP dari server
    if (!response.ok) {
        const text = await response.text();
        throw new Error(text || 'Server Error');
    }
    return response.json();
})
.then(data => {
    if(data.status === 'success') {
        document.getElementById('result').innerHTML = `<div class="alert alert-success"><i class="fas fa-check-circle mr-1"></i> ${data.message}</div>`;
    } else {
        document.getElementById('result').innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-1"></i> ${data.message}</div>`;
    }
})
.catch(error => {
    console.error('Detail Error:', error);
    document.getElementById('result').innerHTML = `<div class="alert alert-danger"><b>Error Server/PHP:</b> ${error.message}</div>`;
});
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", 
    { fps: 10, qrbox: { width: 250, height: 250 } },
    /* verbose= */ false
);

<script>
html5QrcodeScanner.render(onScanSuccess); {
window.location.href='proses_presensi.php?kode_pertemuan='+decodedText;
}
</script>
</script>
</body>
</html>