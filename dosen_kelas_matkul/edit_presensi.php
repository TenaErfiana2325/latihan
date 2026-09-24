<?php
session_start();
require_once '../database/koneksi.php';

// Cek otoritas peran Dosen
$authority = @$_SESSION['peran'];
if ($authority != 'D') {
  echo '<script>alert("Akun ini melakukan cross authority, akan segera di logout");</script>';
  echo '<script>window.location.href="../logout.php"</script>';
  exit();
}

// Tangkap parameter URL
$kode_kelas = mysqli_real_escape_string($con, $_GET['kode_kelas'] ?? '');
$nim        = mysqli_real_escape_string($con, $_GET['nim'] ?? '');

if (empty($kode_kelas) || empty($nim)) {
  echo '<script>alert("Parameter tidak lengkap!"); window.location.href="index.php";</script>';
  exit();
}

// Proses Update Presensi
if (isset($_POST['btn_simpan_presensi'])) {
  $id_presensi      = mysqli_real_escape_string($con, $_POST['id_presensi']);
  $status_kehadiran = mysqli_real_escape_string($con, $_POST['status_kehadiran']);
  $kode_pertemuan   = mysqli_real_escape_string($con, $_POST['kode_pertemuan']);

  if (!empty($id_presensi)) {
    // Update data jika record presensi sudah ada
    $update = mysqli_query($con, "UPDATE tbl_presensi SET status_kehadiran = '$status_kehadiran' WHERE id_presensi = '$id_presensi'") or die(mysqli_error($con));
  } else {
    // Insert data baru jika mahasiswa belum memiliki record presensi di pertemuan ini
    $update = mysqli_query($con, "INSERT INTO tbl_presensi (kode_pertemuan, nim, status_kehadiran) VALUES ('$kode_pertemuan', '$nim', '$status_kehadiran')") or die(mysqli_error($con));
  }

  if ($update) {
    echo "<script>alert('Status Kehadiran Berhasil Diperbarui!'); window.location='detail_kelas.php?kode_kelas=$kode_kelas';</script>";
  } else {
    echo "<script>alert('Gagal Memperbarui Status!');</script>";
  }
}

// Ambil data mahasiswa
$query_mhs = mysqli_query($con, "SELECT nim, nama FROM tbl_mahasiswa WHERE nim = '$nim'") or die(mysqli_error($con));
$mhs = mysqli_fetch_array($query_mhs);

// Ambil pertemuan terbaru dari kelas ini
$query_pertemuan = mysqli_query($con, "SELECT kode_pertemuan, pertemuan_ke, judul_pertemuan FROM tbl_pertemuan WHERE kode_kelas = '$kode_kelas' ORDER BY kode_pertemuan DESC LIMIT 1") or die(mysqli_error($con));
$pt = mysqli_fetch_array($query_pertemuan);

$kode_pertemuan = $pt['kode_pertemuan'] ?? '';

// Ambil status presensi terkini mahasiswa
$query_presensi = mysqli_query($con, "SELECT id_presensi, status_kehadiran FROM tbl_presensi WHERE kode_pertemuan = '$kode_pertemuan' AND nim = '$nim'") or die(mysqli_error($con));
$presensi = mysqli_fetch_array($query_presensi);

$id_presensi = $presensi['id_presensi'] ?? '';
$status_saat_ini = strtolower(trim($presensi['status_kehadiran'] ?? ''));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Presensi - <?= $mhs['nama'] ?? ''; ?></title>
  <?php include '../css.php'; ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <div class="content-wrapper ml-0">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Presensi Mahasiswa</h1>
          </div>
          <div class="col-sm-6 text-right">
            <a href="detail_kelas.php?kode_kelas=<?= $kode_kelas; ?>" class="btn btn-secondary btn-sm">
              <i class="fas fa-arrow-left"></i> Batal / Kembali
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title text-dark font-weight-bold"><i class="fas fa-edit mr-1"></i> Form Ubah Presensi</h3>
          </div>
          
          <form method="POST" action="">
            <div class="card-body">
              <input type="hidden" name="id_presensi" value="<?= $id_presensi; ?>">
              <input type="hidden" name="kode_pertemuan" value="<?= $kode_pertemuan; ?>">

              <div class="form-group">
                <label>NIM & Nama Mahasiswa</label>
                <input type="text" class="form-control" value="<?= $mhs['nim']; ?> - <?= strtoupper($mhs['nama']); ?>" readonly>
              </div>

              <div class="form-group">
                <label>Pertemuan</label>
                <input type="text" class="form-control" value="Pertemuan ke-<?= $pt['pertemuan_ke'] ?? '1'; ?> (<?= $pt['judul_pertemuan'] ?? '-'; ?>)" readonly>
              </div>

              <div class="form-group">
                <label for="status_kehadiran">Status Kehadiran</label>
                <select name="status_kehadiran" id="status_kehadiran" class="form-control" required>
                  <option value="Hadir" <?= ($status_saat_ini == 'hadir') ? 'selected' : ''; ?>>Hadir</option>
                  <option value="Izin" <?= ($status_saat_ini == 'izin') ? 'selected' : ''; ?>>Izin</option>
                  <option value="Sakit" <?= ($status_saat_ini == 'sakit') ? 'selected' : ''; ?>>Sakit</option>
                  <option value="Alfa" <?= ($status_saat_ini == 'alfa') ? 'selected' : ''; ?>>Alfa</option>
                </select>
              </div>
            </div>

            <div class="card-footer text-right">
              <button type="submit" name="btn_simpan_presensi" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include '../footer.php'; ?>
</div>

<?php include '../script.php'; ?>
</body>
</html>