<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    
    <!-- Beranda -->
    <li class="nav-item">
      <a href="../home_dosen/" class="nav-link <?= ($hal == 'Beranda_dosen') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-home"></i>
        <p>Beranda</p>
      </a>
    </li>

    <!-- Kelas Mata Kuliah -->
    <li class="nav-item">
      <a href="../dosen_kelas_matkul/" class="nav-link <?= ($hal == 'kelas_matkul') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-chalkboard-teacher"></i>
        <p>Kelas Mata Kuliah</p>
      </a>
    </li>

    <!-- Ganti Password -->
    <li class="nav-item">
      <a href="../dosen_ganti_password/" class="nav-link <?= ($hal == 'ganti_password') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-key"></i>
        <p>Ganti Password</p>
      </a>
    </li>

    <!-- Logout -->
    <li class="nav-item">
      <a href="../logout.php" class="nav-link">
        <i class="nav-icon fas fa-power-off"></i>
        <p>Logout</p>
      </a>
    </li>

  </ul>
</nav>