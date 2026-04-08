<?php
if (isset($_GET['no_dokumen'])) {
    require_once "koneksi.php";

    // Membuat koneksi ke database
    $mysqli = new mysqli("localhost","root","","arsip");

    // Memeriksa koneksi berhasil atau tidak
    if ($mysqli->connect_error) {
        die("Koneksi ke database gagal: " . $mysqli->connect_error);
    }

    // Menyiapkan nilai no_dokumen untuk digunakan dalam query
    $no_dokumen = $_GET['no_dokumen'];
    $no_dokumen = $mysqli->real_escape_string($no_dokumen); // Melakukan sanitasi nilai

    // Membuat pernyataan SQL untuk menghapus data
    $sql = "DELETE FROM tbl_dok_masuk WHERE no_dokumen='$no_dokumen'";

    // Menjalankan query
    if ($mysqli->query($sql)) {
        echo "<script>alert('Dokumen Masuk berhasil dihapus.')</script>";
        echo "<script>window.location.href='index.php?page=dokumen_masuk&item=tampil_dokumen_masuk';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

    // Menutup koneksi
    $mysqli->close();
} else {
    echo "<script>window.location.href='index.php?page=dokumen_masuk&item=tampil_dokumen_masuk';</script>";
}
?>
