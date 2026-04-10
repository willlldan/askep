-- Tabel identitas lansia gerontik
CREATE TABLE tbl_gerontik_identitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    tempat_lahir VARCHAR(100) NOT NULL,
    tgl_lahir DATE NOT NULL,
    jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
    status_perkawinan VARCHAR(50) NOT NULL,
    agama VARCHAR(50) NOT NULL,
    pendidikan VARCHAR(50) NOT NULL,
    pekerjaan_sekarang VARCHAR(100),
    pekerjaan_sebelumnya VARCHAR(100),
    tgl_pengkajian DATE NOT NULL,
    alamat VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    created_by VARCHAR(100) NOT NULL,
    updated_at DATETIME NOT NULL,
    updated_by VARCHAR(100) NOT NULL
);