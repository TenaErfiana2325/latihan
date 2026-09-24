<?php
require_once '../database/koneksi.php';
$authority = @$_SESSION['peran'];
if ($authority != 'A') {
  echo '<script>alert("Akun ini melakukan cross authority, akan segera di logout");</script>';
  echo '<script>window.location.href="../logout.php"</script>';
}else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
  include'../css.php';

  $hal ='kelas_matkul';
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
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">Sistem Manajemen</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
    <?php 
    include '../sidebar_admin.php' ?>
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
            <form action="" method="post">
                <div class="row">
                    <div class="col-3">
                        <?php 
                        $panggil_periode_akademik = mysqli_query($con, "SELECT * FROM tbl_akademik" )or die($con);
                        ?>
                        <div class="form-group">                    
                            <select class="form-control" name="semester" id="">
                                <?php 
                                while ($data_periode = mysqli_fetch_array($panggil_periode_akademik)){
                                    $kode_akd = $data_periode['kode_akd'];
                                    $semester = $data_periode['semester'];
                                    $tahun = $data_periode['tahun'];?>
                                <option value="<?= $kode_akd; ?>"><?= $tahun?> - <?= ($semester == 'GL')? 'Ganjil' : 'Genap'?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <button type="submit" name="btn_cari" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan Data</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah"> <i class="fas fa-plus" ></i><b>  Tambah Data</b></button>
                    </div>
                </div>
            </form>
            <?php 
            if (isset($_POST['btn_cari'])){
              $filter = trim(mysqli_real_escape_string($con, $_POST['semester']));
            ?>
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Data Kelas Mata Kuliah</h3>
                    </div>
                    <div class="card-body">
                  <a href="reset.php" type="button" class="btn btn-danger mb-2" onclick="return confirm ('Anda Yakin ingin mereset data ini?')"><i class="fa fa-exclamation-triangle"></i> Reset Data</a>
                  <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#modal-impor"> <i class="fas fa-file-excel" ></i><b>  Impor Data</b></button>
                  <a href="pdf.php" type="button" target="_blank" class="btn btn-danger mb-2" ><i class="fas fa-file-pdf"></i>  Ekspor Data</a>
                  <a href="excel.php" type="button" target="_blank" class="btn btn-success mb-2" ><i class="fas fa-file-excel"></i>  Ekspor Data</a>
                  <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#modal-impor-2"> <i class="fas fa-file-excel" ></i><b>  Impor Data Full</b></button>
                <?php 
                $pengguna = $_SESSION['username'];
                ?>
       
                <table id="example1" class="table table-bordered table-striped" >
                  <thead>
                  <tr>
                    <th width="5%">No</th>
                    <th>Nama Kelas</th>
                    <th>Periode Akademik</th>
                    <th>Matkul</th>
                    <th>Jurusan</th>
                    <th>Dosen</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                  $panggil_data_jurusan = mysqli_query($con, "SELECT * FROM tbl_kelasmatkul WHERE kode_akd = '$filter'")or die(mysqli_error($con));
                  
                  $no = 1;
                  $rv = mysqli_num_rows($panggil_data_jurusan);
                  if ($rv > 0) {
                    while ($data = mysqli_fetch_array($panggil_data_jurusan)) {
                      $kode_kelas = $data['kode_kelas'];
                      $kode_akd = $data['kode_akd'];
                      $kode_matkul = $data['kode_matkul'];
                      $kode_jurusan = $data['kode_jurusan'];
                      $nik = $data['nik'];
                      $nama_kelas = $data['nama_kelas'];
                      ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $nama_kelas ?></td>
                        <td><?php 
                        $query_akademik = mysqli_query($con,"SELECT tahun,semester FROM tbl_akademik WHERE kode_akd = '$kode_akd'")or die(mysqli_error($con));
                        $data_akademik = mysqli_fetch_array($query_akademik);
                        echo $data_akademik['tahun']. ' - '.($data_akademik['semester']=='GN'? 'Genap' : 'Ganjil');
                        ?></td>
                        <td><?php 
                        $query_matkul = mysqli_query($con,"SELECT nama_matkul FROM tbl_matkul WHERE kode_matkul = '$kode_matkul'")or die(mysqli_error($con));
                        $data_matkul = mysqli_fetch_array($query_matkul);
                        echo $data_matkul['nama_matkul'];
                        ?></td>
                        <td><?php 
                        $query_jurusan = mysqli_query($con,"SELECT nama_jurusan FROM tbl_jurusan WHERE kode_jurusan = '$kode_jurusan'")or die(mysqli_error($con));
                        $data_jurusan = mysqli_fetch_array($query_jurusan);
                        echo $data_jurusan['nama_jurusan'];
                        ?></td>
                        <td><?php 
                        $query_dosen = mysqli_query($con,"SELECT nama FROM tbl_dosen WHERE nik = '$nik'")or die(mysqli_error($con));
                        $data_dosen = mysqli_fetch_array($query_dosen);
                        echo $data_dosen['nama'];
                        ?></td>
                       
                        <td>
                          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-edit" data-kode= "<?=$data['kode_kelas'];?>" data-akademik= "<?=$data['kode_akd'];?>" data-matkul= "<?=$data['kode_matkul'];?>" data-jurusan="<?=$data['kode_jurusan'];?>" data-dosen="<?=$data['nik'];?>" data-kelas="<?=$data['nama_kelas'];?>"><i class="fas fa-edit"></i></button>
                          <a href='hapus.php?kode_kelas=<?=$data['kode_kelas'] ?>'  class='btn btn-danger btn-sm' onclick="return confirm('Yakin ingin hapus kelas ini?')"> <i class="fas fa-trash"></i></a>
                          <a href='../detail_kelas_matkul?kode_kelas=<?=$data['kode_kelas'] ?>'  class='btn btn-success btn-sm'> <i class="fas fa-eye"></i></a>
                          <a href='pertemuan.php?kode_kelas=<?=$data['kode_kelas'] ?>'  class='btn btn-warning btn-sm'> <i class="fas fa-qrcode"></i></a>
                        </td>
                      </tr>
                      <?php
                    }
                  }else {
                    echo '<center>Data Tidak Ditemukan</center>';
                  }

                  ?>
                  </tbody>

                </table>
              </div>
              <!-- /.card-body -->
            
            </div>
            <?php 
            }
            ?>
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
   <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Data Mata Kuliah</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php 
            $kelas = @$_GET['kode_kelas'];
            $query_jurusan = mysqli_query($con, "SELECT * FROM tbl_jurusan" )or die($con);
            $query_matkul = mysqli_query($con, "SELECT * FROM tbl_matkul" )or die($con);
            $query_dosen = mysqli_query($con, "SELECT * FROM tbl_dosen" )or die($con);
            ?>
            <form action="tambah.php" method="post">
            <div class="modal-body">
              
                <div class="form-group">
                  <label for="">Periode Akademik</label>
                  <select name="kode_akd" id="kode_akd" class="form-control">
                    <option value="">--Pilih Periode Akademik--</option>
                    <?php 
                    mysqli_data_seek($panggil_periode_akademik, 0);
                   while ($dp = mysqli_fetch_array($panggil_periode_akademik)){ ?>
                   <option value="<?= $dp['kode_akd']?>"><?= $dp['tahun']?> - <?= ($dp['semester']=='GL')? 'Ganjil' : 'Genap' ?> </option>
                    <?php }?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Matkul</label>
                  <select name="kode_matkul" id="kode_matkul" class="form-control">
                    <option value="">--Pilih Matkul--</option>
                    <?php 
                    mysqli_data_seek($query_matkul, 0);
                   while ($dm = mysqli_fetch_array($query_matkul)){ ?>
                   <option value="<?= $dm['kode_matkul']?>"><?= $dm['nama_matkul'] ?></option>
                    <?php }?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Jurusan</label>
                  <select name="kode_jurusan" id="kode_jurusan" class="form-control">
                    <option value="">--Pilih Jurusan--</option>
                    <?php 
                    mysqli_data_seek($query_jurusan, 0);
                   while ($dj = mysqli_fetch_array($query_jurusan)){ ?>
                   <option value="<?= $dj['kode_jurusan']?>"><?= $dj['nama_jurusan'] ?></option>
                    <?php }?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Dosen</label>
                  <select name="nik" id="nik" class="form-control">
                    <option value="">--Pilih Dosen--</option>
                    <?php 
                    mysqli_data_seek($query_dosen, 0);
                   while ($dd = mysqli_fetch_array($query_dosen)){ ?>
                   <option value="<?= $dd['nik']?>"><?= $dd['nama'] ?></option>
                    <?php }?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="nama_kelas">Nama Kelas</label>
                  <input type="text" name="nama_kelas" class="form-control" id="nama_kelas" placeholder="Masukan Nama Kelas" required>
                </div>

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
              <button type="submit" name="btn_tambah" class="btn btn-primary">Tambah</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div><!-- /.modal -->

    </form>

    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Data Kelas Matkul</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="ubah.php" method="post">
            <div class="modal-body">
              
                <div class="form-group">
                  <input type="hidden" name="kode_kelas" class="form-control" id="kode_kelas" placeholder="Masukan Nama Kelas">
                  <label for="">Periode Akademik</label>
                  <select name="kode_akd" id="kode_akd" class="form-control">
                    <option value="">--Pilih Periode Akademik--</option>
                    <?php 
                    mysqli_data_seek($panggil_periode_akademik, 0);
                   while ($dp = mysqli_fetch_array($panggil_periode_akademik)){ ?>
                   <option value="<?= $dp['kode_akd']?>"><?= $dp['tahun']?> - <?= ($dp['semester']=='GL')? 'Ganjil' : 'Genap' ?> </option>
                    <?php }?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Matkul</label>
                  <select name="kode_matkul" id="kode_matkul" class="form-control">
                    <option value="">--Pilih Matkul--</option>
                    <?php 
                    mysqli_data_seek($query_matkul, 0);
                   while ($dm = mysqli_fetch_array($query_matkul)){ ?>
                   <option value="<?= $dm['kode_matkul']?>"><?= $dm['nama_matkul'] ?></option>
                    <?php }?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Jurusan</label>
                  <select name="kode_jurusan" id="kode_jurusan" class="form-control">
                    <option value="">--Pilih Jurusan--</option>
                    <?php 
                    mysqli_data_seek($query_jurusan, 0);
                   while ($dj = mysqli_fetch_array($query_jurusan)){ ?>
                   <option value="<?= $dj['kode_jurusan']?>"><?= $dj['nama_jurusan'] ?></option>
                    <?php }?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="">Dosen</label>
                  <select name="nik" id="nik" class="form-control">
                    <option value="">--Pilih Dosen--</option>
                    <?php 
                    mysqli_data_seek($query_dosen, 0);
                   while ($dd = mysqli_fetch_array($query_dosen)){ ?>
                   <option value="<?= $dd['nik']?>"><?= $dd['nama'] ?></option>
                    <?php }?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="nama_kelas">Nama Kelas</label>
                  <input type="text" name="nama_kelas" class="form-control" id="nama_kelas" placeholder="Masukan Nama Kelas" required>
                </div>

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
              <button type="submit" name="btn_edit" class="btn btn-primary">Edit</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div><!-- /.modal -->
    </form>

    
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
                <input type="hidden" name="kode_kelas" value="<?= $kelas ?>" id="kode_kelas">
                  <label for="file">Download File Template</label>
                  <a href="template/template_kelas_matkul.xls" download class="btn btn-success btn-ms">Download</a>
                  <div class="form group">
                    <label for="">Download File Pendukung</label>
                    <div class="row mt-2">
                      <div class="col-md-6 mb-2">
                        <a href="../admin_periode_akademik/excel.php" download class="btn btn-success btn-ms">Download Data Akademik</a>
                      </div>
                      <div class="col-md-6 mb-2">
                        <a href="../admin_mata_kuliah/excel.php" download class="btn btn-success btn-ms">Download Data Matkul</a>
                      </div>
                      <div class="col-md-6 mb-2">
                        <a href="../admin_data_jurusan/excel.php" download class="btn btn-success btn-ms">Download Data Jurusan</a>
                      </div>
                      <div class="col-md-6 mb-2">
                        <a href="../admin_data_dosen/excel.php" download class="btn btn-success btn-ms">Download data Dosen</a>
                      </div>
                    </div>
              </div>
              </div>
              <div class="form-group">
                <label for="file">Upload File Template</label>
                <input type="file" class="form-control" name="file_excel" required >
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
              <button type="submit" name="btn_impor" class="btn btn-primary">Impor</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div><!-- /.modal -->


      <div class="modal fade" id="modal-impor-2">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Impor Data Mahasiswa Kelas Matkul</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="impor_full.php" method="post" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="form-group">
                <input type="hidden" name="kode_kelas" value="<?= $kelas ?>" id="kode_kelas">
                  <label for="file">Download File Template</label>
                  <a href="template/template_kelas_full.xls" download class="btn btn-success btn-ms">Download</a>
                  <div class="form group">
                    <label for="">Download File Pendukung</label>
                    <div class="row mt-2">
                      <div class="col-md-6 mb-2">
                        <a href="../admin_periode_akademik/excel.php" download class="btn btn-success btn-ms">Download Data Akademik</a>
                      </div>
                      <div class="col-md-6 mb-2">
                        <a href="../admin_mata_kuliah/excel.php" download class="btn btn-success btn-ms">Download Data Matkul</a>
                      </div>
                      <div class="col-md-6 mb-2">
                        <a href="../admin_data_jurusan/excel.php" download class="btn btn-success btn-ms">Download Data Jurusan</a>
                      </div>
                      <div class="col-md-6 mb-2">
                        <a href="../admin_data_dosen/excel.php" download class="btn btn-success btn-ms">Download Data Dosen</a>
                      </div>
                      <div class="col-md-6 mb-2">
                        <a href="../admin_data_mahasiswa/excel.php" download class="btn btn-success btn-ms">Download Data Mahasiswa</a>
                      </div>
                    </div>
              </div>
              </div>
              <div class="form-group">
                <label for="file">Upload File Template</label>
                <input type="file" class="form-control" name="file_excel" required >
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
              <button type="submit" name="btn_impor_full" class="btn btn-primary">Impor</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div><!-- /.modal -->

     
  <!-- Main Footer -->
    <?php include '../footer.php' ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include '../script.php' ?>

<script>
  $('#modal-edit').on('show.bs.modal',function (e) {
    var kode = $(e.relatedTarget).data('kode');
    var akademik = $(e.relatedTarget).data('akademik');
    var matkul = $(e.relatedTarget).data('matkul');
    var jurusan = $(e.relatedTarget).data('jurusan');
    var dosen = $(e.relatedTarget).data('dosen');
    var kelas = $(e.relatedTarget).data('kelas');
    
    $(e.currentTarget).find('input[name="kode_kelas"]').val(kode);
    $(e.currentTarget).find('select[name="kode_akd"]').val(akademik);
    $(e.currentTarget).find('select[name="kode_matkul"]').val(matkul);
    $(e.currentTarget).find('select[name="kode_jurusan"]').val(jurusan);
    $(e.currentTarget).find('select[name="nik"]').val(dosen);
    $(e.currentTarget).find('input[name="nama_kelas"]').val(kelas);
  });

</script>
</body>
</html>
<?php
}
?>