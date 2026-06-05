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
               rd.nama as dosen_name,
               rp.nama as preceptor_name
        FROM submissions s
        LEFT JOIN tbl_user rd ON s.dosen_reviewed_by = rd.id_user
        LEFT JOIN tbl_user rp ON s.preceptor_reviewed_by = rp.id_user
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
    $state = getSectionReviewState($submission_id, $section_name, $mysqli);
    return $state ? $state['status'] : null;
}

/**
 * Ambil status section lengkap termasuk status reviewer per role.
 */
function getSectionReviewState($submission_id, $section_name, $mysqli)
{
    $stmt = $mysqli->prepare("
        SELECT status, dosen_review_status, preceptor_review_status
        FROM submission_sections
        WHERE submission_id = ? AND section_name = ?
    ");
    $stmt->bind_param("is", $submission_id, $section_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $section = $result->fetch_assoc();

    if (!$section) {
        return null;
    }

    $section['status'] = getSectionAggregateStatus(
        $section['dosen_review_status'] ?? null,
        $section['preceptor_review_status'] ?? null,
        $section['status']
    );

    return $section;
}

/**
 * Gabungkan status reviewer per role menjadi status section overall.
 */
function getSectionAggregateStatus($dosen_status, $preceptor_status, $fallback = 'draft')
{
    $statuses = [$dosen_status, $preceptor_status];

    if (in_array('revision', $statuses, true)) {
        return 'revision';
    }

    if ($dosen_status === 'approved' && $preceptor_status === 'approved') {
        return 'approved';
    }

    if (in_array('submitted', $statuses, true) || in_array('approved', $statuses, true)) {
        return 'submitted';
    }

    return $fallback ?: 'draft';
}

/**
 * Insert atau update data section
 */
function saveSection($submission_id, $section_name, $section_label, $data, $mysqli)
{
    $json_data = json_encode($data);
    $stmt = $mysqli->prepare("
        INSERT INTO submission_sections (submission_id, section_name, section_label, data, status, dosen_review_status, preceptor_review_status)
        VALUES (?, ?, ?, ?, 'draft', 'draft', 'draft')
        ON DUPLICATE KEY UPDATE 
            data = VALUES(data), 
            status = 'draft',
            dosen_review_status = 'draft',
            preceptor_review_status = 'draft',
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
 * Ambil submission lengkap berdasarkan id.
 */
function getSubmissionById($submission_id, $mysqli)
{
    $stmt = $mysqli->prepare("
        SELECT s.*, u.nama as mahasiswa_name, u.npm as mahasiswa_npm, f.slug, f.department, f.form_name,
               rd.nama as dosen_name,
               rp.nama as preceptor_name
        FROM submissions s
        JOIN tbl_user u ON s.user_id = u.id_user
        JOIN forms f ON s.form_id = f.id
        LEFT JOIN tbl_user rd ON s.dosen_reviewed_by = rd.id_user
        LEFT JOIN tbl_user rp ON s.preceptor_reviewed_by = rp.id_user
        WHERE s.id = ?
    ");
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Ubah department/slug form menjadi route page yang dipakai index.php.
 */
function buildFormPageRoute($department, $slug)
{
    if (strtolower($department) === 'gerontik') {
        return 'askep_gerontik';
    }

    return strtolower($department) . '/' . $slug;
}

/**
 * Buat notification baru untuk user tertentu.
 */
function createNotification($recipient_id, $actor_id, $submission_id, $type, $message, $target_url, $mysqli)
{
    $stmt = $mysqli->prepare("
        INSERT INTO user_notifications
            (recipient_id, actor_id, submission_id, type, message, target_url, is_read, created_at)
        VALUES (?, ?, ?, ?, ?, ?, 0, NOW())
    ");
    $stmt->bind_param("iiisss", $recipient_id, $actor_id, $submission_id, $type, $message, $target_url);
    $stmt->execute();
}

/**
 * Ambil notifikasi terbaru untuk navbar.
 */
function getUserNotifications($recipient_id, $mysqli, $limit = 5)
{
    $limit = (int) $limit;
    $stmt = $mysqli->prepare("
        SELECT id, type, message, target_url, created_at, is_read
        FROM user_notifications
        WHERE recipient_id = ?
        ORDER BY is_read ASC, created_at DESC, id DESC
        LIMIT ?
    ");
    $stmt->bind_param("ii", $recipient_id, $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Hitung notifikasi belum dibaca.
 */
function countUnreadNotifications($recipient_id, $mysqli)
{
    $stmt = $mysqli->prepare("
        SELECT COUNT(*) as total
        FROM user_notifications
        WHERE recipient_id = ? AND is_read = 0
    ");
    $stmt->bind_param("i", $recipient_id);
    $stmt->execute();
    return (int) ($stmt->get_result()->fetch_assoc()['total'] ?? 0);
}

/**
 * Tandai satu notifikasi sebagai sudah dibaca.
 */
function markNotificationRead($notification_id, $recipient_id, $mysqli)
{
    $stmt = $mysqli->prepare("
        UPDATE user_notifications
        SET is_read = 1, read_at = NOW()
        WHERE id = ? AND recipient_id = ?
    ");
    $stmt->bind_param("ii", $notification_id, $recipient_id);
    $stmt->execute();
}

/**
 * Tandai semua notifikasi user sebagai sudah dibaca.
 */
function markAllNotificationsRead($recipient_id, $mysqli)
{
    $stmt = $mysqli->prepare("
        UPDATE user_notifications
        SET is_read = 1, read_at = NOW()
        WHERE recipient_id = ? AND is_read = 0
    ");
    $stmt->bind_param("i", $recipient_id);
    $stmt->execute();
}

/**
 * Render badge status standar.
 */
function renderStatusBadge($status, $emptyLabel = 'Belum Diisi')
{
    $statusMap = [
        'draft' => ['label' => 'Draft', 'class' => 'secondary'],
        'submitted' => ['label' => 'Submitted', 'class' => 'primary'],
        'revision' => ['label' => 'Revision', 'class' => 'warning'],
        'approved' => ['label' => 'Approved', 'class' => 'success'],
    ];

    if ($status && isset($statusMap[$status])) {
        $s = $statusMap[$status];
        return "<span class='badge bg-{$s['class']}'>{$s['label']}</span>";
    }

    return "<span class='badge bg-light text-dark border'>" . htmlspecialchars($emptyLabel) . "</span>";
}

/**
 * Update tanggal_pengkajian & rs_ruangan di submissions
 * Dipanggil saat mahasiswa update section 1
 */
function updateSubmissionHeader($submission_id, $tanggal_pengkajian, $rs_ruangan, $mysqli)
{
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
    $submission = getSubmissionById($submission_id, $mysqli);

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
        SET status = 'submitted', submitted_at = NOW(), dosen_review_status = 'submitted', preceptor_review_status = 'submitted' 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $submission_id);
    $stmt->execute();

    $stmtSection = $mysqli->prepare("
        UPDATE submission_sections
        SET status = 'submitted', dosen_review_status = 'submitted', preceptor_review_status = 'submitted', updated_at = NOW()
        WHERE submission_id = ?
    ");
    $stmtSection->bind_param("i", $submission_id);
    $stmtSection->execute();

    if ($submission) {
        $reviewerIds = [];
        foreach (['dosen_reviewed_by', 'preceptor_reviewed_by'] as $field) {
            if (!empty($submission[$field])) {
                $reviewerIds[] = (int) $submission[$field];
            }
        }
        $reviewerIds = array_values(array_unique($reviewerIds));

        if (!empty($reviewerIds)) {
            $target_url = 'index.php?page=dashboard/detail_mahasiswa&id=' . (int) $submission['user_id'];
            $message = 'Submission ' . $submission['mahasiswa_name'] . ' disubmit ulang oleh mahasiswa.';
            foreach ($reviewerIds as $reviewerId) {
                createNotification($reviewerId, (int) $submission['user_id'], $submission_id, 'resubmitted', $message, $target_url, $mysqli);
            }
        }
    }

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
function updateSectionStatus($submission_id, $section_name, $status, $mysqli, $reviewer_role = 'Dosen')
{
    $reviewer_role = in_array($reviewer_role, ['Dosen', 'Preceptor'], true) ? $reviewer_role : 'Dosen';

    if ($reviewer_role === 'Preceptor') {
        $stmt = $mysqli->prepare("
            UPDATE submission_sections 
            SET preceptor_review_status = ?, updated_at = NOW()
            WHERE submission_id = ? AND section_name = ?
        ");
    } else {
        $stmt = $mysqli->prepare("
            UPDATE submission_sections 
            SET dosen_review_status = ?, updated_at = NOW()
            WHERE submission_id = ? AND section_name = ?
        ");
    }
    $stmt->bind_param("sis", $status, $submission_id, $section_name);
    $stmt->execute();

    $stmtState = $mysqli->prepare("
        SELECT dosen_review_status, preceptor_review_status
        FROM submission_sections
        WHERE submission_id = ? AND section_name = ?
    ");
    $stmtState->bind_param("is", $submission_id, $section_name);
    $stmtState->execute();
    $state = $stmtState->get_result()->fetch_assoc();

    $aggregateStatus = getSectionAggregateStatus(
        $state['dosen_review_status'] ?? null,
        $state['preceptor_review_status'] ?? null,
        'draft'
    );

    $stmtAgg = $mysqli->prepare("
        UPDATE submission_sections 
        SET status = ?, updated_at = NOW()
        WHERE submission_id = ? AND section_name = ?
    ");
    $stmtAgg->bind_param("sis", $aggregateStatus, $submission_id, $section_name);
    $stmtAgg->execute();

    // if($status === 'cancel_approval') {
    //     $new_status = 'draft';
    //     $stmt3 = $mysqli->prepare("UPDATE submissions SET status = ? WHERE id = ?");
    //     $stmt3->bind_param("si", $new_status, $submission_id);
    //     $stmt3->execute();
    // }
}

/**
 * Update reviewer pada submissions.
 * reviewed_by / reviewed_at tetap dipakai sebagai reviewer terakhir.
 */
function updateReviewer($submission_id, $reviewer_id, $mysqli, $reviewer_role = 'Dosen', $review_action = null)
{
    if ($review_action === null) {
        $review_action = $_POST['action'] ?? null;
    }

    $review_status = null;
    if ($review_action === 'approve') {
        $review_status = 'approved';
    } elseif ($review_action === 'revision') {
        $review_status = 'revision';
    } elseif ($review_action === 'cancel_approval') {
        $review_status = 'draft';
    }

    if ($reviewer_role === 'Preceptor') {
        if ($review_status !== null) {
            $stmt = $mysqli->prepare("
                UPDATE submissions 
                SET reviewed_by = ?, reviewed_at = NOW(), preceptor_reviewed_by = ?, preceptor_reviewed_at = NOW(), preceptor_review_status = ?
                WHERE id = ?
            ");
            $stmt->bind_param("iisi", $reviewer_id, $reviewer_id, $review_status, $submission_id);
        } else {
            $stmt = $mysqli->prepare("
                UPDATE submissions 
                SET reviewed_by = ?, reviewed_at = NOW(), preceptor_reviewed_by = ?, preceptor_reviewed_at = NOW()
                WHERE id = ?
            ");
            $stmt->bind_param("iii", $reviewer_id, $reviewer_id, $submission_id);
        }
    } else {
        if ($review_status !== null) {
            $stmt = $mysqli->prepare("
                UPDATE submissions 
                SET reviewed_by = ?, reviewed_at = NOW(), dosen_reviewed_by = ?, dosen_reviewed_at = NOW(), dosen_review_status = ?
                WHERE id = ?
            ");
            $stmt->bind_param("iisi", $reviewer_id, $reviewer_id, $review_status, $submission_id);
        } else {
            $stmt = $mysqli->prepare("
                UPDATE submissions 
                SET reviewed_by = ?, reviewed_at = NOW(), dosen_reviewed_by = ?, dosen_reviewed_at = NOW()
                WHERE id = ?
            ");
            $stmt->bind_param("iii", $reviewer_id, $reviewer_id, $submission_id);
        }
    }
    $stmt->execute();

    if (in_array($review_status, ['approved', 'revision'], true)) {
        $submission = getSubmissionById($submission_id, $mysqli);
        if ($submission) {
            $route = buildFormPageRoute($submission['department'], $submission['slug']);
            $target_url = 'index.php?page=' . $route . '&submission_id=' . (int) $submission_id;
            $statusLabel = $review_status === 'approved' ? 'disetujui' : 'direvisi';
            $message = $submission['form_name'] . ' milik Anda telah ' . $statusLabel . ' oleh ' . strtolower($reviewer_role) . '.';
            createNotification((int) $submission['user_id'], (int) $reviewer_id, $submission_id, $review_status, $message, $target_url, $mysqli);
        }
    }
}

/**
 * Update status submission setelah reviewer action.
 * Status overall mengikuti gabungan status reviewer dosen & preceptor.
 */
function updateSubmissionStatusByDosen($submission_id, $form_id, $mysqli)
{
    $submission = getSubmissionById($submission_id, $mysqli);

    $dosenStatus = $submission['dosen_review_status'] ?? null;
    $preceptorStatus = $submission['preceptor_review_status'] ?? null;

    if (in_array('revision', [$dosenStatus, $preceptorStatus], true)) {
        $new_status = 'revision';
    } elseif ($dosenStatus === 'approved' && $preceptorStatus === 'approved') {
        $new_status = 'approved';
    } elseif (in_array('submitted', [$dosenStatus, $preceptorStatus], true) || in_array('approved', [$dosenStatus, $preceptorStatus], true)) {
        $new_status = 'submitted';
    } else {
        $new_status = 'draft';
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
