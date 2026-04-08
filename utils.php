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
    };

   // $count = $dataPinjaman['count'] + $dataPinjamanPersonel['count'];

   // $result['count'] = $count;
   // $result['data'] = $data;

    return $result;


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
