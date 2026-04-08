CREATE DATABASE `arsip`;
USE `arsip`;

CREATE TABLE `tbl_label_arsip` (
    id_label_arsip  INT NOT NULL AUTO_INCREMENT,
    jenis_dokumen ENUM('Dokumen Masuk','Dokumen Keluar','Dokumen Pendukung', 'Dokumen Personel') NOT NULL,
    label_arsip VARCHAR(20) NOT NULL,
    tanggal_dokumen DATE NOT NULL,
    no_urut_dokumen VARCHAR (3) NOT NULL,
    PRIMARY KEY (id_label_arsip)
);

CREATE TABLE `tbl_user` (
    id_user INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(50) NOT NULL,
    username VARCHAR(15) NOT NULL,
    password VARCHAR(10) NOT NULL,
    level ENUM ('Admin', 'Staff-Dokter') NOT NULL,
    PRIMARY KEY (id_user)
);

INSERT INTO `tbl_user` (`id_user`,`nama`,`username`,`password`,`level`) VALUES 
(null, 'admin', 'admin', 'admin', 'Admin');

CREATE TABLE `tbl_dok_masuk` (
    no_dokumen VARCHAR(20) NOT NULL,
    status_tindakan ENUM('Proses','Selesai','Arsip') NOT NULL,
    update_dokumen DATE NOT NULL,
    status_dokumen ENUM('Biasa','Penting','Rahasia') NOT NULL,
    tgl_masuk_dok DATE NOT NULL,
    tgl_terima_dok DATE NOT NULL,
    asal_dokumen VARCHAR (50) NOT NULL,
    perihal VARCHAR (50) NOT NULL,
    label_arsip VARCHAR(20) NOT NULL,
    rak_arsip VARCHAR(20) NOT NULL,
    tgl_pinjam DATETIME NOT NULL,
    peminjaman ENUM ('Tidak Dipinjam', 'Dipinjam-Kembali', 'Dipinjam-Tidak Kembali') NOT NULL,
    tgl_kembali DATETIME NOT NULL,
    keterangan TEXT(200) NOT NULL,
    file BLOB,
    PRIMARY KEY (no_dokumen)
);

CREATE TABLE `tbl_dok_keluar` (
    no_dokumen VARCHAR(20) NOT NULL,
    update_dokumen DATE NOT NULL,
    status_dokumen ENUM('Biasa','Penting','Rahasia') NOT NULL,
    tgl_keluar_dok DATE NOT NULL,
    perihal VARCHAR (50) NOT NULL,
    tujuan VARCHAR (50) NOT NULL,
    label_arsip VARCHAR(20) NOT NULL,
    rak_arsip VARCHAR(20) NOT NULL,
    tgl_pinjam DATETIME NOT NULL,
    peminjaman ENUM ('Tidak Dipinjam', 'Dipinjam-Kembali', 'Dipinjam-Tidak Kembali') NOT NULL,
    tgl_kembali DATETIME NOT NULL,
    keterangan TEXT(200) NOT NULL,
    file BLOB,
    PRIMARY KEY (no_dokumen)
);

CREATE TABLE `tbl_dok_pendukung` (
    no_dokumen VARCHAR(20) NOT NULL,
    update_dokumen DATE NOT NULL,
    status_dokumen ENUM('Biasa','Penting','Rahasia') NOT NULL,
    tgl_masuk_dok DATE NOT NULL,
    tgl_keluar_dok DATE NOT NULL,
    perihal VARCHAR (50) NOT NULL,
    tujuan VARCHAR (50) NOT NULL,
    asal_dokumen VARCHAR (50) NOT NULL,
    label_arsip VARCHAR(20) NOT NULL,
    rak_arsip VARCHAR(20) NOT NULL,
    tgl_pinjam DATETIME NOT NULL,
    peminjaman ENUM ('Tidak Dipinjam','Dipinjam-Kembali', 'Dipinjam-Tidak Kembali') NOT NULL,
    tgl_kembali DATETIME NOT NULL,
    keterangan TEXT(200) NOT NULL,
    file BLOB,
    PRIMARY KEY (no_dokumen)
);

