<?php
 require_once  '../database/koneksi.php';
$authority = @$_SESSION['peran'];
if ($authority != 'A') {
  echo '<script>alert("akun ini melakukan cross authority, akan segera di logout");</script>';
  echo '<script>window.location.href= "../logout.php" </script>';
}else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
  include '../css.php';
  $hal = 'beranda_matkul';
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
          <i class="far fa-user"></i>
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

    <div class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Data Mata Kuliah</h3>
          </div>
          <div class="card-body">
            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah"> 
              <i class="fas fa-plus"></i><b> Tambah Data</b>
            </button>
            <a href ="halaman_tambah.php" type="button" class="btn btn-success mb-2" >tambah data 2</a>
             <a href="reset.php" type="button" class="btn btn-danger mb-2" onclick="return confirm ('anda yakin ingin mereset data ini')">
            <i class="fa fa-exclamation-triangle">reset data</i></a>

            <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#modal-impor"> 
              <i class="fas fa-file-excel"></i><b> Impor Data</b></button>

             <a href="pdf.php" type="button" target="_blank" class="btn btn-danger mb-2">
            <i class="fa fa-file-pdf">Exspor PDF</i></a>

            <a href="excel.php" type="button" target="_blank" class="btn btn-danger mb-2">
            <i class="fa fa-file-pdf">Exspor Excel</i></a>

            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th width="5%">No</th>
                <th>Kode matkul</th>
                <th>Nama Mata Kuliah</th>
                <th>Jumlah SKS</th>
                <th>Jumlah CPMK</th>
                <th width="15%">Aksi</th>
              </tr>
              </thead>
              <tbody>
              <?php 
              $panggil_data_matkul = mysqli_query($con, "SELECT * FROM tbl_matkul") or die(mysqli_error($con));
              $no = 1;
              $rv = mysqli_num_rows($panggil_data_matkul);
              if ($rv > 0) {
                while ($data = mysqli_fetch_array($panggil_data_matkul)) {
                  $kode_matkul = $data['kode_matkul'] ?? '';
                  $nama_matkul = $data['nama_matkul'] ?? '';
                  $jml_sks = $data['jml_sks'] ?? '';
                  $jml_cpmk = $data['jml_cpmk'] ?? '';
                  ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $kode_matkul ?></td>
                    <td><?= $nama_matkul ?></td>
                    <td><?= $jml_sks ?></td>
                    <td><?= $jml_cpmk ?></td>
                    <td>
                      <a href='edit.php?kode_matkul=<?=$data['kode_matkul'];?>' class='btn btn-warning btn-sm'><i class="fas fa-pen"></i> Edit</a>  

                      <button type="button" class="btn- btn-success btn-sm" data-toggle="modal" data-target="#modal-edit" data-kode="<?=$data['kode_matkul'];?>" data-nama="<?= $data ['nama_matkul'];?>" data-sks="<?= $data['jml_sks'];?>" data-cpmk="<?= $data['jml_cpmk'];?>" ><i class="fas fa-edit"></i> Edit </button>

                      <a href='hapus.php?kode_matkul=<?= $data['kode_matkul'];?>' class='btn btn-danger btn-sm' onclick="return confirm('Yakin ingin hapus ini?')"><i class="fas fa-trash"></i> Hapus</a>
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

  <!-- Modal Tambah Data Mata Kuliah -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Mata Kuliah</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="tambah.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="kode_matkul">Kode Mata Kuliah</label>
              <input type="text" maxlength="10" name="kode_matkul" class="form-control" id="kode_matkul" placeholder="Masukan Kode matkul" required> 
            </div>
            <div class="form-group">
              <label for="nama_matkul">Nama Mata Kuliah</label>
              <input type="text" maxlength="50" name="nama_matkul" class="form-control" id="nama_matkul" placeholder="Masukan Nama Mata Kuliah" required>
            </div>
            <div class="form-group">
              <label for="jml_sks">Jumlah SKS</label>
              <input type="number" name="jml_sks" class="form-control" id="jml_sks" placeholder="Masukan Jumlah SKS" required>
            </div>
            <div class="form-group">
              <label for="jml_cpmk">Jumlah CPMK</label>
              <input type="number" name="jml_cpmk" class="form-control" id="jml_cpmk" placeholder="Masukan Jumlah CPMK" required>
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
  <div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Data Mata Kuliah</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="ubah.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="kode_matkul">Edit Mata Kuliah</label>
              <input type="text" maxlength="10" name="kode_matkul" class="form-control" id="kode_matkul" placeholder="Masukan Kode matkul" required readonly>
            </div>
            <div class="form-group">
              <label for="nama_matkul">Nama Mata Kuliah</label>
              <input type="text" maxlength="50" name="nama_matkul" class="form-control" id="nama_matkul" placeholder="Masukan Nama Mata Kuliah" required>
            </div>
            <div class="form-group">
              <label for="jml_sks">Jumlah SKS</label>
              <input type="number" name="jml_sks" class="form-control" id="jml_sks" placeholder="Masukan Jumlah SKS" required>
            </div>
            <div class="form-group">
              <label for="jml_cpmk">Jumlah CPMK</label>
              <input type="number" name="jml_cpmk" class="form-control" id="jml_cpmk" placeholder="Masukan Jumlah CPMK" required>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="btn_edit" class="btn btn-primary">Edit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
   <div class="modal fade" id="modal-impor">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Impor Data Mata Kuliah</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="impor.php" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">
              <label for="file">Upload File Template</label>
              <input type="file" class="form-control" name="file_excel" required>
        </div>
        </div>
        <form action="impor.php" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group mb-3">
          <label for="file">Download Template Excel</label><br>
          <a href="template/template_matkul.xls" class="btn btn-success btn-sm" download>
          <i class="fas fa-download"></i> Download Template
          </a>
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
  <?php include '../footer.php';
  ?>
</div>

<?php include '../script.php'; 
?>
<script> 
$('#modal-edit').on('show.bs.modal', function (e) {
  var kode = $(e.relatedTarget).data('kode');
  var nama = $(e.relatedTarget).data('nama');
  var sks = $(e.relatedTarget).data('sks');
  var cpmk = $(e.relatedTarget).data('cpmk');

  $(e.currentTarget).find('input[name="kode_matkul"]').val(kode);
  $(e.currentTarget).find('input[name="nama_matkul"]').val(nama);
  $(e.currentTarget).find('input[name="jml_sks"]').val(sks);
  $(e.currentTarget).find('input[name="jml_cpmk"]').val(cpmk);
  
})
</script>
</body>
</html>
<?php
}
?>