<nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="../home_admin/" class="nav-link <?php if ($hal == 'beranda_admin') {
                echo 'active';
            } ?>">
              <i class="nav-icon fas fa-tachometer-alt "></i>
              <p>Beranda</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../admin_periode_akademik/" class="nav-link <?= $aktif = ($hal == 'akademik') ? 'active' : '' ?>"> 
              <i class="nav-icon fas fa-calendar"></i>
              <p>Periode Akademik</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../admin_jurusan/" class="nav-link <?= $aktif = ($hal == 'beranda_jurusan') ? 'active' : '' ?>"> 
              <i class="nav-icon fas fa-book-open"></i>
              <p>Data Jurusan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../admin_mata_kuliah/" class="nav-link <?= $aktif = ($hal == 'beranda_matkul') ? 'active' : '' ?>"> 
              <i class="nav-icon fas fa-book-open"></i>
              <p>Mata Kuliah</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../admin_data_kelas_makul/" class="nav-link <?= $aktif = ($hal == 'kelas_makul') ? 'active' : '' ?>"> 
              <i class="nav-icon fas fa-home"></i>
              <p>Kelas Mata Kuliah</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../admin_data_dosen/" class="nav-link <?= $aktif = ($hal == 'admin_dosen') ? 'active' : '' ?>"> 
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>Data Dosen</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../admin_data_mahasiswa/" class="nav-link <?= $aktif = ($hal == 'admin_mahasiswa') ? 'active' : '' ?>"> 
              <i class="nav-icon fas fa-user-graduate"></i>
              <p>Data Mahasiswa</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../admin_data_administrator/" class="nav-link <?= $aktif = ($hal == 'admin_administrator') ? 'active' : '' ?>"> 
              <i class="nav-icon fas fa-user"></i>
              <p>Data Pengguna</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="../admin_ganti_password/" class="nav-link <?= $aktif = ($hal == 'ganti_password') ? 'active' : '' ?>"> 
              <i class="nav-icon fas fa-lock"></i>
              <p>Ganti Password</p>
            </a>
          </li>
          
          <li class="nav-item">
            <a href="../logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>