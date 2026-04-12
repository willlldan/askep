 <?php $username = $_SESSION['username'];
    $identitas_result = $mysqli->query("SELECT id, nama, tempat_lahir, tgl_lahir FROM tbl_gerontik_identitas WHERE created_by = '$username' ORDER BY created_at DESC"); ?>

<!-- Overlay & Script for Section Dashboard (Reusable) -->
<style>
    #dashboard-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.7);
        z-index: 9999;
        display: none;
        cursor: not-allowed;
    }
    .dashboard.noscroll {
        overflow: hidden !important;
    }
</style>
<div id="dashboard-overlay"></div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.getElementById('identitasDropdown');
    var overlay = document.getElementById('dashboard-overlay');
    var dashboard = document.querySelector('section.section.dashboard');
    function checkDropdown() {
        if (dropdown && dashboard && overlay) {
            if (!dropdown.value) {
                overlay.style.display = 'block';
                overlay.style.position = 'absolute';
                var rect = dashboard.getBoundingClientRect();
                overlay.style.top = dashboard.offsetTop + 'px';
                overlay.style.left = dashboard.offsetLeft + 'px';
                overlay.style.width = dashboard.offsetWidth + 'px';
                overlay.style.height = dashboard.offsetHeight + 'px';
                dashboard.style.pointerEvents = 'none';
                dashboard.classList.add('noscroll');
            } else {
                overlay.style.display = 'none';
                dashboard.style.pointerEvents = '';
                dashboard.classList.remove('noscroll');
            }
        }
    }
    checkDropdown();
    if (dropdown) {
        dropdown.addEventListener('change', function() {
            checkDropdown();
        });
    }
});
</script>

    <div class="pagetitle">
        <h1><strong>Pengkajian Post Partum</strong></h1>
        <!-- <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        </nav> -->
    </div><!-- End Page Title -->
    <br>

    <ul class="nav nav-tabs custom-tabs">
        <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'identitas' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_pascapartum&tab=identitas">
        Identitas
        </a>
    </li>
        <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'riwayat_kehamilan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_pascapartum&tab=riwayat_kehamilan">
        Riwayat Kehamilan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'data_biologis' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_pascapartum&tab=data_biologis">
        Data Biologis
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pemeriksaan_fisik1' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_pascapartum&tab=pemeriksaan_fisik1">
        Pemeriksaan Fisik 1
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pemeriksaan_fisik2' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_pascapartum&tab=pemeriksaan_fisik2">
        Pemeriksaan Fisik 2
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pemeriksaan_fisik3' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_pascapartum&tab=pemeriksaan_fisik3">
        Pemeriksaan Fisik 3
        </a>
    </li>

   

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_pascapartum&tab=diagnosa_keperawatan">
        Diagnosa keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'intervensi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_pascapartum&tab=intervensi_keperawatan">
        Intervensi keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_pascapartum&tab=implementasi_keperawatan">
        Implementasi keperawatan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi_keperawatan' ? 'active' : '' ?>"
        href="?page=maternitas/pengkajian_pascapartum&tab=evaluasi_keperawatan">
        Evaluasi keperawatan
        </a>
    </li>

    </ul>

 <style>
     .custom-tabs {
         border-bottom: 1px solid #dee2e6;
     }

     .custom-tabs .nav-link {
         border: none;
         background: transparent;
         color: #f6f9ff;
         font-weight: 500;
         padding: 10px 20px;
     }

     .custom-tabs .nav-link:hover {
         color: #4154f1;
     }

     .custom-tabs .nav-link.active {
         border: none;
         border-bottom: 3px solid #4154f1;
         color: #4154f1;
         font-weight: 600;
         background: transparent;
     }
 </style>

<?php if (($_GET['tab'] ?? 'identitas') !== 'identitas'): ?>
<div class="card">
        <div class="card-body">
                <!-- Dropdown Identitas Pasien -->
                <div class="row mb-3 mt-3">
                        <label class="col-sm-2 col-form-label"><strong>Identitas Pasien</strong></label>
                        <div class="col-sm-9">
                                <?php $selected_id = $_GET['idpasien'] ?? ''; ?>
                                <?= "ini adalah $selected_id" ?>
                                <select id="identitasDropdown" name="identitas_id" class="form-control" required>
                                        <option value="">Pilih Satu</option>
                                        <?php while ($row = $identitas_result->fetch_assoc()): ?>
                                                <option value="<?= htmlspecialchars($row['id']) ?>" <?= ($selected_id == $row['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($row['nama']) ?> (<?= htmlspecialchars($row['tempat_lahir']) ?> - <?= date('d-m-Y', strtotime($row['tgl_lahir'])) ?>)
                                                </option>
                                        <?php endwhile; ?>
                                </select>
                        </div>
                </div>
        </div>
</div>
<!-- <style>
    #dashboard-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255,255,255,0.7);
        z-index: 9999;
        display: none;
        cursor: not-allowed;
    }
    /* body.noscroll {
        overflow: hidden !important;
    } */
</style>
<div id="dashboard-overlay"></div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var dropdown = document.getElementById('identitasDropdown');
    var overlay = document.getElementById('dashboard-overlay');
    var dashboard = document.querySelector('section.section.dashboard');
    function checkDropdown() {
        if (dropdown && dashboard) {
            if (!dropdown.value) {
                overlay.style.display = 'block';
                dashboard.style.pointerEvents = 'none';
            } else {
                overlay.style.display = 'none';
                dashboard.style.pointerEvents = '';
            }
        }
    }
    checkDropdown();
    dropdown.addEventListener('change', function() {
        checkDropdown();
    });
});
</script> -->
<?php endif; ?>