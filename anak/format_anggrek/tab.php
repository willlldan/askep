<?php
$jenisAnak = $_GET['jenisAnak'] ?? 'anggrek';
$jenisAnak = isset($_GET['jenisAnak']) ? $_GET['jenisAnak'] : '';
$submission_id = $_GET['submission_id'] ?? null;
$titles = [
    'anggrek' => 'Asuhan Keperawatan Anak Anggrek B',
    'aster' => 'Anak Bayi Baru Lahir',
    'resume' => 'Format Resume Keperawatan Anak di Puskesmas',
 
];
?>


<div class="card">
    <div class="card-body">
        <div class="row mb-3 mt-3   ">
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

        <option value="index.php?page=anak/format_resume&tab=halm_tambah_format_resume_keperawatan&jenisAnak=poli_anak"
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

<div class="pagetitle">
    <h1><strong><?= $titles[$jenisAnak] ?? 'Pengkajian Asuhan Anak' ?></strong></h1>
</div>
<br>
<ul class="nav nav-tabs custom-tabs">

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? 'format_laporan_pendahuluan') == 'format_laporan_pendahuluan' ? 'active' : '' ?>"
           href="index.php?page=anak/format_anggrek&jenisanak=<?= $jenisAnak ?>&tab=format_laporan_pendahuluan<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
           Format Laporan Pendahuluan
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian' ? 'active' : '' ?>"
           href="index.php?page=anak/format_anggrek&jenisanak=<?= $jenisAnak ?>&tab=pengkajian<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
           Pengkajian Anak
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian_riwayat' ? 'active' : '' ?>"
           href="index.php?page=anak/format_anggrek&jenisanak=<?= $jenisAnak ?>&tab=pengkajian_riwayat<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
           Format Pengkajian Riwayat
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'pengkajian_fisik' ? 'active' : '' ?>"
           href="index.php?page=anak/format_anggrek&jenisanak=<?= $jenisAnak ?>&tab=pengkajian_fisik<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
           Pemeriksaan Fisik
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'analisa_data' ? 'active' : '' ?>"
           href="index.php?page=anak/format_anggrek&jenisanak=<?= $jenisAnak ?>&tab=analisa_data<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
           Analisa Data
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= ($_GET['tab'] ?? '') == 'lainnya' ? 'active' : '' ?>"
           href="index.php?page=anak/format_anggrek&jenisanak=<?= $jenisAnak ?>&tab=lainnya<?php if($submission_id) echo '&submission_id=' . $submission_id; ?>">
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