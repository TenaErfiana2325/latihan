<?php
require_once '../database/koneksi.php';
$authority = @$_SESSION['peran'];
if ($authority != 'A') {
  echo '<script>alert("akun ini melakukan cross authority, akan segera di logout");</script>';
  echo '<script>window.location.href= "../logout.php" </script>';
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
  include '../css.php';

  $hal ='beranda_dosen';
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
          <?= $_SESSION['nama']; ?> <?= $_SESSION['peran']; ?> <i class="far fa-user"></i>
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
      <!-- Sidebar user panel (optional) -->
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
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Dosen</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah"> 
                  <i class="fas fa-plus"></i><b> Tambah Data</b>
                </button>
               <a href="reset.php" type="button" class="btn btn-danger mb-2" onclick="return confirm ('anda yakin ingin mereset data ini')">
                <i class="fa fa-exclamation-triangle">reset data</i></a>

                <a href="excel.php" type="button" target="_blank" class="btn btn-danger mb-2">
                <i class="fa fa-file-pdf">Exspor Excel</i></a>

              <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#modal-impor"> 
                <i class="fas fa-file-excel"></i><b> impor Data</b>
              </button>


             <a href="pdf.php" type="button" target="_blank" class="btn btn-danger mb-2"><i class="fas fa-file-pdf"> ekspor pdf</i></a>

                  <?php
                  $pengguna = $_SESSION['username'] ?? '';
                  ?>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th>Nik</th>
                    <th>Nama</th>
                    <th>Kontak</th>
                    <th>Email</th>
                    <th>Jenis Kelamin</th>
                    <th>foto</th>
                    <th width="15%">Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                  $panggil_data_dosen = mysqli_query($con, "SELECT * FROM tbl_dosen") or die(mysqli_error($con));
                  $no = 1;
                  $rv = mysqli_num_rows($panggil_data_dosen);
                  if ($rv > 0) {
                    while ($data = mysqli_fetch_array($panggil_data_dosen)) {
                      $nik = $data['nik'];
                      $nama = $data['nama'];
                      $kontak = $data['kontak'];
                      $email = $data['email'];
                      $jenis_kelamin = $data['jenis_kelamin'];
                      $foto = $data['img'];
                       ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $nik ?></td>
                        <td><?= $nama ?></td>
                        <td><?= $kontak ?></td>
                        <td><?= $email ?></td>
                        <td>
                          <?php
                        $jenis_kelamin = $data['jenis_kelamin'];
                        if ($jenis_kelamin == 'L') {
                          echo 'laki laki';
                          }else{
                            echo 'perempuan';
                          }?>
                          </td>

                         <td>
                            
                            <?php
                            if ($jenis_kelamin == 'L') {
                              ?>
                               <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-foto" data-nik="<?=  $nik ; ?>">
                               <img src="<?= (!empty($foto))?$foto:'../aset_web/img/dosen_laki-laki.png'?>" alt="photo" class="img-fluid" style="width: 100px;"> </img>
                               </button>
                              <?php
                              }else {
                                 ?>
                                 <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-foto" data-nik="<?= $nik ; ?>">
                                  <img src="<?= (!empty($foto))?$foto:'../aset_web/img/dosen_perempuan.png'?>" alt="photo" class="img-fluid" style="width: 100px;"> </img>
                                  </button>
                                  <?php
                              }
                            $foto; 
                            ?>
                        </td>

                        <td>
                        <a href='edit_user.php?nik=<?=$data['nik'];?>&nama=<?=$data['nama'];?>&kontak=<?=$data['kontak'];?>&email=<?=$data['email'];?>&jenis_kelamin=<?=$data['jenis_kelamin'];?>' class='btn btn-warning btn-sm'><i class= "fas fa-pen" ></i>Edit</a> 
                        <a href="detail_profile.php?nik=<?=$data['nik']; ?>&nama=<?=$data['nama']; ?>&kontak=<?=$data['kontak']; ?>" type="button" class="btn btn-success btn-sm"><i class="fas fa-user"></i></a>

                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-edit" 
                        data-nik="<?= $nik; ?>"
                        data-nama="<?= $nama; ?>"
                        data-kontak="<?= $kontak; ?>"
                        data-email="<?= $email; ?>"
                        data-jk="<?= $jenis_kelamin; ?>">
                        <i class="fas fa-edit" ></i>
                      </button>

                        <a href='hapus.php?nik=<?= $data['nik'];?>' class='btn btn-danger btn-sm' onclick="return confirm('Yakin ingin hapus ini?')"><i class="fas fa-trash"></i>hapus</a>
                    </td>
                      </tr>
                      <?php
                    }
                  } else {
                    echo '<tr><td colspan="7" class="text-center">Data Tidak Ditemukan</td></tr>';
                  }

                  ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Modal Tambah Data -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Dosen</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="tambah.php" method="post">
          <div class="modal-body">
              <div class="form-group">
                <label for="tambah_nik">NIK</label>
                <input type="text" inputmode="numeric" name="nik" class="form-control" id="tambah_nik" placeholder="Masukan NIK" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label for="tambah_nama">Nama</label>
                <input type="text" name="nama" class="form-control" id="tambah_nama" placeholder="Masukan Nama" required>
              </div>
              <div class="form-group">
                <label for="tambah_kontak">Kontak</label>
                <input type="text" inputmode="numeric" maxlength="13" name="kontak" class="form-control" id="tambah_kontak" placeholder="Masukan Kontak (Max 13 angka)" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label for="tambah_email">Email</label>
                <input type="email" maxlength="100" name="email" class="form-control" id="tambah_email" placeholder="Masukan Email" required>
              </div>
              <div class="form-group">
                <label for="tambah_jenis_kelamin">Jenis Kelamin</label>
                <select class="form-control" id="tambah_jenis_kelamin" name="jenis_kelamin" required>
                  <option value="">-- Pilih Jenis Kelamin --</option>
                  <option value="L">Laki-laki</option>
                  <option value="P">Perempuan</option>
                </select>
              </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="btn_tambah" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">edit Data Dosen</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="ubah.php" method="post">
          <div class="modal-body">
              <div class="form-group">
                <label for="edit_nik">NIK</label>
                <input type="text" inputmode="numeric" name="nik" class="form-control" id="edit_nik" placeholder="Masukan NIK" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label for="edit_nama">Nama</label>
                <input type="text" name="nama" class="form-control" id="edit_nama" placeholder="Masukan Nama" required>
              </div>
              <div class="form-group">
                <label for="edit_kontak">Kontak</label>
                <input type="text" inputmode="numeric" maxlength="13" name="kontak" class="form-control" id="edit_kontak" placeholder="Masukan Kontak (Max 13 angka)" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label for="edit_email">Email</label>
                <input type="email" maxlength="100" name="email" class="form-control" id="edit_email" placeholder="Masukan Email" required>
              </div>
              <div class="form-group">
                <label for="edit_jenis_kelamin">Jenis Kelamin</label>
                <select class="form-control" id="edit_jenis_kelamin" name="jenis_kelamin" required>
                  <option value="">-- Pilih Jenis Kelamin --</option>
                  <option value="L">Laki-laki</option>
                  <option value="P">Perempuan</option>
                </select>
              </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="btn_edit" class="btn btn-primary">edit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal impor data -->
  <div class="modal fade" id="modal-impor">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">impor data dosen</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="impor.php" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">
              <label>download file template</label>
              <a href="template/template_dosen.xls" download class="btn btn-success ml-2">download</a>
            </div>
            <div class="form-group">
              <label for="file_excel">upload file template</label>
              <input type="file" id="file_excel" class="form-control" name="file_excel" required>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="btn-impor" class="btn btn-primary">impor</button>
          </div>
        </form>
      </div>
    </div>
  </div>

   <div class="modal fade" id="modal-foto">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">edit foto dosen</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="foto.php" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">

            </div>
            <div class="form-group">
              <input type="text" name="nik" hidden>
              <label for="file_foto">upload foto dosen</label>
              <input type="file" id="file_foto" class="form-control" name="file_foto" required>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="btn-foto" class="btn btn-primary">upload</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Main Footer -->
  <?php include '../footer.php' ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include '../script.php' ?>

<script>
  $('#modal-edit').on('show.bs.modal', function(e) {
    var nik= $(e.relatedTarget).data('nik');
    var nama=$(e.relatedTarget).data('nama');
    var kontak=$(e.relatedTarget).data('kontak');
    var email=$(e.relatedTarget).data('email');
    var jk=$(e.relatedTarget).data('jk');

    $(e.currentTarget).find('input[name="nik"]').val(nik);
    $(e.currentTarget).find('input[name="nama"]').val(nama);
    $(e.currentTarget).find('input[name="kontak"]').val(kontak);
    $(e.currentTarget).find('input[name="email"]').val(email);
    $(e.currentTarget).find('select[name="jenis_kelamin"]').val(jk);
  })
</script>

<script>
$('#modal-foto').on('show.bs.modal', function(e){
  var nik = $(e.relatedTarget).data('nik');

  $(e.currentTarget).find('input[name="nik"]').val(nik);
});
</script>

</body>
</html>
<?php
}
?>