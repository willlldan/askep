<?php
function checkFileSize($size, $max_size)
{
    if ($size > $max_size) {
        echo "<script>alert('File yang diupload harus kurang dari " . ($max_size/5000) . "KB')</script>";
        return 0;
    } else return 1;
}

function allowedFileType($file_type, $allowed_type)
{
    $allowed = 0;
    foreach ($allowed_type as $type) {
        if ($file_type == $type) $allowed = 1;
    }

    if($allowed === 1) return 1;
    else {
        echo "<script>alert('Hanya menerima file ".implode(', ', $allowed_type)."')</script>";
        return 0;
    }
}

function dateFormatter($date) {
    
    $timestamp = strtotime($date); // Ganti tanggal yang sesuai
    $dayOfWeek = date('w', $timestamp);
    $month = date('m', $timestamp)-1;
    $tanggal = date('d', $timestamp);
    $year = date('Y', $timestamp);

    return HARI_DALAM_INDONESIA[$dayOfWeek] . ', ' . "$tanggal " . BULAN_DALAM_INDONESIA[$month] ." $year";
}

function getAllDataPinjaman($mysqli) {

    $dataPinjaman = getDataPinjaman($mysqli);
    $dataPinjamanPersonel = getDataPinjamanPersonel($mysqli);

    // $data = array_merge($dataPinjaman['data'], $dataPinjamanPersonel['data']);
    // usort($data, function($a, $b) {
   //     return strtotime($b['tgl_kembali']) - strtotime($a['tgl_kembali']);

   return $dataPinjaman;
    };

   // $count = $dataPinjaman['count'] + $dataPinjamanPersonel['count'];

   // $result['count'] = $count;
   // $result['data'] = $data;

    // return $result;


function getDataPinjaman($mysqli) {

    $sql = "SELECT pd.id_peminjaman_dokumen as id, pd.nama_peminjam, pd.tgl_kembali, pd.status_peminjam FROM tbl_peminjaman_dokumen pd WHERE 
                tgl_pinjam >= DATE_SUB(CURDATE(), INTERVAL 3 DAY) ORDER BY tgl_pinjam DESC LIMIT 5";
   // $result_pinjaman = $mysqli->query($sql);
   // $count_pinjaman = $result_pinjaman->num_rows;
    $data = [];
    // while ($row = $result_pinjaman->fetch_assoc()) {
        $row["type"] = "Pinjaman";
        array_push($data, $row);
    }

   // $result['count'] = $count_pinjaman;
   // $result['data'] = $data;

   // return $result;

function getDataPinjamanPersonel($mysqli) {

    $sql = "SELECT pd.id_peminjaman_dok_personel as id, pd.nama_peminjam, pd.tgl_kembali, pd.status_peminjam FROM tbl_peminjaman_dok_personel pd WHERE 
                tgl_pinjam >= DATE_SUB(CURDATE(), INTERVAL 3 DAY) ORDER BY tgl_pinjam DESC LIMIT 5";
   // $result_pinjaman = $mysqli->query($sql);
  //  $count_pinjaman = $result_pinjaman->num_rows;
    $data = [];
    // while ($row = $result_pinjaman->fetch_assoc()) {
        $row["type"] = "Pinjaman Personel";
        array_push($data, $row);
    }

   //  $result['count'] = $count_pinjaman;
    // $result['data'] = $data;

   // return $result;

function getUrlDetailPeminjaman($type, $id) {
    if($type == "Pinjaman") {
        return "index.php?page=peminjam_dokumen&item=detail_dok&id_peminjaman_dokumen=$id";
    } 
    return "index.php?page=peminjam_dokumen_personel&item=detail_dok_personel&id_peminjaman_dok_personel=$id";
};

function getAllDataDokumen($mysqli){
    $data = array_merge(getAllDokumenMasuk($mysqli), getAllDokumenKeluar($mysqli), getAllDokumenPendukung($mysqli), getAllDokumenPersonel($mysqli));

    return $data;
}

function getAllDokumenMasuk($mysqli) {
    $sql = "SELECT masuk.no_dokumen as id, tgl_masuk_dok as tgl_dokumen, perihal FROM tbl_dok_masuk masuk ORDER BY tgl_masuk_dok DESC";
    $result = $mysqli->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row["type"] = "Dokumen Masuk";
        array_push($data, $row);
    }

    return $data;
}

function getAllDokumenKeluar($mysqli) {
    $sql = "SELECT keluar.no_dokumen as id, tgl_keluar_dok as tgl_dokumen, perihal FROM tbl_dok_keluar keluar ORDER BY tgl_keluar_dok DESC";
    $result = $mysqli->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row["type"] = "Dokumen Keluar";
        array_push($data, $row);
    }

    return $data;
}

