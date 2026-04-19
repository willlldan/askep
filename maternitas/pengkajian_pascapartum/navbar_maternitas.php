<?php
$jenismaternitas = $_GET['jenismaternitas'] ?? 'pascapartum';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'antenatal' => 'Pengkajian Asuhan Keperawatan Antenatal Care',
    'pascapartum' => 'Pengkajian Asuhan Keperawatan Pascapartum',
    'resume' => 'Resume Asuhan Keperawatan Antenatal Care',
    'inranatal' => 'Pengkajian Asuhan Keperawatan Inranatal Care',
    'ginekologi' => 'Pengkajian Asuhan Keperawatan Ginekologi'
];
?>


<div class="card">
    <div class="card-body">
        <div class="row mb-3 mt-3   ">
            <label for="jenismaternitas" class="col-sm-2 col-form-label"><strong>Maternitas</strong></label>
            <div class="col-sm-9">

                <select class="form-select" name="jenismaternitas"
                    onchange="window.location=this.value" required>

                    <option value="">Pilih</option>

                    <option value="index.php?page=maternitas/pengkajian_antenatal_care&jenismaternitas=antenatal"
                        <?= $jenismaternitas == 'antenatal' ? 'selected' : '' ?>>
                        Pengkajian Antenatal Care
                    </option>

                    <option value="index.php?page=maternitas/pengkajian_pascapartum&jenismaternitas=pascapartum"
                        <?= $jenismaternitas == 'pascapartum' ? 'selected' : '' ?>>
                        Pengkajian Pascapartum
                    </option>

                    <option value="index.php?page=maternitas/resume_antenatal_care&jenismaternitas=resume"
                        <?= $jenismaternitas == 'resume' ? 'selected' : '' ?>>
                        Resume Antenatal Care
                    </option>

                    <option value="index.php?page=maternitas/pengkajian_inranatal_care&jenismaternitas=inranatal"
                        <?= $jenismaternitas == 'inranatal' ? 'selected' : '' ?>>
                        Pengkajian Inranatal Care
                    </option>

                    <option value="index.php?page=maternitas/pengkajian_ginekologi&jenismaternitas=ginekologi"
                        <?= $jenismaternitas == 'ginekologi' ? 'selected' : '' ?>>
                        Pengkajian Ginekologi
                    </option>

                </select>
                <div class="invalid-feedback">
                    Harap isi Jenis Maternitas.
                </div>
            </div>
        </div>

    </div>
</div>

<div class="pagetitle">
    <h1><strong><?= $titles[$jenismaternitas] ?? 'Pengkajian Asuhan Keperawatan Pascapartum' ?></strong></h1>
</div>
<br>

<ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'identitas' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_pascapartum&jenismaternitas=<?= $jenismaternitas ?>&tab=identitas<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Identitas
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'data_biologis' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_pascapartum&jenismaternitas=<?= $jenismaternitas ?>&tab=data_biologis<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Data Biologis
        </a>
    </li>
  

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pemeriksaan_fisik1' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_pascapartum&jenismaternitas=<?= $jenismaternitas ?>&tab=pemeriksaan_fisik1<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Pemeriksaan Fisik
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pemeriksaan_fisik2' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_pascapartum&jenismaternitas=<?= $jenismaternitas ?>&tab=pemeriksaan_fisik2<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Pemeriksaan Fisik Lanjut
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pemeriksaan_fisik3' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_pascapartum&jenismaternitas=<?= $jenismaternitas ?>&tab=pemeriksaan_fisik3<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Pemeriksaan Lanjut 1
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'terapi_lab' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_pascapartum&jenismaternitas=<?= $jenismaternitas ?>&tab=terapi_lab<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Terapi Lab
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'riwayat_kehamilan' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_pascapartum&jenismaternitas=<?= $jenismaternitas ?>&tab=riwayat_kehamilan<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
            Riwayat Kehamilan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya' ? 'active' : '' ?>"
            href="index.php?page=maternitas/pengkajian_pascapartum&jenismaternitas=<?= $jenismaternitas ?>&tab=lainnya<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
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