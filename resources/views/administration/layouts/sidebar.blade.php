<!-- Sidebar start -->
<div class="deznav">
    <div class="deznav-scroll">
        <!-- Sidebar menu -->
        <ul class="metismenu" id="menu">
            <!-- Dashboard section -->
            <li>
                <a class="ai-icon" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt fw-bold"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <!-- Master section -->
            <li>
                <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-database fw-bold"></i>
                    <span class="nav-text">Master</span>
                </a>
                <ul aria-expanded="false">
                    <!-- Akademik -->
                    <li class="nav-label mt-1 text-uppercase small text-muted px-2">Akademik</li>
                    <li><a href="{{ route('master.sekolah.index') }}" class="fs-6">Sekolah</a></li>
                    <li><a href="{{ route('master.tahun-pelajaran.index') }}" class="fs-6">Tahun Pelajaran</a></li>
                    <li><a href="{{ route('master.semester.index') }}" class="fs-6">Semester</a></li>
                    <li><a href="{{ route('master.tingkat.index') }}" class="fs-6">Tingkat</a></li>
                    <li><a href="{{ route('master.jurusan.index') }}" class="fs-6">Jurusan</a></li>
                    <li><a href="{{ route('master.ruang-kelas.index') }}" class="fs-6">Ruang Kelas</a></li>

                    <!-- SDM -->
                    <li class="nav-label mt-2 text-uppercase small text-muted px-2">SDM</li>
                    <li><a href="{{ route('master.jabatan-guru.index') }}" class="fs-6">Jabatan Guru</a></li>
                    <li><a href="{{ route('master.guru.index') }}" class="fs-6">Guru</a></li>

                    <!-- Siswa -->
                    <li class="nav-label mt-1 text-uppercase small text-muted px-2">Siswa</li>
                    <li><a href="#" class="fs-6">PPDB</a></li>
                    <li><a href="{{ route('master.siswa.index') }}" class="fs-6">Siswa Aktif</a></li>
                    <li><a href="#" class="fs-6">Kenaikan/Kelulusan</a></li>

                    <!-- Keuangan -->
                    <li class="nav-label mt-1 text-uppercase small text-muted px-2">Keuangan</li>
                    <li><a href="{{ route('master.iuran.index') }}" class="fs-6">Iuran</a></li>
                    <li><a href="{{ route('master.keringanan.index') }}" class="fs-6">Keringanan</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-cogs fw-bold"></i>
                    <span class="nav-text">System</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('system.log-activity.index') }}" class="fs-6">Log Activity </a></li>
                    <li><a href="{{ route('log-viewer::dashboard') }}" class="fs-6">Log Viewer </a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-users-cog fw-bold"></i>
                    <span class="nav-text fs-12">RBAC</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('rbac.permission.index') }}" class="fs-6">Permission</a></li>
                    <li><a href="{{ route('rbac.role.index') }}" class="fs-6">Role</a></li>
                    <li><a href="{{ route('rbac.user.index') }}" class="fs-6">User</a></li>
                </ul>
            </li>

        </ul>

        <!-- Footer with copyright information -->
        <div class="copyright">
            <p><strong>Payment App</strong> © <span class="current-year"></span> All Rights Reserved</p>
            <p>Developed by <a href="https://www.poterteknik.com" target="_blank">PT. POTER TEKNIK INTERNASIONAL</a></p>
        </div>
    </div>
</div>
<!-- Sidebar end -->