function getAllDokumenPendukung($mysqli) {
    $sql = "SELECT pendukung.no_dokumen as id, tgl_masuk_dok as tgl_dokumen, perihal FROM tbl_dok_pendukung pendukung ORDER BY tgl_masuk_dok DESC";
    $result = $mysqli->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row["type"] = "Dokumen Pendukung";
        array_push($data, $row);
    }

    return $data;
}

function getAllDokumenPersonel($mysqli) {
    $sql = "SELECT personel.id_dokumen_personel as id, nama as perihal FROM tbl_dok_personel personel";
    $result = $mysqli->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row["type"] = "Dokumen Personel";
        $row["perihal"] =  "Personel " . $row['perihal'];
        $row["tgl_dokumen"] = "-";
        array_push($data, $row);
    }

    return $data;
}

function getURLMasterData($type, $id, $action) {

    $key = $action . " " .  $type;

    switch ($key) {
        case 'Detail Dokumen Masuk':
            return "index.php?page=dokumen_masuk&item=detail_dokumen_masuk&no_dokumen=$id";
        case 'Edit Dokumen Masuk':
            return "index.php?page=dokumen_masuk&item=edit_dokumen_masuk&no_dokumen=$id";
        case 'Delete Dokumen Masuk':
            return "index.php?page=dokumen_masuk&item=delete_dokumen_masuk&no_dokumen=$id";

        case 'Detail Dokumen Keluar':
            return "index.php?page=dokumen_keluar&item=detail_dokumen_keluar&no_dokumen=$id";
        case 'Edit Dokumen Keluar':
            return "index.php?page=dokumen_keluar&item=edit_dokumen_keluar&no_dokumen=$id";
        case 'Delete Dokumen Keluar':
            return "index.php?page=dokumen_keluar&item=delete_dokumen_keluar&no_dokumen=$id";

        case 'Detail Dokumen Pendukung':
            return "index.php?page=dokumen_pendukung&item=detail_dokumen_pendukung&no_dokumen=$id";
        case 'Edit Dokumen Pendukung':
            return "index.php?page=dokumen_pendukung&item=edit_dokumen_pendukung&no_dokumen=$id";
        case 'Delete Dokumen Pendukung':
            return "index.php?page=dokumen_pendukung&item=delete_dokumen_pendukung&no_dokumen=$id";

        case 'Detail Dokumen Personel':
            return "index.php?page=dokumen_personel&item=detail_dokumen_personel&id_dokumen_personel=$id";
        case 'Edit Dokumen Personel':
            return "index.php?page=dokumen_personel&item=edit_dokumen_personel&id_dokumen_personel=$id";
        case 'Delete Dokumen Personel':
            return "index.php?page=dokumen_personel&item=delete_dokumen_personel&id_dokumen_personel=$id";
        default:
            # code...
            break;
    }
}

function getCountingLabel($mysqli, $label) {
    $sql = "SELECT * FROM tbl_label_arsip lbl WHERE lbl.label_arsip LIKE '$label'";
    $result = $mysqli->query($sql);
    return $result->num_rows;
}

function getAllCountingLabel($mysqli) {
    $DM = getCountingLabel($mysqli, "DM") + 1;
    $DK = getCountingLabel($mysqli, "DK") + 1;
    $DG = getCountingLabel($mysqli, "DG") + 1;
    $DP = getCountingLabel($mysqli, "DP") + 1;

    echo "<script>";
   
    echo "let existingNoUrut = {
        'DM': $DM,
        'DK': $DK,
        'DG': $DG,
        'DP': $DP
    }
    
    let startingNoUrut = {
        'DM': $DM,
        'DK': $DK,
        'DG': $DG,
        'DP': $DP
    }
    
    ";

    echo "</script>";
}

function getJenisDokumen($kode) {
    switch ($kode) {
        case 'DM':
            return "Dokumen Masuk";
            break;
        case 'DK':
            return "Dokumen Keluar";
            break;
        case 'DG':
            return "Dokumen Pendukung";
            break;
        case 'DP':
            return "Dokumen Personel";
            break;
        
        default:
            # code...
            break;
    }
}


const BULAN_DALAM_INDONESIA = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember"
];

const HARI_DALAM_INDONESIA = [
    "Minggu",
    "Senin",
    "Selasa",
    "Rabu",
    "Kamis",
    "Jumat",
    "Sabtu"
];


// Buat Askep

/**
 * =============================================
 * UTILS.PHP
 * Helper functions untuk form pengkajian
 * =============================================
 */

/**
 * Ambil submission existing berdasarkan user & form
 * Return array submission atau false jika belum ada
 */
