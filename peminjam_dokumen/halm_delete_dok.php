<?php
if (isset($_GET['id_peminjaman_dokumen'])) {
    require_once "koneksi.php";

    // Membuat koneksi ke database
    $mysqli = new mysqli("localhost","root","","arsip");

    // Memeriksa koneksi berhasil atau tidak
    if ($mysqli->connect_error) {
        die("Koneksi ke database gagal: " . $mysqli->connect_error);
    }

    // Menyiapkan nilai no_dokumen untuk digunakan dalam query
    $id_peminjaman_dokumen = $_GET['id_peminjaman_dokumen'];
    $id_peminjaman_dokumen = $mysqli->real_escape_string($id_peminjaman_dokumen); // Melakukan sanitasi nilai

    // Membuat pernyataan SQL untuk menghapus data
    $sql = "DELETE FROM tbl_peminjaman_dokumen WHERE id_peminjaman_dokumen='$id_peminjaman_dokumen'";

    // Menjalankan query
    if ($mysqli->query($sql)) {
        echo "<script>alert('Peminjaman Dokumen berhasil dihapus.')</script>";
        echo "<script>window.location.href='index.php?page=peminjam_dokumen&item=tampil_dok';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

    // Menutup koneksi
    $mysqli->close();
} else {
    echo "<script>window.location.href='index.php?page=peminjam_dokumen&item=tampil_dok';</script>";
}
?>
