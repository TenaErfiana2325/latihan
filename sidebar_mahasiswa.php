<nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="../home_mahasiswa/" class="nav-link <?php if ($hal == 'Beranda_mahasiswa') {
                echo 'active';
            } ?>
            ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Beranda</p>
            </a>
          </li>
            <li class="nav-item">
            <a href="../mahasiswa_kelas/" class="nav-link <?= ($hal == 'kelas_matkul') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-chalkboard-teacher"></i>
              <p>Scan Qr</p>
            </a>
          </li>
            <li class="nav-item">
            <a href="../mahasiswa_ganti_password/" class="nav-link <?= $aktif = ($hal == 'ganti_password')? 'active':'' ?>">
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