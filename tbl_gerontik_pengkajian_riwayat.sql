-- Tabel untuk menyimpan riwayat pengkajian gerontik
CREATE TABLE tbl_gerontik_pengkajian_riwayat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_identitas INT NOT NULL,
    keluhan_utama TEXT,
    riwayat_kesehatan_sekarang TEXT,
    berkualitas VARCHAR(10),
    sehat VARCHAR(10),
    aktif VARCHAR(10),
    produktif VARCHAR(10),
    sakit_perawatan VARCHAR(10),
    sakit_tanpa_perawatan VARCHAR(10),
    imunisasi VARCHAR(255),
    alergi_obat VARCHAR(255),
    kecelakaan VARCHAR(255),
    merokok VARCHAR(255),
    dirawat_rs VARCHAR(255),
    penyakit_1_tahun VARCHAR(255),
    obat_2_minggu VARCHAR(255),
    teratur_konsumsi VARCHAR(10),
    resep_dokter VARCHAR(10),
    genogram VARCHAR(255),
    G1 TEXT,
    G2 TEXT,
    G3 TEXT,
    created_at DATETIME,
    created_by VARCHAR(100),
    
    CONSTRAINT fk_identitas FOREIGN KEY (id_identitas)
        REFERENCES tbl_gerontik_identitas(id)
        ON DELETE CASCADE
);