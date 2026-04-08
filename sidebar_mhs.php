<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <!-- Tampil Maternitas -->
        <li class="nav-item">

            <a class="nav-link collapsed" data-bs-target="#maternitas-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person"></i>
                <span>Maternitas</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="maternitas-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                <li>
                    <a href="index.php?page=maternitas/pengkajian_antenatal_care">
                        <i class="bi bi-circle-fill"></i>
                        <span>Input Maternitas</span>
                    </a>
                </li>

                <li>
                    <a href="index.php?page=maternitas/detail/pengkajian_antenatal_care">
                        <i class="bi bi-circle"></i>
                        <span>Data Maternitas</span>
                    </a>
                </li>

            </ul>

        </li>

        <!-- Tampil Keluarga -->

        <li class="nav-item">
            <a id="askep" class="nav-link collapsed" data-bs-target="#keluarga-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Keluarga</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="keluarga-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="index.php?page=keluarga">
                        <i class="bi bi-circle-fill"></i><span>Input Keluarga</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=keluarga/detail">
                        <i class="bi bi-circle"></i><span>Data Keluarga</span>
                    </a>
                </li>

            </ul>
        </li>

        <!-- Tampil Keperawatan Medikal Bedah -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#kmb-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-check"></i>
                <span>Keperawatan Medikal Bedah</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="kmb-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                <li>
                    <a href="index.php?page=kmb/format_hd_kmb">
                        <i class="bi bi-circle-fill"></i>
                        <span>Input KMB</span>
                    </a>
                </li>

                <li>
                    <a href="index.php?page=kmb/detail/format_hd_kmb">
                        <i class="bi bi-circle"></i>
                        <span>Data KMB</span>
                    </a>
                </li>

            </ul>
        </li>

        <!-- Tampil Gawat Darurat -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#gadar-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-activity"></i>
                <span>Gawat Darurat</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="gadar-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                <li>
                    <a href="index.php?page=gadar/icu">
                        <i class="bi bi-circle-fill"></i>
                        <span>Input Gawat Darurat</span>
                    </a>
                </li>

                <li>
                    <a href="index.php?page=gadar/detail/icu">
                        <i class="bi bi-circle"></i>
                        <span>Data Gawat Darurat</span>
                    </a>
                </li>

            </ul>
        </li>

        <!-- Tampil Gerontik -->

        <li class="nav-item">
            <a id="askep" class="nav-link collapsed" data-bs-target="#gerontik-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-heart"></i><span>Gerontik</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="gerontik-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="index.php?page=gerontik">
                        <i class="bi bi-circle-fill"></i><span>Input Gerontik</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=gerontik/detail">
                        <i class="bi bi-circle"></i><span>Data Gerontik</span>
                    </a>
                </li>

            </ul>
        </li>

        <!-- Tampil Anak -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#anak-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-emoji-laughing"></i>
                <span>Anak</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="anak-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                <li>
                    <a href="index.php?page=anak/format_anggrek">
                        <i class="bi bi-circle-fill"></i>
                        <span>Input Anak</span>
                    </a>
                </li>

                <li>
                    <a href="index.php?page=anak/detail/format_anggrek">
                        <i class="bi bi-circle"></i>
                        <span>Data Anak</span>
                    </a>
                </li>

            </ul>
        </li>

        <!-- Tampil Jiwa -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#jiwa-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-chat-left-dots"></i>
                <span>Jiwa</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="jiwa-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">

                <li>
                    <a href="index.php?page=jiwa/jiwa_rsud">
                        <i class="bi bi-circle-fill"></i>
                        <span>Input Jiwa</span>
                    </a>
                </li>

                <li>
                    <a href="index.php?page=jiwa/detail/jiwa_rsud">
                        <i class="bi bi-circle"></i>
                        <span>Data Jiwa</span>
                    </a>
                </li>

            </ul>
        </li>

    </ul>
    
</aside><!-- End Sidebar-->

