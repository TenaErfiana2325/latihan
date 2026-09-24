<?php
 require_once  '../database/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
 include'../css.php';

 $hal = 'beranda_mahasiswa';
 require_once '../database/koneksi.php';
?>
</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->
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
          <i class="far fa-user"></i>

        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-user"></i> PROFILE
          </a>
          <div class="dropdown-divider"></div>
          
          <div class="dropdown-divider"></div>
          <a href="../logout.php" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i> LOGOUT
          </a>
          <div class="dropdown-divider"></div>

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
          <a href="#" class="d-block">sistem manajemen</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
<?php 
 include'../sidebar_admin.php';
?>
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
        
            <div class="card card-primary card-outline">
                <?php 
                $nim = @$_GET['nim'];
                $ambil_mhs = mysqli_query($con, "SELECT * FROM tbl_mahasiswa WHERE nim = '$nim'")or die (mysqli_error($con));
                $data_mhs = mysqli_fetch_array($ambil_mhs);
                $nama = $data_mhs['nama'];
                $kontak = $data_mhs['kontak'];
                $email = $data_mhs['email'];
                $jenis_kelamin = $data_mhs['jenis_kelamin'];
                $foto = $data_mhs['img'];
                ?>
              <div class="card-body ">
                <div class="row">
                <div class="col-md-5">
                <div class="box-profile" >
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle" src="<?= $foto ?>" alt="User profile picture"> 
                </div>

                <h3 class="profile-username text-center"> <?= $nama ?></h3>
                
                <p class="text-muted text-center"><?= $nim ?></p>

                <p class="text-muted text-center">Mahasiswa Informatika UPB</p>

                <a href="#" class="btn btn-primary btn-block"><b>edit</b></a>
              </div>
            </div> 
            



<div class="col-md-7">
    <div class="table-responsive">
        <div class="table">
            <tbody>
                <th style="width: 35%;"></th>

            <table>
             <tr>
                <td> Nim </td>
                <td><?= $nim; ?></td>
             </tr>

             <tr>
                <td> Nama </td>
                <td><?= $nama; ?></td>
            </tr>

             <tr>
                <td> kontak </td>
                <td><?= $kontak; ?></td>
            </tr>

             <tr>
                <td> email </td>
                <td><?= $email; ?></td>
            </tr>
            <td> Jenis Kelamin </td>
             <td><?php
                        $jenis_kelamin = $data_mhs['jenis_kelamin'];
                        if ($jenis_kelamin == 'L') {
                          echo 'laki laki';
                          }else{
                            echo 'perempuan';
                          }?>
                          </td>
     </table>                         
    </tbody>
    </div>
    </div>
    </div>
    </div> 
    </div> 
</div>
        
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

  <!-- Main Footer -->
<?php 
 include'../footer.php';
?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<?php 
 include'../script.php';
?>
</body>
</html>