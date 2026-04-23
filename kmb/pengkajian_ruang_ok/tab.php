<?php
$jeniskmb = $_GET['jeniskmb'] ?? 'kmb';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'hd' => 'Format HD KMB',
    'kmb' => 'Keperawatan Medikal Bedah',
    'ok' => 'Pengkajian Ruang OK',

];
?>

<div class="card">
    <div class="card-body">
        <div class="row mb-3 mt-3   ">
            <label for="jeniskmb" class="col-sm-2 col-form-label"><strong>KEPERAWATAN MEDIKAL BEDAH</strong></label>
            <div class="col-sm-9">

         

                                <select class="form-select" name="jeniskmb"
                        onchange="window.location=this.value" required>

                        <option value="">Pilih</option>

                        <option value="index.php?page=kmb/pengkajian_ruang_ok&tab=pengkajian&jeniskmb=ok"
                        <?= $jeniskmb == 'ok' ? 'selected' : '' ?>>
                        Pengkajian Askep Ruang ok
                        </option>

                        <option value="index.php?page=kmb/format_kmb&tab=demografi&jeniskmb=kmb"
                        <?= $jeniskmb == 'kmb' ? 'selected' : '' ?>>
                        Format KMB
                        </option>

                        <option value="index.php?page=kmb/format_hd_kmb&tab=demografi&jeniskmb=hd"
                        <?= $jeniskmb == 'hd' ? 'selected' : '' ?>>
                        Format HD KMB
                        </option>

                </select>
                <div class="invalid-feedback">
                    Harap isi Jenis kmb.
                </div>
            </div>
        </div>

    </div>
</div>

<div class="pagetitle">
    <h1><strong><?= $titles[$jeniskmb] ?? 'pengkajian ruang ok' ?></strong></h1>
</div>
<br>


   <ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lp_ruangok' ? 'active' : '' ?>"
        href="index.php?page=kmb/pengkajian_ruang_ok&tab=lp_ruangok<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Format Laporan Pendahuluan Ruang Ok</a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'ruang_operasi' ? 'active' : '' ?>"
        href="index.php?page=kmb/pengkajian_ruang_ok&tab=ruang_operasi<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Laporan Ruang Operasi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'resume' ? 'active' : '' ?>"
       href="index.php?page=kmb/pengkajian_ruang_ok&tab=resume<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Format Resume Ruang OK
        </a>
    </li>
   
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'analisa' ? 'active' : '' ?>"
       href="index.php?page=kmb/pengkajian_ruang_ok&tab=analisa<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Analisa Data        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya' ? 'active' : '' ?>"
       href="index.php?page=kmb/pengkajian_ruang_ok&tab=lainnya<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
        Lainnya</a>
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