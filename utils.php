<?php
function checkFileSize($size, $max_size)
{
    if ($size > $max_size) {
        echo "<script>alert('File yang diupload harus kurang dari " . ($max_size / 5000) . "KB')</script>";
        return 0;
    } else return 1;
}

function allowedFileType($file_type, $allowed_type)
{
    $allowed = 0;
    foreach ($allowed_type as $type) {
        if ($file_type == $type) $allowed = 1;
    }

    if ($allowed === 1) return 1;
    else {
        echo "<script>alert('Hanya menerima file " . implode(', ', $allowed_type) . "')</script>";
        return 0;
    }
}

function dateFormatter($date)
{

    $timestamp = strtotime($date); // Ganti tanggal yang sesuai
    $dayOfWeek = date('w', $timestamp);
    $month = date('m', $timestamp) - 1;
    $tanggal = date('d', $timestamp);
    $year = date('Y', $timestamp);

    return HARI_DALAM_INDONESIA[$dayOfWeek] . ', ' . "$tanggal " . BULAN_DALAM_INDONESIA[$month] . " $year";
}

function getAllDataPinjaman($mysqli)
{

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


function getDataPinjaman($mysqli)
{

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

function getDataPinjamanPersonel($mysqli)
{

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

function getUrlDetailPeminjaman($type, $id)
{
    if ($type == "Pinjaman") {
        return "index.php?page=peminjam_dokumen&item=detail_dok&id_peminjaman_dokumen=$id";
    }
    return "index.php?page=peminjam_dokumen_personel&item=detail_dok_personel&id_peminjaman_dok_personel=$id";
};

function getAllDataDokumen($mysqli)
{
    $data = array_merge(getAllDokumenMasuk($mysqli), getAllDokumenKeluar($mysqli), getAllDokumenPendukung($mysqli), getAllDokumenPersonel($mysqli));

    return $data;
}

function getAllDokumenMasuk($mysqli)
{
    $sql = "SELECT masuk.no_dokumen as id, tgl_masuk_dok as tgl_dokumen, perihal FROM tbl_dok_masuk masuk ORDER BY tgl_masuk_dok DESC";
    $result = $mysqli->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row["type"] = "Dokumen Masuk";
        array_push($data, $row);
    }

    return $data;
}

function getAllDokumenKeluar($mysqli)
{
    $sql = "SELECT keluar.no_dokumen as id, tgl_keluar_dok as tgl_dokumen, perihal FROM tbl_dok_keluar keluar ORDER BY tgl_keluar_dok DESC";
    $result = $mysqli->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row["type"] = "Dokumen Keluar";
        array_push($data, $row);
    }

    return $data;
}

function getAllDokumenPendukung($mysqli)
{
    $sql = "SELECT pendukung.no_dokumen as id, tgl_masuk_dok as tgl_dokumen, perihal FROM tbl_dok_pendukung pendukung ORDER BY tgl_masuk_dok DESC";
    $result = $mysqli->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row["type"] = "Dokumen Pendukung";
        array_push($data, $row);
    }

    return $data;
}

