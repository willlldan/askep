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
    <h1><strong><?= $titles[$jeniskmb] ?? 'Keperawatan Medikal Bedah' ?></strong></h1>
</div>
<br>


<ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'konsep_keperawatan' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&tab=konsep_keperawatan"> Konsep Keperawatan </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&tab=pengkajian"> Pengkajian </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian_gordon' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&tab=pengkajian_gordon"> Pengkajian FX Gordon </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'data_biologis' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&tab=data_biologis"> Data Biologis </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'data_biologis_1' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&tab=data_biologis_1"> Data Biologis 1 </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'klasifikasi_analisa_data' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&tab=klasifikasi_analisa_data"> Klasifikasi Analisa Data </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya' ? 'active' : '' ?>"
            href="index.php?page=kmb/format_kmb&tab=lainnya"> Lainnya </a>
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