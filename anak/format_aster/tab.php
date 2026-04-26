<?php
$jenisAnak = $_GET['jenisAnak'] ?? 'aster';
$submission_id = $_GET['submission_id'] ?? null;

$titles = [
    'aster' => 'Anak Bayi Baru Lahir',
    'anggrek' => 'Keperawatan Medikal Bedah',
    'ok' => 'Pengkajian Ruang OK',

];
?>

<!-- Card Identitas -->
<div class="card">
    <div class="card-body">

        <div class="row mb-3 mt-3">
            <label for="jenisAnak" class="col-sm-2 col-form-label"><strong>Anak</strong></label>
            <div class="col-sm-9">

                <select class="form-select" name="jenisAnak"
                    onchange="window.location=this.value" required>

                    <option value="">Pilih</option>

                    <option value="index.php?page=anak/format_anggrek&tab=format_laporan_pendahuluan&jenisAnak=anggrek"
                        <?= $jenisAnak == 'anggrek' ? 'selected' : '' ?>>
                        Format Anggrek B
                    </option>

                    <option value="index.php?page=anak/format_aster&tab=format_laporan_pendahuluan&jenisAnak=aster"
                        <?= $jenisAnak == 'aster' ? 'selected' : '' ?>>
                        Format Aster
                    </option>

                    <option value="index.php?page=anak/format_resume&tab=format_laporan_pendahuluan&jenisAnak=poli_anak"
                        <?= $jenisAnak == 'poli_anak' ? 'selected' : '' ?>>
                        FORMAT RESUME KEPERAWATAN POLI ANAK
                    </option>

                </select>

                <div class="invalid-feedback">
                    Harap isi Jenis Anak.
                </div>

            </div>
        </div>


    </div>
</div>
<!-- Card Identitas -->

<div class="pagetitle">
    <h1><strong><?= $titles[$jenisAnak] ?? 'Anak' ?></strong></h1>
</div>
<br>

<ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? 'format_laporan_pendahuluan') == 'format_laporan_pendahuluan' ? 'active' : '' ?>"
            href="index.php?page=anak/format_aster&tab=format_laporan_pendahuluan">
            Format Pengkajian Bayi Baru Lahir
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'diagnosa' ? 'active' : '' ?>"
            href="index.php?page=anak/format_aster&tab=diagnosa">
            Diagnosa Keperawatan </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'rencana' ? 'active' : '' ?>"
            href="index.php?page=anak/format_aster&tab=rencana">
            Rencana Keperawatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'implementasi' ? 'active' : '' ?>"
            href="index.php?page=anak/format_aster&tab=implementasi">
            Implementasi Keperawatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'evaluasi' ? 'active' : '' ?>"
            href="index.php?page=anak/format_aster&tab=evaluasi">
            Evaluasi Keperawatan
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