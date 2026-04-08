CREATE DATABASE `askep`;
USE `askep`;

CREATE TABLE user (
    id_user INT NOT NULL AUTO_INCREMENT,
    nama VARCHAR(50) NOT NULL,
    username VARCHAR(15) NOT NULL,
    password VARCHAR(10) NOT NULL,
    level ENUM ('Admin', 'Staff-Dokter') NOT NULL,
    PRIMARY KEY (id_user)
);

INSERT INTO `user` (`id_user`,`nama`,`username`,`password`,`level`) VALUES 
(null, 'admin', 'admin', 'admin', 'Admin');

CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    nim VARCHAR(20),
    kelompok TEXT,
    tempat_dinas VARCHAR(100),
    jenis_gadar ENUM('icu','igd'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE icu_laporanpendahuluan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    pengertian TEXT,
    comment_pengertian TEXT,

    klasifikasi TEXT,
    comment_klasifikasi TEXT,

    etiologi TEXT,
    comment_etiologi TEXT,

    patofisiologi TEXT,
    comment_patofisiologi TEXT,

    manifestasiklinik TEXT,
    comment_manifestasi_klinik TEXT,

    pemeriksaandiagnostik TEXT,
    comment_pemeriksaan_diagnostik TEXT,

    penatalaksanaan TEXT,
    comment_penatalaksanaan TEXT,

    komplikasi TEXT,
    comment_komplikasi TEXT,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_pengkajian_umum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    nama TEXT,
    comment_nama TEXT,

    umur TEXT,
    comment_umur TEXT,

    jeniskelamin TEXT,
    comment_jeniskelamin TEXT,

    pekerjaan TEXT,
    comment_pekerjaan TEXT,

    agama TEXT,
    comment_agama TEXT,

    tgl_mrs TEXT,
    comment_tgl_mrs TEXT,

    tgl_pengkajian TEXT,
    comment_tgl_pengkajian TEXT,

    noreg TEXT,
    comment_noreg TEXT,

    alamat TEXT,
    comment_alamat TEXT,

    dxmedis TEXT,
    comment_dxmedis TEXT,

    keluhanutama TEXT,
    comment_keluhanutama TEXT,

    riwayatkeluhanutama TEXT,
    comment_riwayatkeluhanutama TEXT,

    riwayat_alergi TEXT,
    comment_riwayat_alergi TEXT,

    keadaan_umum TEXT,
    comment_keadaan_umum TEXT,

    tekanan_darah VARCHAR(20),
    nadi VARCHAR(20),
    suhu VARCHAR(10),
    rr VARCHAR(20),
    comment_tanda_vital TEXT,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_pengkajian_primary (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    tanggal DATETIME,
    comment_tanggal TEXT,

    -- AIRWAY
    jalan_nafas VARCHAR(20),
    comment_jalan_nafas TEXT,

    ett VARCHAR(20),
    comment_ett TEXT,

    -- BREATHING
    pola_nafas VARCHAR(20),
    comment_pola_nafas TEXT,

    spo2 VARCHAR(10),
    comment_spo2 TEXT,

    ventilator TEXT,
    comment_ventilator TEXT,

    pernafasan_cuping_hidung VARCHAR(20),
    comment_pernafasan_cuping_hidung TEXT,

    suara_nafas_tambahan VARCHAR(20),
    comment_suara_nafas_tambahan TEXT,

    rektraksi_dinding_dada VARCHAR(20),
    comment_rektraksi_dinding_dada TEXT,

    otot_bantu VARCHAR(20),
    comment_otot_bantu TEXT,


-- Circulation
    nadi VARCHAR(20),
    comment_nadi TEXT,

    tekanandarah VARCHAR(10),
    comment_tekanan_darah TEXT,

    cvp TEXT,
    comment_cvp TEXT,

    crt VARCHAR(20),
    comment_crt TEXT,

    suara_jantung VARCHAR(20),
    comment_suara_jantung TEXT,

    perfusi_perifer VARCHAR(20),
    comment_perfusi_perifer TEXT,

   -- DISABILITY
    tingkat_kesadaran VARCHAR(50),
    comment_tingkat_kesadaran TEXT,

    gcs_e VARCHAR(5),
    gcs_m VARCHAR(5),
    gcs_v VARCHAR(5),
    comment_gcs TEXT,

    pupil VARCHAR(50),
    comment_pupil TEXT,

    respon_motorik VARCHAR(50),
    comment_respon_motorik TEXT,

    -- EXPOSURE
    suhu VARCHAR(20),
    comment_suhu TEXT,

    lainnya VARCHAR(10),
    comment_lainnya TEXT,

   -- FLUID
    infuse VARCHAR(20),
    comment_infuse TEXT,

    cairan VARCHAR(10),
    comment_cairan TEXT,

    jumlah TEXT,
    comment_jumlah TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_pengkajian_secondary_b1 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    -- HIDUNG
    hidung VARCHAR(50),
    comment_hidung TEXT,

    -- TRAKEA
    trakea VARCHAR(50),
    comment_trakea TEXT,

    -- NYERI
    nyeri VARCHAR(10),
    comment_nyeri TEXT,

    dypsnea VARCHAR(10),
    comment_dypsnea TEXT,

    cyanosis VARCHAR(10),
    comment_cyanosis TEXT,

    retraksi_dada VARCHAR(10),
    comment_retraksi_dada TEXT,

    batuk_darah VARCHAR(10),
    comment_batuk_darah TEXT,

    orthopnea VARCHAR(10),
    comment_orthopnea TEXT,

    napas_dangkal VARCHAR(10),
    comment_napas_dangkal TEXT,

    sputum VARCHAR(10),
    comment_sputum TEXT,

    trakeostomi VARCHAR(10),
    comment_trakeostomi TEXT,

    suara_tambahan_napas VARCHAR(50),
    comment_suara_tambahan_napas TEXT,

    bentuk_dada VARCHAR(20),
    lainnya_bentuk_dada TEXT,
    comment_bentuk_dada TEXT,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_pengkajian_secondary_b2 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    nyeri_dada VARCHAR(10),
    comment_nyeri_dada TEXT,

    pusing VARCHAR(10),
    comment_pusing TEXT,

    sakit_kepala VARCHAR(10),
    comment_sakit_kepala TEXT,

    palpitasi VARCHAR(10),
    comment_palpitasi TEXT,

    clubbing_finger VARCHAR(10),
    comment_clubbing_finger TEXT,

    suara_jantung VARCHAR(50),
    comment_suara_jantung TEXT,

    edema VARCHAR(50),
    lainnya_edema TEXT,
    sebutkan_edema TEXT,
    comment_edema TEXT,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_pengkajian_secondary_b3 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    -- KESADARAN
    kesadaran VARCHAR(50),
    comment_kesadaran TEXT,

    -- GCS
    e VARCHAR(5),
    m VARCHAR(5),
    v VARCHAR(5),
    comment_gcs TEXT,

    -- KEJANG
    kejang VARCHAR(20),
    comment_kejang TEXT,

    -- KEPALA & WAJAH
    kepala VARCHAR(50),
    comment_kepala TEXT,

    wajah VARCHAR(50),
    comment_wajah TEXT,

    -- MATA
    sklera VARCHAR(20),
    comment_sklera TEXT,

    konjunctiva VARCHAR(20),
    comment_konjunctiva TEXT,

    pupil VARCHAR(20),
    ukuran_pupil VARCHAR(20),
    comment_pupil TEXT,

    -- LEHER
    leher VARCHAR(50),
    comment_leher TEXT,

    -- REFLEKS
    refleks_tendon_normal VARCHAR(50),
    comment_refleks_tendon_normal TEXT,

    refleks_tidak_normal VARCHAR(50),
    comment_refleks_tidak_normal TEXT,

    -- PERSEPSI SENSORI
    pendengaran_kiri VARCHAR(20),
    pendengaran_kanan VARCHAR(20),
    comment_pendengaran TEXT,

    penciuman VARCHAR(20),
    comment_penciuman TEXT,

    pengecapan VARCHAR(20),
    comment_pengecapan TEXT,

    penglihatan_kiri VARCHAR(20),
    penglihatan_kanan VARCHAR(20),
    comment_penglihatan TEXT,

    alat_bantu TEXT,
    comment_alat_bantu TEXT,

    -- PERABAAN
    panas VARCHAR(20),
    dingin VARCHAR(20),
    tekan VARCHAR(20),
    comment_perabaan TEXT,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_pengkajian_secondary_b4 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    -- PRODUKSI URINE
    produksiurine VARCHAR(50),
    comment_produksi_urine TEXT,

    -- FREKUENSI
    frekuensi VARCHAR(20),
    comment_frekuensi TEXT,

    -- WARNA
    warna VARCHAR(50),
    comment_warna TEXT,

    -- BAU
    bau VARCHAR(20),
    comment_bau TEXT,

    -- DOUWER CATETER
    douwercateter VARCHAR(50),
    comment_douwercateter TEXT,

    harike_cateter VARCHAR (50),
    comment_harike_cateter TEXT,

    -- Spolling Blass
    spolling_blass VARCHAR(50),
    comment_spolling_blas TEXT,

    -- KELAINAN DALAM URINE
    kelainan_dalam_urine VARCHAR(20),
    comment_kelainan_dalam_urine TEXT,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_pengkajian_secondary_b5 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    -- MULUT DAN TENGGOROKAN
    mulut_dan_tenggorokan VARCHAR(50),
    comment_kesadaran TEXT,

    -- GCS
    e VARCHAR(5),
    m VARCHAR(5),
    v VARCHAR(5),
    comment_gcs TEXT,

    -- KEJANG
    kejang VARCHAR(20),
    comment_kejang TEXT,

    -- KEPALA & WAJAH
    kepala VARCHAR(50),
    comment_kepala TEXT,

    wajah VARCHAR(50),
    comment_wajah TEXT,

    -- MATA
    sklera VARCHAR(20),
    comment_sklera TEXT,

    konjunctiva VARCHAR(20),
    comment_konjunctiva TEXT,

    pupil VARCHAR(20),
    ukuran_pupil VARCHAR(20),
    comment_pupil TEXT,

    -- LEHER
    leher VARCHAR(50),
    comment_leher TEXT,

    -- REFLEKS
    refleks_tendon_normal VARCHAR(50),
    comment_refleks_tendon_normal TEXT,

    refleks_tidak_normal VARCHAR(50),
    comment_refleks_tidak_normal TEXT,

    -- PERSEPSI SENSORI
    pendengaran_kiri VARCHAR(20),
    pendengaran_kanan VARCHAR(20),
    comment_pendengaran TEXT,

    penciuman VARCHAR(20),
    comment_penciuman TEXT,

    pengecapan VARCHAR(20),
    comment_pengecapan TEXT,

    penglihatan_kiri VARCHAR(20),
    penglihatan_kanan VARCHAR(20),
    comment_penglihatan TEXT,

    alat_bantu TEXT,
    comment_alat_bantu TEXT,

    -- PERABAAN
    panas VARCHAR(20),
    dingin VARCHAR(20),
    tekan VARCHAR(20),
    comment_perabaan TEXT,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_pengkajian_secondary_b6 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    -- B6: TULANG - OTOT - INTEGUMEN
    kelainan_tulang TEXT,
    comment_kelainan_tulang TEXT,

    kekuatan_otot TEXT,
    comment_kekuatan_otot TEXT,

    hemiparese VARCHAR(20),
    comment_hemiparese TEXT,

    tetraparese VARCHAR(20),
    comment_tetraparese TEXT,

    rom TEXT,
    comment_rom TEXT,

    lainnya TEXT,
    comment_lainnya TEXT,

    ekstremitas_atas VARCHAR(50),
    ekstremitas_bawah VARCHAR(50),

    tulang_belakang VARCHAR(50),

    warna_kulit VARCHAR(50),
    akral VARCHAR(50),

    turgor VARCHAR(20),
    comment_turgor TEXT,

    -- G: SISTEM ENDOKRIN
    terapi_hormon TEXT,
    comment_terapi_hormon TEXT,

    -- H: SISTEM REPRODUKSI
    sistem_reproduksi VARCHAR(20),
    kelainan_bentuk VARCHAR(20),
    kebersihan VARCHAR(20),

    -- I: POLA AKTIVITAS (MAKAN)
    makan_frekuensi TEXT,
    comment_makan_frekuensi TEXT,

    makan_jenis_menu TEXT,
    comment_makan_jenis_menu TEXT,

    makan_pantangan TEXT,
    comment_makan_pantangan TEXT,

    makan_alergi TEXT,
    comment_makan_alergi TEXT,

    -- MINUM
    minum_frekuensi TEXT,
    comment_minum_frekuensi TEXT,

    minum_jenis_menu TEXT,
    comment_minum_jenis_menu TEXT,

    minum_pantangan TEXT,
    comment_minum_pantangan TEXT,

    minum_alergi TEXT,
    comment_minum_alergi TEXT,

    -- KEBERSIHAN DIRI
    mandi TEXT,
    comment_mandi TEXT,

    keramas TEXT,
    comment_keramas TEXT,

    sikat_gigi TEXT,
    comment_sikat_gigi TEXT,

    memotong_kuku TEXT,
    comment_memotong_kuku TEXT,

    ganti_pakaian TEXT,
    comment_ganti_pakaian TEXT,

    masalah_lain_kebersihan TEXT,
    comment_masalah_lain_kebersihan TEXT,

    -- J: INTERAKSI SOSIAL
    dukungan_keluarga VARCHAR(20),
    dukungan_kelompok VARCHAR(20),

    hubungan_klien TEXT,
    comment_hubungan_klien TEXT,

    menunggu_klien TEXT,
    comment_menunggu_klien TEXT,

    -- K: PEMERIKSAAN PENUNJANG
    tgl_laboratorium DATE,
    comment_laboratorium TEXT,

    pemeriksaan TEXT,
    comment_pemeriksaan TEXT,

    hasil TEXT,
    comment_hasil TEXT,

    satuan TEXT,
    comment_satuan TEXT,

    nilai_rujukan TEXT,
    comment_nilai_rujukan TEXT,

    tgl_radiologi DATE,
    hasil_radiologi TEXT,
    comment_radiologi TEXT,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

    -- PENGOBATAN
    nama_obat VARCHAR(100),
    comment_nama_obat TEXT,

    dosis VARCHAR(100),
    comment_dosis TEXT,

    rute_pemberian VARCHAR(100),
    comment_rute_pemberian TEXT,

    pemberian_per_hari TEXT,
    comment_pemberian_per_hari TEXT,

CREATE TABLE icu_klasifikasi_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    datasubjektif TEXT,
    comment_datasubjektif TEXT,

    dataobjektif TEXT,
    comment_dataobjektif TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_analisa_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    dsdo TEXT,
    comment_dsdo TEXT,

    etiologi TEXT,
    comment_etiologi TEXT,

    masalah TEXT,
    comment_masalah TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_diagnosa_keperawatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    diagnosa TEXT,
    comment_diagnosa TEXT,

    tgl_ditemukan DATETIME,
    comment_tgl_ditemukan TEXT,

    tgl_teratasi DATETIME,
    comment_tgl_teratasi TEXT,

    paraf VARCHAR(5),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_rencana_keperawatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    diagnosa TEXT,
    comment_diagnosa TEXT,

    tujuandankriteria TEXT,
    comment_tujuandankriteria TEXT,

    intervensi TEXT,
    comment_intervensi TEXT,

    paraf VARCHAR,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_implementasi_keperawatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    implementasi TEXT,
    comment_implementasi TEXT,

    hari_tgl DATETIME,
    comment_hari_tgl TEXT,

    jam TIMESTAMP,
    comment_jam TEXT,

    --- Implementasi

    implementasi TEXT,
    comment_implementasi TEXT,

    hasil TEXT,
    comment_hasil TEXT,

    paraf VARCHAR,
   
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE icu_evaluasi_keperawatan (
    id INT AUTO_INCREMENT PRIMARY KEY,

    no_dx VARCHAR(50),
    comment_no_dx TEXT,

    hari_tgl DATETIME,
    comment_hari_tgl TEXT,

    jam TIME,
    comment_jam TEXT,

    evaluasi_s TEXT,
    evaluasi_o TEXT,
    evaluasi_a TEXT,
    evaluasi_p TEXT,

    commentevaluasi TEXT,

    paraf VARCHAR(10),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE icu_pustaka (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT,

    daftar_pustaka TEXT,
    comment_pustaka TEXT,

    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

