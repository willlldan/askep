<?php
if (isset($_GET['id_peminjaman_dok_personel'])) {
    require_once "koneksi.php";

    // Membuat koneksi ke database
    $mysqli = new mysqli("localhost","root","","arsip");

    // Memeriksa koneksi berhasil atau tidak
    if ($mysqli->connect_error) {
        die("Koneksi ke database gagal: " . $mysqli->connect_error);
    }

    // Menyiapkan nilai no_dokumen untuk digunakan dalam query
    $id_peminjaman_dok_personel = $_GET['id_peminjaman_dok_personel'];
    $id_peminjaman_dok_personel = $mysqli->real_escape_string($id_peminjaman_dok_personel); // Melakukan sanitasi nilai

    // Membuat pernyataan SQL untuk menghapus data
    $sql = "DELETE FROM tbl_peminjaman_dok_personel WHERE id_peminjaman_dok_personel='$id_peminjaman_dok_personel'";

    // Menjalankan query
    if ($mysqli->query($sql)) {
        echo "<script>alert('Peminjaman Dokumen Personel berhasil dihapus.')</script>";
        echo "<script>window.location.href='index.php?page=peminjam_dokumen_personel&item=tampil_dok_personel';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

    // Menutup koneksi
    $mysqli->close();
} else {
    echo "<script>window.location.href='index.php?page=peminjam_dokumen_personel&item=tampil_dok_personel';</script>";
}
?>
