<?php include_once "header.php"; ?>

<?php

if (isset($_SESSION['id_user'])) {

    include_once "navbar.php";

    // ambil parameter URL dengan aman
    $page = $_GET['page'] ?? '';
    $tab  = $_GET['tab'] ?? '';

    // =====================
    // ADMIN
    // =====================
    if ($_SESSION['level'] == 'Mahasiswa') {

        include_once "sidebar_mhs.php";

        echo "<H1> ini adalah page $page"
            . ($tab ? " - $tab" : "")
            . "</H1>";
        switch ($page) {

            // =====================
            // MATERNITAS ANTENATAL
            // =====================
            case 'maternitas/pengkajian_antenatal_care':

                $tab = $tab ?: 'pengkajian';

                switch ($tab) {

                    case 'data_demografi':
                        include "maternitas/pengkajian_antenatal_care/halm_data_demografi.php";
                        break;

                    case 'riwayat_kelahiran_persalinan':
                        include "maternitas/pengkajian_antenatal_care/halm_data_riwayat_kelahiran_persalinan.php";
                    break;

                    case 'pengkajian_fisik':
                        include "maternitas/pengkajian_antenatal_care/halm_data_pengkajian_fisik.php";
                    break;

                    case 'terapi_lab':
                        include "maternitas/pengkajian_antenatal_care/halm_data_terapi_lab.php";
                    break;

                    case 'analisa_data':
                        include "maternitas/pengkajian_antenatal_care/halm_analisa_data.php";
                    break;

                    case 'pengkajian':
                        include "maternitas/pengkajian_antenatal_care/halm_tambah_pengkajian.php";
                        break;

                    case 'catatan_keperawatan':
                        include "maternitas/pengkajian_antenatal_care/halm_catatan_keperawatan.php";
                        break;

                    case 'intervensi_keperawatan':
                        include "maternitas/pengkajian_antenatal_care/halm_tambah_intervensi.php";
                        break;

                    case 'implementasi_keperawatan':
                        include "maternitas/pengkajian_antenatal_care/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi_keperawatan':
                        include "maternitas/pengkajian_antenatal_care/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "maternitas/pengkajian_antenatal_care/halm_data_demografi.php";
                }

                break;

            // =====================
            // MATERNITAS PASCAPARTUM
            // =====================
            case 'maternitas/pengkajian_pascapartum':

                $tab = $tab ?: 'pemeriksaanfisik';

                switch ($tab) {

                    case 'pemeriksaanfisik':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_pemeriksaanfisik.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_diagnosa.php";
                        break;

                    case 'intervensi_keperawatan':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_intervensi.php";
                        break;

                    case 'implementasi_keperawatan':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi_keperawatan':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "maternitas/pengkajian_pascapartum/halm_tambah_umum.php";
                }

                break;

            // =====================
            // MATERNITAS Inranatal Care
            // =====================
            case 'maternitas/pengkajian_inranatal_care':

                $tab = $tab ?: 'laporanpersalinan';

                switch ($tab) {

                    case 'laporanpersalinan':
                        include "maternitas/pengkajian_inranatal_care/halm_tambah_laporanpersalinan.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "maternitas/pengkajian_inranatal_care/halm_tambah_diagnosa.php";
                        break;

                    case 'intervensi_keperawatan':
                        include "maternitas/pengkajian_inranatal_care/halm_tambah_intervensi.php";
                        break;

                    case 'implementasi_keperawatan':
                        include "maternitas/pengkajian_inranatal_care/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi_keperawatan':
                        include "maternitas/pengkajian_inranatal_care/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "maternitas/pengkajian_inranatal_care/halm_tambah_umum.php";
                }

                break;


            // =====================
            // MATERNITAS Resume ANTENATAL
            // =====================
            case 'maternitas/resume_antenatal_care':

                $tab = $tab ?: 'pengkajian';

                switch ($tab) {

                    case 'pengkajian':
                        include "maternitas/resume_antenatal_care/halm_tambah_pengkajian.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "maternitas/resume_antenatal_care/halm_tambah_diagnosa.php";
                        break;

                    case 'intervensi_keperawatan':
                        include "maternitas/resume_antenatal_care/halm_tambah_intervensi.php";
                        break;

                    case 'implementasi_keperawatan':
                        include "maternitas/resume_antenatal_care/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi_keperawatan':
                        include "maternitas/resume_antenatal_care/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "maternitas/resume_antenatal_care/halm_tambah_demografi.php";
                }

                break;

            // =====================
            // MATERNITAS Ginekologi
            // =====================
            case 'maternitas/pengkajian_ginekologi':

                $tab = $tab ?: 'pengkajian';

                switch ($tab) {

                    case 'pengkajian':
                        include "maternitas/pengkajian_ginekologi/halm_tambah_pengkajian.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "maternitas/pengkajian_ginekologi/halm_tambah_diagnosa.php";
                        break;

                    case 'intervensi_keperawatan':
                        include "maternitas/pengkajian_ginekologi/halm_tambah_intervensi.php";
                        break;

                    case 'implementasi_keperawatan':
                        include "maternitas/pengkajian_ginekologi/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi_keperawatan':
                        include "maternitas/pengkajian_ginekologi/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "maternitas/pengkajian_ginekologi/halm_tambah_demografi.php";
                }

                break;

            // =====================
            // Keluarga
            // =====================
            case 'keluarga':

                $tab = $tab ?: 'pengkajian';

                switch ($tab) {

                    case 'pengkajian':
                        include "keluarga/halm_tambah_pengkajian.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "keluarga/halm_tambah_diagnosa.php";
                        break;

                    case 'rencana_keperawatan':
                        include "keluarga/halm_tambah_rencana.php";
                        break;

                    case 'implementasi_keperawatan':
                        include "keluarga/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi_keperawatan':
                        include "keluarga/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "keluarga/halm_tambah_pengkajian.php";
                }

                break;

            // =====================
            // Gadar ICU
            // =====================
            case 'gadar/icu':

                $tab = $tab ?: 'laporanpendahuluan';

                switch ($tab) {

                    case 'laporanpendahuluan':
                        include "gadar/icu/halm_tambah_laporanpendahuluan.php";
                        break;

                    case 'pengkajian':
                        include "gadar/icu/halm_tambah_pengkajian.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "gadar/icu/halm_tambah_diagnosa.php";
                        break;

                    case 'rencana_keperawatan':
                        include "gadar/icu/halm_tambah_rencana.php";
                        break;

                    case 'implementasi_keperawatan':
                        include "gadar/icu/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi_keperawatan':
                        include "gadar/icu/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "gadar/icu/halm_tambah_laporanpendahuluan.php";
                }

                break;

            // =====================
            // Gadar IGD
            // =====================
            case 'gadar/igd':

                $tab = $tab ?: 'laporanpendahuluan';

                switch ($tab) {

                    case 'laporanpendahuluan':
                        include "gadar/igd/halm_tambah_laporanpendahuluan.php";
                        break;

                    case 'pengkajian':
                        include "gadar/igd/halm_tambah_pengkajian.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "gadar/igd/halm_tambah_diagnosa.php";
                        break;

                    case 'rencana_keperawatan':
                        include "gadar/igd/halm_tambah_rencana.php";
                        break;

                    case 'implementasi_keperawatan':
                        include "gadar/igd/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi_keperawatan':
                        include "gadar/igd/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "gadar/igd/halm_tambah_laporanpendahuluan.php";
                }

                break;


            // =====================
            // GERONTIK
            // =====================
            case 'gerontik':

                $tab = $tab ?: 'identitas';

                switch ($tab) {

                    case 'identitas':
                        include "gerontik/halm_tambah_identitas.php";
                        break;

                    case 'pengkajian-lanjutan':
                        include "gerontik/halm_tambah_pengkajian_lanjutan.php";
                        break;

                    case 'pengkajian-riwayat':
                        include "gerontik/halm_tambah_pengkajian_riwayat.php";
                        break;
                    
                    case 'pengkajian-fisik':
                        include "gerontik/halm_tambah_pengkajian_fisik.php";
                        break;

                    case 'pengkajian-kebiasaan':
                        include "gerontik/halm_tambah_pengkajian_kebiasaan.php";
                        break;

                    case 'pengkajian-psikis':
                        include "gerontik/halm_tambah_pengkajian_psikis.php";
                        break;

                    case 'pengkajian-depresi':
                        include "gerontik/halm_tambah_pengkajian_depresi.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "gerontik/halm_tambah_diagnosa.php";
                        break;

                    case 'rencana':
                        include "gerontik/halm_tambah_rencana.php";
                        break;

                    case 'implementasi_keperawatan':
                        include "gerontik/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi_keperawatan':
                        include "gerontik/halm_tambah_evaluasi.php";
                        break;

                    case 'sap':
                        include "gerontik/halm_tambah_sap.php";
                        break;

                    default:
                        include "gerontik/halm_tambah_identitas.php";
                }

                break;

            // =====================
            // KMB Format KMB
            // =====================
            case 'kmb/format_kmb':

                $tab = $tab ?: 'format_askep';

                switch ($tab) {

                    case 'askep':
                        include "kmb/format_kmb/halm_tambah_format_askep.php";
                        break;

                    case 'klasifikasi_data':
                        include "kmb/format_kmb/halm_tambah_klasifikasi_data.php";
                        break;

                    case 'analisa_data':
                        include "kmb/format_kmb/halm_tambah_analisa_data.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "kmb/format_kmb/halm_tambah_diagnosa_keperawatan.php";
                        break;

                    case 'rencana':
                        include "kmb/format_kmb/halm_tambah_rencana.php";
                        break;

                    case 'implementasi':
                        include "kmb/format_kmb/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi':
                        include "kmb/format_kmb/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "kmb/format_kmb/halm_tambah_format_askep.php";
                }

                break;

            // =====================
            // KMB Format HD KMB
            // =====================
            case 'kmb/format_hd_kmb':

                $tab = $tab ?: 'lp_ruanghd';

                switch ($tab) {

                    case 'lp_ruanghd':
                        include "kmb/format_hd_kmb/halm_tambah_lp_ruanghd.php";
                        break;

                    case 'format_hd':
                        include "kmb/format_hd_kmb/halm_tambah_format_hd.php";
                        break;

                    case 'resume':
                        include "kmb/format_hd_kmb/halm_tambah_resume.php";
                        break;

                    case 'analisa':
                        include "kmb/format_hd_kmb/halm_tambah_analisa.php";
                        break;

                    case 'diagnosa':
                        include "kmb/format_hd_kmb/halm_tambah_diagnosa.php";
                        break;

                    case 'rencana':
                        include "kmb/format_hd_kmb/halm_tambah_rencana.php";
                        break;

                    case 'implementasi':
                        include "kmb/format_hd_kmb/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi':
                        include "kmb/format_hd_kmb/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "kmb/format_hd_kmb/halm_tambah_lp_ruanghd.php";
                }

                break;

            // =====================
            // KMB Pengkajian Ruang OK
            // =====================
            case 'kmb/pengkajian_ruang_ok':

                $tab = $tab ?: 'lp_ruangok';

                switch ($tab) {

                    case 'lp_ruangok':
                        include "kmb/pengkajian_ruang_ok/halm_tambah_lp_ruangok.php";
                        break;

                    case 'ruang_operasi':
                        include "kmb/pengkajian_ruang_ok/halm_tambah_ruang_operasi.php";
                        break;

                    case 'resume':
                        include "kmb/pengkajian_ruang_ok/halm_tambah_resume.php";
                        break;

                    case 'analisa':
                        include "kmb/pengkajian_ruang_ok/halm_tambah_analisa.php";
                        break;

                    case 'diagnosa':
                        include "kmb/pengkajian_ruang_ok/halm_tambah_diagnosa.php";
                        break;

                    case 'rencana':
                        include "kmb/pengkajian_ruang_ok/halm_tambah_rencana.php";
                        break;

                    case 'implementasi':
                        include "kmb/pengkajian_ruang_ok/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi':
                        include "kmb/pengkajian_ruang_ok/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "kmb/pengkajian_ruang_ok/halm_tambah_lp_ruangok.php";
                }

                break;

            // =====================
            // Jiwa Jiwa RSUD
            // =====================
            case 'jiwa/jiwa_rsud':

                $tab = $tab ?: 'format_laporan_pendahuluan';

                switch ($tab) {

                    case 'format_laporan_pendahuluan':
                        include "jiwa/jiwa_rsud/halm_tambah_format_laporan_pendahuluan.php";
                        break;

                    case 'pengkajian':
                        include "jiwa/jiwa_rsud/halm_tambah_pengkajian.php";
                        break;

                    case 'diagnosa':
                        include "jiwa/jiwa_rsud/halm_tambah_diagnosa.php";
                        break;

                    case 'rencana':
                        include "jiwa/jiwa_rsud/halm_tambah_rencana.php";
                        break;

                    case 'implementasi':
                        include "jiwa/jiwa_rsud/halm_tambah_implementasi.php";
                        break;

                    default:
                        include "jiwa/jiwa_rsud/halm_tambah_format_laporan_pendahuluan.php";
                }

                // =====================
                // Jiwa Poli Jiwa
                // =====================
            case 'jiwa/poli_jiwa':

                $tab = $tab ?: 'halm_tambah_praktik_klinik_keperawatan_jiwa';

                switch ($tab) {

                    case 'halm_tambah_praktik_klinik_keperawatan_jiwa':
                        include "jiwa/poli_jiwa/halm_tambah_praktik_klinik_keperawatan_jiwa.php";
                        break;

                    case 'diagnosa':
                        include "jiwa/poli_jiwa/halm_tambah_diagnosa.php";
                        break;

                    case 'implementasi':
                        include "jiwa/poli_jiwa/halm_tambah_implementasi.php";
                        break;

                    default:
                        include "jiwa/poli_jiwa/halm_tambah_praktik_klinik_keperawatan_jiwa.php";
                }

                break;

            // =====================
            // Anak Format Anggrek
            // =====================
            case 'anak/format_anggrek':

                $tab = $tab ?: 'format_laporan_pendahuluan';

                switch ($tab) {

                    case 'format_laporan_pendahuluan':
                        include "anak/format_anggrek/halm_tambah_format_laporan_pendahuluan.php";
                        break;

                    case 'pengkajian':
                        include "anak/format_anggrek/halm_tambah_pengkajian.php";
                        break;

                    case 'diagnosa':
                        include "anak/format_anggrek/halm_tambah_diagnosa.php";
                        break;

                    case 'rencana':
                        include "anak/format_anggrek/halm_tambah_rencana.php";
                        break;

                    case 'implementasi':
                        include "anak/format_anggrek/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi':
                        include "anak/format_anggrek/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "anak/format_anggrek/halm_tambah_format_laporan_pendahuluan.php";
                }

                break;

            // =====================
            // Anak Format Aster
            // =====================
            case 'anak/format_aster':

                $tab = $tab ?: 'format_laporan_pendahuluan';

                switch ($tab) {

                    case 'format_laporan_pendahuluan':
                        include "anak/format_aster/halm_tambah_format_laporan_pendahuluan.php";
                        break;

                    case 'diagnosa':
                        include "anak/format_aster/halm_tambah_diagnosa.php";
                        break;

                    case 'rencana':
                        include "anak/format_aster/halm_tambah_rencana.php";
                        break;

                    case 'implementasi':
                        include "anak/format_aster/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi':
                        include "anak/format_aster/halm_tambah_evaluasi.php";
                        break;

                    default:
                        include "anak/format_aster/halm_tambah_format_laporan_pendahuluan.php";
                }

                break;

            // =====================
            // Anak Format Resume
            // =====================
            case 'anak/format_resume':

                $tab = $tab ?: 'format_resume_keperawatan';

                switch ($tab) {

                    case 'format_resume_keperawatan':
                        include "anak/format_resume/halm_tambah_format_resume_keperawatan.php";
                        break;

                    case 'format_laporan_poli':
                        include "anak/format_resume/halm_tambah_format_laporan_poli.php";
                        break;

                    case 'format_laporan_pendahuluan_imunisasi':
                        include "anak/format_resume/halm_tambah_format_laporan_imunisasi.php";
                        break;

                    default:
                        include "anak/format_resume/halm_tambah_format_resume_keperawatan.php";
                }

                break;

            // =====================
            // LAINNYA
            // =====================
            case 'laporan':
                include "laporan/halm_laporan.php";
                break;

            case 'ganti_password':
                include "halaman_ganti_password.php";
                break;

            case 'keluar':
                include "auth/logout.php";
                break;

            default:
                include "halaman_dashboard.php";
        }
    }

    // =====================
    // STAFF DOKTER
    // =====================
    elseif ($_SESSION['level'] == 'Staff-Dokter') {

        include_once "navbar_staff.php";
        include_once "sidebar_staff.php";

        switch ($page) {

            case 'keluar':
                include "auth/logout.php";
                break;

            case 'ganti_password':
                include "halaman_ganti_password.php";
                break;

            default:
                include "arsip/halm_cari_arsip.php";
        }
    }
} else {

    include_once "auth/login.php";
}

include_once "footer.php";
?>