CREATE TABLE `tbl_dok_personel` (
    id_dokumen_personel INT NOT NULL AUTO_INCREMENT,
    update_dokumen DATE NOT NULL,
    nrp_nip VARCHAR(20) NOT NULL,
    nama VARCHAR (50) NOT NULL,
    pangkat_golongan VARCHAR (50) NOT NULL,
    kesatuan VARCHAR (50) NOT NULL,
    tempat_lahir VARCHAR (50) NOT NULL,
    tgl_lahir DATE NOT NULL,
    jenis_kelamin ENUM ('Laki-laki','Perempuan') NOT NULL,
    agama VARCHAR (30) NOT NULL, 
    alamat_rumah VARCHAR (50) NOT NULL,
    data_keluarga TEXT (200) NOT NULL,
    hasil_urikes TEXT (200) NOT NULL,
    hasil_samapta TEXT (200) NOT NULL,
    tmt_pangkat_pertama DATE NOT NULL,
    tmt_pangkat_kedua DATE NOT NULL,
    tmt_masuk_satuan DATE NOT NULL,
    no_ktp VARCHAR (16) NOT NULL,
    no_bpjs VARCHAR (13) NOT NULL,
    no_npwp VARCHAR (15) NOT NULL,
    pendidikan_terakhir TEXT (200) NOT NULL,
    riwayat_jabatan TEXT (200) NOT NULL,
    tanda_kehormatan TEXT (200) NOT NULL,
    pendidikan_umum TEXT (200) NOT NULL,
    pendidikan_militer TEXT (200) NOT NULL,
    pelatihan_khusus TEXT (200) NOT NULL,
    label_arsip VARCHAR(20) NOT NULL,
    rak_arsip VARCHAR(20) NOT NULL,
    keterangan TEXT(200) NOT NULL,
    file BLOB,
    PRIMARY KEY (id_dokumen_personel)
);

CREATE TABLE tbl_disposisi (
    id_disposisi INT AUTO_INCREMENT PRIMARY KEY,
    no_agenda VARCHAR(20) NOT NULL,
    no_dokumen VARCHAR(50) NOT NULL,
    asal_dokumen VARCHAR(50) NOT NULL,
    perihal VARCHAR(50) NOT NULL,
    tgl_masuk DATE NOT NULL,
    tgl_disposisi DATE NOT NULL,
    posisi VARCHAR (50) NOT NULL,
    keterangan TEXT
);

CREATE TABLE tbl_histori_disposisi (
    id_histori INT AUTO_INCREMENT PRIMARY KEY,
    id_disposisi INT,
    tgl_update DATETIME,
    tgl_disposisi DATE,
    posisi VARCHAR (50),
    FOREIGN KEY (id_disposisi) REFERENCES tbl_disposisi(id_disposisi)
);


CREATE TABLE `tbl_peminjaman_dokumen` (
    id_peminjaman_dokumen INT NOT NULL AUTO_INCREMENT,
    no_dokumen VARCHAR(20) NOT NULL,
    status_peminjam ENUM ('Peminjam Internal', 'Peminjam Eksternal') NOT NULL,
    nama_peminjam VARCHAR(50) NOT NULL,
    tgl_pinjam DATETIME NOT NULL,
    tgl_kembali DATETIME NOT NULL,
    keterangan TEXT(200) NOT NULL,
    PRIMARY KEY (id_peminjaman_dokumen)
);

CREATE TABLE `tbl_peminjaman_dok_personel` (
    id_peminjaman_dok_personel INT NOT NULL AUTO_INCREMENT,
    nrp_nip VARCHAR(20) NOT NULL,
    status_peminjam ENUM ('Peminjam Internal', 'Peminjam Eksternal') NOT NULL,
    nama_peminjam VARCHAR(50) NOT NULL,
    tgl_pinjam DATETIME NOT NULL,
    tgl_kembali DATETIME NOT NULL,
    status_peminjaman ENUM ('Dipinjam-Kembali', 'Dipinjam-Tidak Kembali') NOT NULL,
    keterangan TEXT(200) NOT NULL,
    PRIMARY KEY (id_peminjaman_dok_personel)
);