function getAllDokumenPersonel($mysqli)
{
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

function getURLMasterData($type, $id, $action)
{

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

function getCountingLabel($mysqli, $label)
{
    $sql = "SELECT * FROM tbl_label_arsip lbl WHERE lbl.label_arsip LIKE '$label'";
    $result = $mysqli->query($sql);
    return $result->num_rows;
}

function getAllCountingLabel($mysqli)
{
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

function getJenisDokumen($kode)
{
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
function getSubmission($user_id, $form_id, $mysqli)
{
    $stmt = $mysqli->prepare("
        SELECT s.*,
               r.nama as dosen_name
        FROM submissions s
        LEFT JOIN tbl_user r ON s.reviewed_by = r.id_user
        WHERE s.user_id = ? AND s.form_id = ?
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
function getSectionData($submission_id, $section_name, $mysqli)
{
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
function getSectionStatus($submission_id, $section_name, $mysqli)
{
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
function saveSection($submission_id, $section_name, $section_label, $data, $mysqli)
{
    $json_data = json_encode($data);
    $stmt = $mysqli->prepare("
        INSERT INTO submission_sections (submission_id, section_name, section_label, data, status)
        VALUES (?, ?, ?, ?, 'draft')
        ON DUPLICATE KEY UPDATE 
            data = VALUES(data), 
            status = 'draft',
            updated_at = NOW()
    ");
    $stmt->bind_param("isss", $submission_id, $section_name, $section_label, $json_data);
    $stmt->execute();
}

/**
 * Insert submission baru
 * Return id submission yang baru dibuat
 */
function createSubmission($user_id, $form_id, $tanggal_pengkajian, $rs_ruangan, $mysqli)
{
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
function updateSubmissionHeader($submission_id, $tanggal_pengkajian, $rs_ruangan, $mysqli)
{
echo "updateSubmissionHeader called with: $submission_id, $tanggal_pengkajian, $rs_ruangan"; die;
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
function isLocked($submission)
{
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
    $count_section = $stmt->get_result()->fetch_assoc()['count_section'];

    // Hitung section yang sudah diisi
    $stmt = $mysqli->prepare("SELECT COUNT(*) as filled FROM submission_sections WHERE submission_id = ?");
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
    $filled = $stmt->get_result()->fetch_assoc()['filled'];

    // Hitung section yang sudah approved
    $stmt = $mysqli->prepare("SELECT COUNT(*) as approved FROM submission_sections WHERE submission_id = ? AND status = 'approved'");
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
    $approved = $stmt->get_result()->fetch_assoc()['approved'];

    // Tentukan status baru
    if ($approved == $count_section) {
        $new_status = 'approved';
    } elseif ($filled < $count_section) {
        $new_status = 'draft';
    } else {
        // Semua section diisi tapi belum submit manual → tetap draft
        $new_status = 'draft';
    }

    $stmt = $mysqli->prepare("UPDATE submissions SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $submission_id);
    $stmt->execute();
}

function submitSubmission($submission_id, $mysqli) {
    // Cek apakah ada section yang statusnya revision
    $stmt = $mysqli->prepare("
        SELECT COUNT(*) as revision 
        FROM submission_sections 
        WHERE submission_id = ? AND status = 'revision'
    ");
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
    $revision = $stmt->get_result()->fetch_assoc()['revision'];

    if ($revision > 0) {
        return [
            'success' => false,
            'message' => 'Masih ada section yang perlu direvisi.'
        ];
    }

    $stmt = $mysqli->prepare("
        UPDATE submissions 
        SET status = 'submitted', submitted_at = NOW() 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();

    return ['success' => true];
}

/**
 * Ambil existing value untuk ditampilin di form HTML
 * Otomatis escape HTML untuk keamanan
 */
function val($key, $existing_data)
{
    return htmlspecialchars($existing_data[$key] ?? '');
}

/**
 * Redirect dengan pesan session
 */
function redirectWithMessage($url, $type, $message)
{
    $_SESSION[$type] = $message;
    echo "<script>window.location.href = '$url';</script>";
    exit;
}

/**
 * Ambil semua submissions (untuk dosen)
 * Return list semua mahasiswa yang punya submission
 */
function getAllSubmissions($form_id, $mysqli)
{
    $stmt = $mysqli->prepare("
        SELECT s.*,
               u.nama as mahasiswa_name,
               u.npm as mahasiswa_npm,
               r.nama as dosen_name
        FROM submissions s
        JOIN tbl_user u ON s.user_id = u.id_user
        LEFT JOIN tbl_user r ON s.reviewed_by = r.id_user
        WHERE s.form_id = ?
        ORDER BY s.updated_at DESC
    ");
    $stmt->bind_param("i", $form_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Ambil semua sections dari submission
 */
function getAllSections($submission_id, $mysqli)
{
    $stmt = $mysqli->prepare("
        SELECT * FROM submission_sections 
        WHERE submission_id = ?
        ORDER BY id ASC
    ");
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Ambil semua komentar per section
 */
function getSectionComments($submission_id, $section_name, $mysqli)
{
    $stmt = $mysqli->prepare("
        SELECT sc.*, u.nama as dosen_name
        FROM section_comments sc
        JOIN tbl_user u ON sc.commented_by = u.id_user 
        WHERE sc.submission_id = ? AND sc.section_name = ?
        ORDER BY sc.created_at ASC
    ");
    $stmt->bind_param("is", $submission_id, $section_name);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Simpan komentar dosen
 */
function saveComment($submission_id, $section_name, $comment, $dosen_id, $mysqli)
{
    $stmt = $mysqli->prepare("
        INSERT INTO section_comments (submission_id, section_name, comment, commented_by)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("issi", $submission_id, $section_name, $comment, $dosen_id);
    $stmt->execute();
}

/**
 * Update status section oleh dosen
 */
function updateSectionStatus($submission_id, $section_name, $status, $mysqli)
{
    $stmt = $mysqli->prepare("
        UPDATE submission_sections 
        SET status = ?
        WHERE submission_id = ? AND section_name = ?
    ");
    $stmt->bind_param("sis", $status, $submission_id, $section_name);
    $stmt->execute();
}

/**
 * Update reviewed_by dan reviewed_at di submissions
 */
function updateReviewer($submission_id, $dosen_id, $mysqli)
{
    $stmt = $mysqli->prepare("
        UPDATE submissions 
        SET reviewed_by = ?, reviewed_at = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param("ii", $dosen_id, $submission_id);
    $stmt->execute();
}

/**
 * Update status submission setelah dosen action
 * Cek semua section:
 * - Ada yang revision → submission = revision
 * - Semua approved → submission = approved
 * - Selainnya → submitted
 */
function updateSubmissionStatusByDosen($submission_id, $form_id, $mysqli)
{
    $stmt = $mysqli->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = 'revision' THEN 1 ELSE 0 END) as revision
        FROM submission_sections
        WHERE submission_id = ?
    ");
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    // Ambil count_section
    $stmt2 = $mysqli->prepare("SELECT count_section FROM forms WHERE id = ?");
    $stmt2->bind_param("i", $form_id);
    $stmt2->execute();
    $count_section = $stmt2->get_result()->fetch_assoc()['count_section'];

    if ($result['revision'] > 0) {
        $new_status = 'revision';
    } elseif ($result['approved'] == $count_section) {
        $new_status = 'approved';
    } else {
        $new_status = 'submitted';
    }

    $stmt3 = $mysqli->prepare("UPDATE submissions SET status = ? WHERE id = ?");
    $stmt3->bind_param("si", $new_status, $submission_id);
    $stmt3->execute();
}

/**
 *
 * uploadImage($file, $upload_dir, $quality)
 *
 * @param array  $file        → $_FILES['nama_field']
 * @param string $upload_dir  → folder tujuan, contoh: 'uploads/kdm/'
 * @param int    $quality     → kualitas kompresi 0-100 (default 50)
 *
 * @return array [
 *   'success' => true/false,
 *   'path'    => 'uploads/kdm/filename.jpg',  // path relatif untuk disimpan ke DB
 *   'error'   => 'pesan error jika gagal'
 * ]
 * ============================================================
 */
 
function uploadImage(array $file, string $upload_dir, int $quality = 50): array
{
    // ---- 1. Cek ada file yang diupload ----
    if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Tidak ada file yang diupload.'];
    }
 
    // ---- 2. Validasi tipe file ----
    $allowed_mime = ['image/jpeg', 'image/png', 'image/webp'];
    $finfo        = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type    = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
 
    if (!in_array($mime_type, $allowed_mime)) {
        return ['success' => false, 'error' => 'Tipe file tidak didukung. Hanya JPG, PNG, dan WebP.'];
    }
 
    // ---- 3. Validasi ukuran max 2MB ----
    $max_size = 2 * 1024 * 1024; // 2MB dalam bytes
    if ($file['size'] > $max_size) {
        return ['success' => false, 'error' => 'Ukuran file maksimal 2MB.'];
    }
 
    // ---- 4. Buat folder jika belum ada ----
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
 
    // ---- 5. Generate nama file unik ----
    $extension = match($mime_type) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    };
    $filename  = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $dest_path = rtrim($upload_dir, '/') . '/' . $filename;
 
    // ---- 6. Load gambar dari tmp ----
    $image = match($mime_type) {
        'image/jpeg' => imagecreatefromjpeg($file['tmp_name']),
        'image/png'  => imagecreatefrompng($file['tmp_name']),
        'image/webp' => imagecreatefromwebp($file['tmp_name']),
    };
 
    if (!$image) {
        return ['success' => false, 'error' => 'Gagal memproses gambar.'];
    }
 
    // ---- 7. Compress & simpan ----
    $saved = match($mime_type) {
        'image/jpeg' => imagejpeg($image, $dest_path, $quality),
        // PNG quality: skala 0-9, konversi dari 0-100
        'image/png'  => imagepng($image, $dest_path, (int) round((100 - $quality) / 11)),
        'image/webp' => imagewebp($image, $dest_path, $quality),
    };
 
    imagedestroy($image);
 
    if (!$saved) {
        return ['success' => false, 'error' => 'Gagal menyimpan gambar.'];
    }
 
    return [
        'success' => true,
        'path'    => $dest_path,
        'error'   => '',
    ];
}
