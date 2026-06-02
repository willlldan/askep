<?php
// Shortcut per section
$pengkajian          = $sections['pengkajian'] ?? [];
$pengkajianlanjutan2 = $sections['pengkajianlanjutan2'] ?? [];
$pengkajianlanjutan3 = $sections['pengkajianlanjutan3'] ?? [];
$klasifikasi         = $sections['klasifikasi'] ?? [];
$lainnya             = $sections['lainnya'] ?? [];

include 'template_pdf.php';
?>

<body>
    <div >

        <!-- HEADER -->
        <h1> FORMAT ASUHAN KEPERAWATAN KELUARGA <br> FORMAT PENGKAJIAN </h1>
        <br>
        
        <table class="header-table">
            <tr>
                <td width="25%"><strong>Nama Mahasiswa</strong></td>
                <td width="2%">:</td>
                <td width="23%"><?= p($submission['mahasiswa_name']) ?></td>
                <td width="25%"><strong>Tanggal Pengkajian</strong></td>
                <td width="2%">:</td>
                <td><?= p($submission['tanggal_pengkajian']) ?></td>
            </tr>
            
            <tr>
                <td><strong>NIM</strong></td>
                <td>:</td>
                <td><?= p($submission['npm']) ?></td>
                <td><strong>Puskesmas</strong></td>
                <td>:</td>
                <td><?= p($submission['rs_ruangan']) ?></td>
            </tr>
        </table>
        
        <br>

        <!-- ================================ -->
        <!-- SECTION 1: Pengkajian -->
        <!-- ================================ -->
        <h3 class="mt-5">A. Pengkajian</h3>
        <h4>I. Data Umum</h4>

        <div class="field-row">
            <div class="field-label">Nama KK</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['namakk']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Tempat/Tanggal Lahir</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['tempattgllahir']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">Alamat</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['alamat']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Pendidikan KK</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['pendidikankk']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Tipe Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['tipekk']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Suku Bangsa</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['sukubangsa']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Agama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['agama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Status Sosial Ekonomi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['statussosialekonomi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Aktivitas Rekreasi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['aktivitasrekreasi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Komposisi Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['komposisikeluarga']) ?></div>
        </div>
        <table class="data">
            <thead>
                <tr>
                    <th>Nama (Inisial)</th>
                    <th>Jenis Kelamin</th>
                    <th>Hub. dengan KK</th>
                    <th>Umur</th>
                    <th>Pendidikan</th>
                    <th>Status Gizi</th>
                    <th>Status Imunisasi</th>
                    <th>Kondisi Kesehatan</th>
                    <th>TTV</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($pengkajian['keluarga'])): ?>
                <?php foreach ($pengkajian['keluarga'] as $dx): ?>
                    <tr>
                        <td><?= p($dx['nama_inisial'] ?? '') ?></td>
                        <td><?= p($dx['jenis_kelamin'] ?? '') ?></td>
                        <td><?= p($dx['hub_dengankk'] ?? '') ?></td>
                        <td><?= p($dx['umur'] ?? '') ?></td>
                        <td><?= p($dx['pendidikan'] ?? '') ?></td>

                        <td>
                            BB: <?= p($dx['status_gizi_bb'] ?? '') ?><br>
                            TB: <?= p($dx['status_gizi_tb'] ?? '') ?><br>
                            IMT: <?= p($dx['status_gizi_imt'] ?? '') ?>
                        </td>

                        <td><?= p($dx['status_imunisasi'] ?? '') ?></td>

                        <td><?= p($dx['kondisi_kesehatan'] ?? '') ?></td>

                        <td>
                            TD: <?= p($dx['ttv_td'] ?? '') ?><br>
                            N: <?= p($dx['ttv_n'] ?? '') ?><br>
                            S: <?= p($dx['ttv_s'] ?? '') ?><br>
                            RR: <?= p($dx['ttv_rr'] ?? '') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align:center">-</td>
                </tr>
            <?php endif; ?>
        </tbody>
        </table>
        <div class="field-row">
            <div class="field-label">Genogram dann Keterangan Gambar</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['genogram']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">G1 G2 G3</div>
            <div class="field-sep">:</div>

            <div class="field-value">
                <div>G1 : <?= p($pengkajian['g1']) ?></div>
                <div>G2 : <?= p($pengkajian['g2']) ?></div>
                <div>G3 : <?= p($pengkajian['g3']) ?></div>
            </div>
        </div>

        <h4>II. Riwayat dan Tahap Perkembangan Keluarga</h4>
        <div class="field-row">
            <div class="field-label">a. Tahap Perkembangan Keluarga</div>
            <div class="field-sep">:</div>

            <div class="field-value">
                <?php
                $tahap = [
                    'tahap1' => 'Tahap 1: Keluarga pasangan baru',
                    'tahap2' => 'Tahap 2: Keluarga dengan kelahiran anak pertama',
                    'tahap3' => 'Tahap 3: Keluarga dengan anak pra sekolah',
                    'tahap4' => 'Tahap 4: Keluarga dengan anak sekolah',
                    'tahap5' => 'Tahap 5: Keluarga dengan anak remaja',
                    'tahap6' => 'Tahap 6: Keluarga dengan usia dewasa/pertengahan',
                    'tahap7' => 'Tahap 7: Keluarga dengan usia pelepasan',
                    'tahap8' => 'Tahap 8: Keluarga dengan lanjut usia',
                ];

                echo p($tahap[$pengkajian['perkembangankeluarga']] ?? '-');
                ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">b. Tahap Perkembangan Keluarga yang Belum Terpenuhi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['belumterpenuhi']) ?></div>
        </div>

        <br>

        <h4>III. Riwayat Kesehatan Keluarga Inti</h4>
        <div class="field-row">
            <div class="field-label">Gambaran Umum Kondisi Kesehatan Seluruh Anggota Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['kondisikesehatankeluarga']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Saat Ini Anggota Keluarga yang Sakit dan Keluhan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['anggotasakitdankeluhan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Keluhan/Sakit yang Sering Dialami Berulang Dalam Keluarga </div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['keluhansakit']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Anggota Keluarga yang Menderita Penyakit Kronik Membutuhkan Penanganan/Perawatan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['penyakitkronik']) ?></div>
        </div>

        <br>

        <h4>IV. Riwayat Kesehatan Keluarga Sebelumnya</h4>
        <div class="field-row">
            <div class="field-label">Anggota Keluarga yang Pernah Dirawat dan Penyakit yang Diderita</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajian['penyakitdiderita']) ?></div>
        </div>

        <div class="page-break"></div>

        <!-- HEADER 
        <h1>LAPORAN RUANG OPERASI</h1> -->

        <!-- ================================ -->
        <!-- SECTION 2: Pengkajian Lanjutan 2 -->
        <!-- ================================ -->
        <h3 class="mt-5">Pengkajian Lanjutan 2</h3>

        <h4>V. Data Lingkungan</h4>
        <h4>1. Karakteristik Rumah</h4>

         <div class="field-row">
            <div class="field-label">Gambar Denah Rumah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['gambardenahrumah']) ?></div>
        </div>

        <h5>Penjelasan Karakteristik Rumah</h5>
        <table class="header-table" style="border:1px solid #000;">
            <tr>
                <td width="25%"><strong>Jenis Bangunan</strong></td>
                <td width="2%">:</td>
                <td width="23%"><?= p($pengkajianlanjutan2['jenisbangunan']) ?></td>
                <td width="25%"><strong>Jumlah Kamar</strong></td>
                <td width="2%">:</td>
                <td><?= p($pengkajianlanjutan2['jumlahkamar']) ?></td>
            </tr>
            <tr>
                <td><strong>Pencahayaan</strong></td>
                <td>:</td>
                <td><?= p($pengkajianlanjutan2['pencahayaan']) ?></td>
                <td><strong>Ventilasi</strong></td>
                <td>:</td>
                <td><?= p($pengkajianlanjutan2['ventilasi']) ?></td>
            </tr>
            <tr>
                <td><strong>Sumber Air Bersih</strong></td>
                <td>:</td>
                <td><?= p($pengkajianlanjutan2['sumberairbersih']) ?></td>
                <td><strong>Sumber Air Minum</strong></td>
                <td>:</td>
                <td><?= p($pengkajianlanjutan2['sumberairminum']) ?></td>
            </tr>
            <tr>
                <td><strong>Pembuangan Air Limbah</strong></td>
                <td>:</td>
                <td><?= p($pengkajianlanjutan2['pembuanganairlimbah']) ?></td>
                <td><strong>Penerangan (Malam Hari)</strong></td>
                <td>:</td>
                <td><?= p($pengkajianlanjutan2['peneranganmalamhari']) ?></td>
            </tr>
            <tr>
                <td><strong>Kebersihan Toilet, Kamar Mandi, dan Tempat Penampungan Air</strong></td>
                <td>:</td>
                <td><?= p($pengkajianlanjutan2['kebersihantoilet']) ?></td>
                <td><strong>Jumlah Jentik Nyamuk di Tempat Penampungan Air</strong></td>
                <td>:</td>
                <td><?= p($pengkajianlanjutan2['jumlahjentik']) ?></td>
            </tr>
        </table>
        
        <h4>2. Karakteristik Tetangga Komunitasnya</h4>
        <div class="field-row">
            <div class="field-label">Jarak Rumah disekitarnya</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['jarakrumahdisekitarnya']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kondisi Tetangga Sekitar</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['kondisitetanggasekitar']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Tanggapan Keluarga Terhadap Tetangga Sekitar</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['keluargaterhadaptetanggasekitar']) ?></div>
        </div>

        <br>

        <h4>3. Mobilitas Geografis Keluarga</h4>
        <div class="field-row">
            <div class="field-label">Kegiatan Keluarga Saat Pagi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['kegiatankeluargasaatpagi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kegiatan Keluarga Saat Siang/Sore</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['kegiatankeluargasaatsiangsore']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kegiatan Keluarga Saat Malam</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['kegiatankeluargasaatmalam']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kegiatan Keluarga Saat Senggang/Luang</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['kegiatankeluargasaatsenggangluang']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Waktu Keluarga Berkunjung untuk Saudara yang lain</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['waktuberkunjung']) ?></div>
        </div>

        <br>

        <h4>4. Perkumpulan Keluarga dan Interaksi dengan Masyarakat</h4>
        <div class="field-row">
            <div class="field-label">Keluarga Besar Berkumpul Pada Saat</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['keluargabesarkumpulpadasaat']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Kegiatan yang Ada dan Diikuti di Lingkungan Tempat Tinggal</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['kegiatanditempattinggal']) ?></div>
        </div>

        <br>

        <h4>5. Sistem Pendukung Keluarga</h4>
        <div class="field-row">
            <div class="field-label">Yang dimintai Pertolongan Bila Keluarga Menghadapi Masalah Keuangan/Ekonomi</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['masalahekonomi']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Anggota Keluarga yang Sering Memberikan Support/Dukungan Bila Keluarga Menghadapi Masalah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['dukunganmenghadapimasalah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Jenis Jaminan Sosial Kesehatan yang Dimiliki Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['jaminansosialkesehatan']) ?></div>
        </div>

        <br>

        <h4>VI. Struktur Keluarga</h4>
        <h4>1. Pola Komunikasi Keluarga</h4>
       
        <div class="field-row">
            <div class="field-label">Komunikasi Antar Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['komunikasiantarkeluarga']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Bahasa yang Sering Digunakan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['bahasayangseringdigunakan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Anggota Keluarga yang Memiliki Telepon/Ponsel</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['memilikiponsel']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Lama/Waktu Pembatasan Anak Menggunakan Ponsel</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['waktuanak']) ?></div>
        </div>

        <br>

        <h4>2. Struktur Peran Keluarga</h4>
       
        <div class="field-row">
            <div class="field-label">Peran Kepala Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['perankepalakeluarga']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Peran Istri dalam Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['peranistridalamkeluarga']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Peran Anak dalam Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['perananakdalamkeluarga']) ?></div>
        </div>

        <br>

        <h4>3. Nilai atau Norma Keluarga</h4>
       
        <div class="field-row">
            <div class="field-label">Kebiasaan di Rumah yang Diterapkan mengikuti Adat/Budaya/Suku</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['kebiasaandirumah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Penerapan Nilai-nilai Agama di Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['nilainilaiagama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Tanggapan Keluarga Terhadap Pergaulan Anak Baik di Lingkungan Sekitar/di Sekolah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['pergaulananak']) ?></div>
        </div>

        <br>

        <h4>4. Struktur Kekuatan Keluarga</h4>
       
        <div class="field-row">
            <div class="field-label">Yang Mengambil Keputusan dalam Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['mengambilkeputusan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Peran Anggota Keluarga dalam Pengambilan Keputusan dalam Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan2['pengambilankeputusankeluarga']) ?></div>
        </div>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 3: Pengkajian Lanjutan 3 -->
        <!-- ================================ -->
        <h3 class="mt-5">Pengkajian Lanjutan 3</h3>

        <h4>VII. Fungsi Keluarga</h4>
        <h4>1. Fungsi Afeksi</h4>

        <div class="field-row">
            <div class="field-label">Kedekatan Emosi Antar Anggota Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['emosikeluarga']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Respon Anggota Keluarga Bila Ada yang Mengalami Masalah</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['responmasalah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Cara Keluarga Agar Tetap Harmonis untuk Anggota Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['harmoniskeluarga']) ?></div>
        </div>

        <br>

        <h4>2. Fungsi Sosial</h4>

        <div class="field-row">
            <div class="field-label">Interaksi Keluarga dengan Lingkungan Sekitar</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['interaksilingkungan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Cara Keluarga Agar Anak Bersosialisasi dengan Lingkungan Sekitar</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['sosialisasianak']) ?></div>
        </div>

        <br>

        <h4>3. Fungsi Reproduksi</h4>

        <div class="field-row">
            <div class="field-label">Jumlah Anak</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['jumlahanak']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Keinginan untuk Menambah Anak</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['menambahanak']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Tanggapan Keluarga dengan Jumlah Anaknya Saat Ini</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['tanggapanjumlahanak']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Jenis Kontrasepsi yang Digunakan Sebelum dan Saat Ini</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['jeniskontrasepsi']) ?></div>
        </div>

        <br>

        <h4>4. Fungsi Ekonomi</h4>

        <div class="field-row">
            <div class="field-label">Penghasilan Keluarga Perbulan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['penghasilankeluargaperbulan']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Tanggapan Keluarga Tentang Penghasilan Tersebut dalam Memenuhi Kebutuhan Sehari-hari</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['tanggapanpenghasilan']) ?></div>
        </div>

        <br>

        <h4>5. Fungsi Perawatan Kesehatan Keluarga <br>
            a. Mengenal Masalah Keluarga</h4>
        <div class="field-row">
            <div class="field-label">1. Adakah Perhatian Keluarga Kepada Anggotanya yang Menderita Sakit</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['perhatiankeluarga']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Bila Jawaban Tidak, Alasannya</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['jawabantidak']) ?></div>
        </div>
         <div class="field-row">
            <div class="field-label">2. Apakah Keluarga Mengetahui Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['mengetahuimasalahkesehatan']) ?>
            </div>
        </div>
         <div class="field-row">
            <div class="field-label">3. Apakah Keluarga Mengetahui Penyebab Masalah Kesehatan yang Dialami Anggota Keluarga dalam Keluarganya</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['penyebabmasalahkesehatan']) ?>
            </div>
        </div>
         <div class="field-row">
            <div class="field-label">4. Apakah Keluarga Mengetahui Tanda dan Gejala Masalah Kesehatan yang Dialami Anggota Dalam Keluarganya</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['tandadangejala']) ?>
            </div>
        </div>

        <br>

       <h4>b. Mengambil Keputusan</h4>
        <div class="field-row">
            <div class="field-label">1. Apakah Keluarga Mengetahui Akibat Masalah Kesehatan yang Dialami Anggota dalam Keluarganya Bila Tidak Diobati/Dirawat</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['akibatmasalahkesehatan']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">2. Pada Siapa Keluarga Biasa Menggali Informasi Tentang Masalah Kesehatan yang Dialami Anggota Keluarganya</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['informasikesehatan']) ?></div>
        </div> 

        <br>

        <div class="field-row">
            <div class="field-label">3. Keyakinan Keluarga Tentang Masalah Kesehatan yang Dialami Anggota Keluarganya</div>
            <div class="field-sep"></div>
            <div class="field-value"></div>
        </div>

        <div class="field-row">
            <div class="field-label">- Tidak Perlu ditangani karena Akan Sembuh Sendiri Biasanya</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['sembuhsendiri']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">- Perlu Berobat ke Fasilitas Yankes</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['perluberobat']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">- Tidak Terpikir/Tidak Peduli/Cuek</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['tidakpeduli']) ?>
            </div>
        </div>

        <br>

        <div class="field-row">
            <div class="field-label">4. Apakah Keluarga Melakukan Upaya-upaya Peningkatan Kesehatan yang dialami Anggota Keluarganya Secara Aktif</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['upayakesehatan']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Bila Tidak, Jelaskan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['bilatidakupayakesehatan']) ?></div>
        </div> 

        <br>

        <div class="field-row">
            <div class="field-label">5. Apakah Keluarga Mengetahui Kebutuhan Pengobatan Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['kebutuhanpengobatan']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Bila Tidak, Jelaskan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['bilatidakupayapengobatan']) ?></div>
        </div> 

        <br>

        <h4>c. Merawat Anggota Keluarga yang Sakit</h4>
        <div class="field-row">
            <div class="field-label">Apakah Keluarga Dapat Melakukan Cara Merawat Anggota Keluarga dengan Masalah Kesehatan yang dialaminya</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['caramerawat']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Bila Tidak, Jelaskan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['bilatidakcaramerawat']) ?></div>
        </div> 

        <br>

        <h4>d. Memodifikasi Lingkungan</h4>
        <div class="field-row">
            <div class="field-label">1. Apakah Keluarga Dapat Melakukan Pencegahan Masalah Kesehatan yang Dialami Anggota dalam Keluarganya</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['pencegahanmasalah']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Bila Tidak, Jelaskan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['bilatidakpencegahanmasalah']) ?></div>
        </div> 
        <div class="field-row">
            <div class="field-label">2. Apakah Keluarga Mampu Memelihara atau Memodifikasi Lingkungan yang Mendukung Kesehatan Anggota Keluarga yang Mengalami Masalah Kesehatan</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['memeliharalingkungan']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Bila Tidak, Jelaskan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['bilatidakmemeliharalingkungan']) ?></div>
        </div> 

        <br>

        <h4>e. Memanfaatkan Fasilitas Kesehatan</h4>
        <div class="field-row">
            <div class="field-label">Apakah Keluarga Mampu Menggali dan Memanfaatkan Tenaga Kesehatan di Masyarakat untuk Mengatasi Masalah Kesehatan Anggota Keluarganya</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['tenagakesehatan']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label">Bila Tidak, Jelaskan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['bilatidaktenagakesehatan']) ?></div>
        </div>
        
        <br>

        <h4>6. Fungsi Religius</h4>
        <div class="field-row">
            <div class="field-label">Jenis Ibadah yang Dijalankan Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['jenisibadah']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">Usia Anak diperkenalkan Tentang Ajaran Agama</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['ajaranagama']) ?></div>
        </div>

        <br>

        <h4>VIII. Stress dan Koping Keluarga</h4>

        <div class="field-row">
            <div class="field-label">1. Stressor Jangka Pendek dan Panjang</div>
            <div class="field-sep"></div>
            <div class="field-value"></div>
        </div>

        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">a. Masalah/Beban Pikiran Saat Ini</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['masalahbebankeluarga']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">b. Masalah/Beban Pikiran yang Sudah Lama dirasakan (Lebih Dari 6 Bulan)</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['masalahbebankeluargalama']) ?></div>
        </div>
        <div class="field-row">
            <div class="field-label">2. Kemampuan/Tanggapan Keluarga Terhadap Stressor</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['masalahbebankeluargalama']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">3. Strategi Koping yang digunakan</div>
            <div class="field-sep"></div>
            <div class="field-value"></div>
        </div>

        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">a. Bercerita dengen Keluarga</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['berceritadengankeluarga']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">b. Menyelesaikan Sendiri</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['menyelesaikansendiri']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">c. Meminta Tanggapan dari Teman yang dipercaya</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['memintatanggapan']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">d. Lebih Mendekatkan Diri Pada Tuhan Yang Maha Esa</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['mendekatkandiri']) ?>
            </div>
        </div>

         <div class="field-row">
            <div class="field-label">4. Strategi Adaptasi Fungsional</div>
            <div class="field-sep"></div>
            <div class="field-value"></div>
        </div>

        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">a. Sering Marah</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['seringmarah']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">b. Mengalihkan ke Hal Negatif seperti Mengkonsumsi Minuman Alkohol</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['halnegatif']) ?>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label" style="padding-left:20px;">c. Mengalihkan Beban Pikiran dengan Merokok</div>
            <div class="field-sep">:</div>
            <div class="field-value">
                <?= p($pengkajianlanjutan3['mengalihkanbebanpikiran']) ?>
            </div>
        </div>

        <br>

        <h4>IX. Pemeriksaan Fisik</h4>

        <div class="field-row">
            <div class="field-label">Pemeriksaan Fisik</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['pemeriksaanfisik']) ?></div>
        </div>

        <br>

        <h4>X. Harapan Keluarga</h4>

        <div class="field-row">
            <div class="field-label">1. Harapan Terhadap Kesehatannya</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['harapankesehatan']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">2. Harapan Keluarga Terhadap Petugas Kesehatan</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($pengkajianlanjutan3['harapanterhadappetugaskesehatan']) ?></div>
        </div>

        <div class="page-break"></div>

        <h4>XI. Tingkat Kemandirian Keluarga</h4>

        <table class="data">
    <thead>
        <tr>
            <th>No</th>
            <th>Kunjungan Ke</th>
            <th>Perawat</th>
            <th>Hasil</th>
            <th>Kriteria Tingkat Kemandirian Keluarga</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($pengkajianlanjutan3['kemandirian'])): ?>
            <?php $no = 1; ?>
            <?php foreach ($pengkajianlanjutan3['kemandirian'] as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= p($row['kunjunganke'] ?? '') ?></td>
                    <td><?= p($row['perawat'] ?? '') ?></td>
                    <td><?= p($row['hasil'] ?? '') ?></td>
                    <td>
                        <?php
                        $tingkat = [];

                        if (($row['menerimaperawat'] ?? '') === 'ya') {
                            $tingkat[] = '1. Keluarga Menerima Perawat';
                        }

                        if (($row['pelayanankesehatan'] ?? '') === 'ya') {
                            $tingkat[] = '2. Keluarga Menerima Pelayanan Kesehatan';
                        }

                        if (($row['mengungkapkanmasalah'] ?? '') === 'ya') {
                            $tingkat[] = '3. Keluarga Tahu dan Mengungkapkan Masalah';
                        }

                        if (($row['faskes'] ?? '') === 'ya') {
                            $tingkat[] = '4. Memanfaatkan Faskes';
                        }

                        if (($row['tindakankeperawatan'] ?? '') === 'ya') {
                            $tingkat[] = '5. Tindakan Keperawatan';
                        }

                        if (($row['tindakanpencegahan'] ?? '') === 'ya') {
                            $tingkat[] = '6. Tindakan Pencegahan';
                        }

                        if (($row['tindakanpromotif'] ?? '') === 'ya') {
                            $tingkat[] = '7. Tindakan Promotif';
                        }

                        echo !empty($tingkat)
                        ? implode('<br>', $tingkat)
                        : '-';
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center">-</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 4: Analisa Data -->
        <!-- ================================ -->
        <h3 class="mt-5">Klasifikasi Data</h3>

        <h4>XII. Klasifikasi Data</h4>
        <table class="data">
            <thead>
                <tr>
                    <th width="50%">Data Kesehatan Keluarga (DS)</th>
                    <th width="50%">Data Penunjang (DO)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($klasifikasi['klasifikasi'])): ?>
                    <?php foreach ($klasifikasi['klasifikasi'] as $klas): ?>
                        <tr>
                            <td><?= p($klas['kesehatan']) ?></td>
                            <td><?= p($klas['penunjang']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="page-break"></div>

        <!-- ================================ -->
        <!-- SECTION 4: CATATAN KEPERAWATAN -->
        <!-- ================================ -->
        <h3 class="mt-5">B. Diagnosa Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>Masalah Keperawatan</th>
                    <th>Nama Anggota Keluarga yang Sakit</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lainnya['diagnosa'])): ?>
                    <?php foreach ($lainnya['diagnosa'] as $dx): ?>
                        <tr>
                            <td><?= p($dx['masalahkeperawatan']) ?></td>
                            <td><?= p($dx['keluargayangsakit']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3 class="mt-5">Kriteria</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>Sifat Masalah (Bobot 1)</th>
                    <th>Pembenaran</th>
                    <th>Kemungkinan Masalah Dapat diubah (Bobot 2)</th>
                    <th>Pembenaran</th>
                    <th>Potensial Masalah Dapat dicegah (Bobot 1)</th>
                    <th>Pembenaran</th>
                    <th>Menonjolnya Masalah (Bobot 1)</th>
                    <th>Pembenaran</th>  
                    <th>Jumlah Skoring</th>   
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lainnya['kriteria'])): ?>
                    <?php foreach ($lainnya['kriteria'] as $dx): ?>
                        <tr>
                            <td><?= p($dx['sifatmasalah']) ?></td>
                            <td><?= p($dx['pembenaransifatmasalah']) ?></td>
                            <td><?= p($dx['masalahdapatdiubah']) ?></td>
                            <td><?= p($dx['pembenaranmasalahdapatdiubah']) ?></td>
                            <td><?= p($dx['masalahdapatdicegah']) ?></td>
                            <td><?= p($dx['pembenaranmasalahdapatdicegah']) ?></td>
                            <td><?= p($dx['menonjolnyamasalah']) ?></td>
                            <td><?= p($dx['pembenaranmenonjolnyamasalah']) ?></td>
                            <td><?= p($dx['jumlahskoring']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="page-break"></div>

        <h3 class="mt-5">C. Rencana Keperawatan</h3>

        <div class="field-row">
            <div class="field-label">Nama KK</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya['namakk']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">Tanggal Pengkajian</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya['tglpengkajian']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">Umur</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya['umur']) ?></div>
        </div>

        <div class="field-row">
            <div class="field-label">Alamat</div>
            <div class="field-sep">:</div>
            <div class="field-value"><?= p($lainnya['alamat']) ?></div>
        </div>

        <table class="data">
            <thead>
                <tr>
                    <th width="40%">Diagnosa Keperawatan</th>
                    <th width="30%">Tujuan Jangka Panjang (Umum)</th>
                    <th width="30%">Tujuan Jangka Pendek (Khusus)</th>
                    <th width="40%">Kriteria</th>
                    <th width="30%">Standar</th>
                    <th width="30%">Intervensi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lainnya['rencana'])): ?>
                    <?php foreach ($lainnya['rencana'] as $inv): ?>
                        <tr>
                            <td><?= p($inv['diagnosakeperawatan']) ?></td>
                            <td><?= p($inv['tujuanjangkapanjang']) ?></td>
                            <td><?= p($inv['tujuanjangkapendek']) ?></td>
                            <td><?= p($inv['kriteria']) ?></td>
                            <td><?= p($inv['standar']) ?></td>
                            <td><?= p($inv['intervensi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="page-break"></div>

        <h3 class="mt-5">D. Implementasi Keperawatan</h3>

        <table class="data">
            <thead>
                <tr>
                    <th>Pertemuan</th>
                    <th>Hari/Tanggal</th>
                    <th>Jam</th>
                    <th>Implementasi</th>
                    <th>Hasil</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($lainnya['implementasi'])): ?>
                    <?php foreach ($lainnya['implementasi'] as $impl): ?>
                        <tr>
                            <td><?= p($impl['pertemuan']) ?></td>
                            <td><?= p($impl['haritgl']) ?></td>
                            <td><?= p($impl['jam']) ?></td>
                            <td><?= p($impl['implementasi']) ?></td>
                            <td><?= p($impl['hasil']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center">-</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="page-break"></div>

        <h3 class="mt-5">E. Evaluasi Keperawatan</h3>

    <table class="data">
        <thead>
            <tr>
                <th>Pertemuan</th>
                <th>Hari/Tanggal</th>
                <th>Jam</th>
                <th>Evaluasi (SOAP)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($lainnya['evaluasi'])): ?>
                <?php foreach ($lainnya['evaluasi'] as $eval): ?>
                    <tr>
                        <td><?= p($eval['pertemuan']) ?></td>
                        <td><?= p($eval['haritgl']) ?></td>
                        <td><?= p($eval['jam']) ?></td>
                        <td style="white-space: pre-line;">
                            <?= p($eval['evaluasi_soap']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center">-</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    </div>
</body>

</html>