function getSubmission($user_id, $form_id, $mysqli) {
    $stmt = $mysqli->prepare("
        SELECT id, status, tanggal_pengkajian, rs_ruangan 
        FROM submissions 
        WHERE user_id = ? AND form_id = ?
    ");
    $stmt->bind_param("ii", $user_id, $form_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

/**
 * Ambil data JSON section existing
 * Return array data atau [] jika belum ada
 */
function getSectionData($submission_id, $section_name, $mysqli) {
    $stmt = $mysqli->prepare("
        SELECT data 
        FROM submission_sections 
        WHERE submission_id = ? AND section_name = ?
    ");
    $stmt->bind_param("is", $submission_id, $section_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $section = $result->fetch_assoc();
    return $section ? json_decode($section['data'], true) : [];
}

/**
 * Ambil status section tertentu
 * Return status string atau null jika belum ada
 */
function getSectionStatus($submission_id, $section_name, $mysqli) {
    $stmt = $mysqli->prepare("
        SELECT status 
        FROM submission_sections 
        WHERE submission_id = ? AND section_name = ?
    ");
    $stmt->bind_param("is", $submission_id, $section_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $section = $result->fetch_assoc();
    return $section ? $section['status'] : null;
}

/**
 * Insert atau update data section
 */
function saveSection($submission_id, $section_name, $section_label, $data, $mysqli) {
    $json_data = json_encode($data);
    $stmt = $mysqli->prepare("
        INSERT INTO submission_sections (submission_id, section_name, section_label, data, status)
        VALUES (?, ?, ?, ?, 'draft')
        ON DUPLICATE KEY UPDATE 
            data = VALUES(data), 
            updated_at = NOW()
    ");
    $stmt->bind_param("isss", $submission_id, $section_name, $section_label, $json_data);
    $stmt->execute();
}

/**
 * Insert submission baru
 * Return id submission yang baru dibuat
 */
function createSubmission($user_id, $form_id, $tanggal_pengkajian, $rs_ruangan, $mysqli) {
    $stmt = $mysqli->prepare("
        INSERT INTO submissions (user_id, form_id, tanggal_pengkajian, rs_ruangan, status) 
        VALUES (?, ?, ?, ?, 'draft')
    ");
    $stmt->bind_param("iiss", $user_id, $form_id, $tanggal_pengkajian, $rs_ruangan);
    $stmt->execute();
    return $mysqli->insert_id;
}

/**
 * Update tanggal_pengkajian & rs_ruangan di submissions
 * Dipanggil saat mahasiswa update section 1
 */
function updateSubmissionHeader($submission_id, $tanggal_pengkajian, $rs_ruangan, $mysqli) {
    $stmt = $mysqli->prepare("
        UPDATE submissions 
        SET tanggal_pengkajian = ?, rs_ruangan = ?, updated_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("ssi", $tanggal_pengkajian, $rs_ruangan, $submission_id);
    $stmt->execute();
}

/**
 * Cek apakah form sedang terkunci (tidak bisa diedit)
 * Locked jika status submitted atau approved
 */
function isLocked($submission) {
    if (!$submission) return false;
    return in_array($submission['status'], ['submitted', 'approved']);
}

/**
 * Update status submission secara otomatis
 * draft     → belum semua section diisi
 * submitted → semua section diisi, menunggu review
 * approved  → semua section approved oleh dosen
 */
function updateSubmissionStatus($submission_id, $form_id, $mysqli) {
    // Ambil count_section dari form
    $stmt = $mysqli->prepare("SELECT count_section FROM forms WHERE id = ?");
    $stmt->bind_param("i", $form_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count_section = $result->fetch_assoc()['count_section'];

    // Hitung section yang sudah diisi
    $stmt = $mysqli->prepare("
        SELECT COUNT(*) as filled 
        FROM submission_sections 
        WHERE submission_id = ?
    ");
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $filled = $result->fetch_assoc()['filled'];

    // Hitung section yang sudah approved
    $stmt = $mysqli->prepare("
        SELECT COUNT(*) as approved 
        FROM submission_sections 
        WHERE submission_id = ? AND status = 'approved'
    ");
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $approved = $result->fetch_assoc()['approved'];

    // Tentukan status baru
    if ($filled < $count_section) {
        $new_status = 'draft';
    } elseif ($approved == $count_section) {
        $new_status = 'approved';
    } else {
        $new_status = 'submitted';
    }

    $stmt = $mysqli->prepare("UPDATE submissions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $submission_id);
    $stmt->execute();
}

/**
 * Ambil existing value untuk ditampilin di form HTML
 * Otomatis escape HTML untuk keamanan
 */
function val($key, $existing_data) {
    return htmlspecialchars($existing_data[$key] ?? '');
}

/**
 * Redirect dengan pesan session
 */
function redirectWithMessage($url, $type, $message) {
    $_SESSION[$type] = $message;
    echo "<script>window.location.href = '$url';</script>";
    exit;
}