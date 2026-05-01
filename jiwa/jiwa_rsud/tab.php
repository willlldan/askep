<?php
$jenisjiwa = $_GET['jenisjiwa'] ?? 'jiwa_rsud';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'jiwa_rsud' => 'Asuhan Keperawatan Jiwa RSUD',
    'poli_jiwa' => 'Asuhan Keperawatan Poli Jiwa'
];
?>

    <div class="card">
        <div class="card-body">
    
        <h5 class="card-title"><strong>Jenis Jiwa</strong></h5>

                <!-- Jenis Jiwa -->

                <?php
                    $jenisjiwa = $_GET['jenisjiwa'] ?? 'jiwa_rsud';
                   
                ?>

                <div class="row mb-3">
                    <label for="jenisjiwa" class="col-sm-2 col-form-label"><strong>Jiwa</strong></label>
                        <div class="col-sm-10">

                        <select class="form-select" name="jenisjiwa"
                        onchange="window.location=this.value" required>

                       <option value="">Pilih</option>
                        <option value="index.php?page=jiwa/jiwa_rsud&tab=format_laporan_pendahuluan&jenisjiwa=jiwa_rsud"
                        <?= $jenisjiwa == 'jiwa_rsud' ? 'selected' : '' ?>>
                        Jiwa RSUD
                        </option>

                        <option value="index.php?page=jiwa/poli_jiwa&tab=format_laporan_pendahuluan&jenisjiwa=poli_jiwa"
                        <?= $jenisjiwa == 'poli_jiwa' ? 'selected' : '' ?>>
                        Poli Jiwa
                        </option>
                       

                        </select>
                        <div class="invalid-feedback">
                            Harap isi Jenis Jiwa.
                        </div>
                    </div>
                </div>

             </div>
    </div>
<div class="pagetitle">
    <h1><strong><?= $titles[$jenisjiwa] ?? 'Pengkajian Asuhan Keperawatan' ?></strong></h1>
</div>
<br>

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
    <a class="nav-link <?= ($_GET['tab'] ?? 'format_laporan_pendahuluan') == 'format_laporan_pendahuluan' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=format_laporan_pendahuluan<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
    Format Laporan Pendahuluan
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=pengkajian<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
    Format Pengkajian
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajianlanjutan' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=pengkajianlanjutan<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
    Format Pengkajian Lanjutan
    </a>
</li>

<li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya' ? 'active' : '' ?>"
    href="index.php?page=jiwa/jiwa_rsud&tab=lainnya<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
    Lainnya
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

