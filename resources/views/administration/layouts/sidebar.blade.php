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
                    <li><a href="{{ route('master.jabatan-guru.index') }}" class="fs-6">Jabatan Guru </a></li>

                    <li><a href="{{ route('master.guru.index') }}" class="fs-6">Guru </a></li>
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
