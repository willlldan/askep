<?php
$username = $_SESSION['username'];
$selected_id = $_GET['idpasien'] ?? '';
$currentTab = $_GET['tab'] ?? 'identitas';
$tabs = [
    'identitas',
    'pengkajian-riwayat',
    'pengkajian-fisik',
    'pengkajian-kebiasaan',
    'pengkajian-psikis',
    'pengkajian-depresi',
    'pengkajian-lanjutan',
    'diagnosa_keperawatan',
    'rencana',
    'implementasi_keperawatan',
    'evaluasi_keperawatan',
    'sap',
];
$tabLabels = [
    'identitas' => 'Identitas',
    'pengkajian-riwayat' => 'Pengkajian Riwayat',
    'pengkajian-fisik' => 'Pengkajian Fisik',
    'pengkajian-kebiasaan' => 'Pengkajian Kebiasaan',
    'pengkajian-psikis' => 'Pengkajian Psikis',
    'pengkajian-depresi' => 'Pengkajian Depresi',
    'pengkajian-lanjutan' => 'Pengkajian Lanjutan',
    'diagnosa_keperawatan' => 'Diagnosa Keperawatan',
    'rencana' => 'Rencana Keperawatan',
    'implementasi_keperawatan' => 'Implementasi Keperawatan',
    'evaluasi_keperawatan' => 'Evaluasi Keperawatan',
    'sap' => 'Format SAP',
];
$identitas_result = $mysqli->query("SELECT id, nama, tempat_lahir, tgl_lahir FROM tbl_gerontik_identitas WHERE created_by = '$username' ORDER BY created_at DESC");
?>

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
    <h1><strong>Asuhan Keperawatan Gerontik</strong></h1>
</div>
<br>

<ul class="nav nav-tabs custom-tabs">
    <?php foreach ($tabs as $tab): ?>
        <?php
        $isActive = ($currentTab === $tab) ? 'active' : '';
        $label = $tabLabels[$tab] ?? ucfirst(str_replace('_', ' ', $tab));
        $url = "index.php?page=gerontik&tab={$tab}";
        if (!empty($selected_id)) {
            $url .= "&idpasien={$selected_id}";
        }
        ?>
        <li class="nav-item">
            <a class="nav-link js-nav-tab <?= $isActive ?>" href="<?= htmlspecialchars($url) ?>">
                <?= htmlspecialchars($label) ?>
            </a>
        </li>
    <?php endforeach; ?>
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
