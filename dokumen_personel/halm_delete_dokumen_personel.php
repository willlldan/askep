<?php
if (isset($_GET['id_dokumen_personel'])) {
    require_once "koneksi.php";

    // Membuat koneksi ke database
    $mysqli = new mysqli("localhost","root","","arsip");

    // Memeriksa koneksi berhasil atau tidak
    if ($mysqli->connect_error) {
        die("Koneksi ke database gagal: " . $mysqli->connect_error);
    }

    // Menyiapkan nilai no_dokumen untuk digunakan dalam query
    $id_dokumen_personel = $_GET['id_dokumen_personel'];
    $id_dokumen_personel = $mysqli->real_escape_string($id_dokumen_personel); // Melakukan sanitasi nilai

    // Membuat pernyataan SQL untuk menghapus data
    $sql = "DELETE FROM tbl_dok_personel WHERE id_dokumen_personel='$id_dokumen_personel'";

    // Menjalankan query
    if ($mysqli->query($sql)) {
        echo "<script>alert('Dokumen Personel berhasil dihapus.')</script>";
        echo "<script>window.location.href='index.php?page=dokumen_personel&item=tampil_dokumen_personel';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

    // Menutup koneksi
    $mysqli->close();
} else {
    echo "<script>window.location.href='index.php?page=dokumen_personel&item=tampil_dokumen_personel';</script>";
}
?>
