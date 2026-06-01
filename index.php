<?php include_once "header.php"; ?>

<?php

if (isset($_SESSION['id_user'])) {

    include_once "navbar.php";

    // ambil parameter URL dengan aman
    $page = $_GET['page'] ?? '';
    $tab  = $_GET['tab'] ?? '';
    $tab = $tab ?: 'identitas';
    echo "ini tab: " . htmlspecialchars($tab) . "<br>";
    echo "ini page: " . htmlspecialchars($page) . "<br>";

    // =====================
    // Mahasiswa & Dosen
    // =====================
    if ($_SESSION['level'] == 'Mahasiswa' || $_SESSION['level'] == 'Dosen') {

        if ($_SESSION['level'] == 'Dosen') {
            include_once "sidebar_dosen.php";
        } else {
            include_once "sidebar_mhs.php";
        }

        switch ($page) {
            case 'dashboard':
                if ($_SESSION['level'] == 'Dosen') {
                    include "halaman_dashboard.php";
                } else {
                    include "halaman_dashboard_mahasiswa.php";
                }
                break;

            case 'dashboard/detail_mahasiswa':
                if ($_SESSION['level'] == 'Dosen') {
                    include "halaman_detail_mahasiswa.php";
                }
                break;

            case 'maternitas/detail':
                include "maternitas/halm_data_maternitas.php";
                break;

            // =====================
            // MATERNITAS ANTENATAL
            // =====================
            case 'maternitas/pengkajian_antenatal_care':

                $tab = $tab ?: 'laporan_pendahuluan';

                switch ($tab) {
                    case 'laporan_pendahuluan':
                        include "maternitas/pengkajian_antenatal_care/halm_laporan_pendahuluan.php";
                        break;
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

                    case 'catatan_keperawatan':
                        include "maternitas/pengkajian_antenatal_care/halm_catatan_keperawatan.php";
                        break;
                    default:
                        include "maternitas/pengkajian_antenatal_care/halm_laporan_pendahuluan.php";
                }

                break;

            // =====================
            // MATERNITAS PASCAPARTUM
            // =====================
            case 'maternitas/pengkajian_pascapartum':

                $tab = $tab ?: 'laporan_pendahuluan';

                switch ($tab) {
                    case 'laporan_pendahuluan':
                        include "maternitas/pengkajian_pascapartum/halm_laporan_pendahuluan.php";
                        break;
                    case 'identitas':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_identitas.php";
                        break;
                    case 'data_biologis':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_data_biologis.php";
                        break;
                    case 'pemeriksaan_fisik1':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_pemeriksaan_fisik1.php";
                        break;
                    case 'pemeriksaan_fisik2':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_pemeriksaan_fisik2.php";
                        break;
                    case 'pemeriksaan_fisik3':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_pemeriksaan_fisik3.php";
                        break;
                    case 'terapi_lab':
                        include "maternitas/pengkajian_pascapartum/halm_data_terapi_lab.php";
                        break;

                    case 'riwayat_kehamilan':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_riwayat_kehamilan.php";
                        break;

                    case 'lainnya':
                        include "maternitas/pengkajian_pascapartum/halm_tambah_lainnya.php";
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
                        include "maternitas/pengkajian_pascapartum/halm_laporan_pendahuluan.php";
                }

                break;

            // =====================
            // MATERNITAS Inranatal Care
            // =====================
            case 'maternitas/pengkajian_inranatal_care':

                $tab = $tab ?: 'laporan_pendahuluan';

                switch ($tab) {
                    case 'laporan_pendahuluan':
                        include "maternitas/pengkajian_inranatal_care/halm_laporan_pendahuluan.php";
                        break;

                    case 'umum':
                        include "maternitas/pengkajian_inranatal_care/halm_tambah_umum.php";
                        break;
                    case 'riwayat_persalinan':
                        include "maternitas/pengkajian_inranatal_care/halm_tambah_riwayatpersalinan.php";
                        break;
                    case 'terapi_lab':
                        include "maternitas/pengkajian_inranatal_care/halm_data_terapi_lab.php";
                        break;

                    case 'laporanpersalinan':
                        include "maternitas/pengkajian_inranatal_care/halm_tambah_laporanpersalinan.php";
                        break;
                    case 'analisa_data':
                        include "maternitas/pengkajian_inranatal_care/halm_analisa_data.php";
                        break;
                    case 'lainnya':
                        include "maternitas/pengkajian_inranatal_care/halm_tambah_lainnya.php";
                        break;
                    default:
                        include "maternitas/pengkajian_inranatal_care/halm_laporan_pendahuluan.php";
                }

                break;



            // =====================
            // MATERNITAS Resume ANTENATAL
            // =====================
            case 'maternitas/resume_antenatal_care':

                $tab = $tab ?: 'laporan_pendahuluan_anc';

                switch ($tab) {

                    case 'laporan_pendahuluan_anc':
                        include "maternitas/resume_antenatal_care/halm_laporan_pendahuluan_anc.php";
                        break;

                    case 'laporan_pendahuluan_kb':
                        include "maternitas/resume_antenatal_care/halm_laporan_pendahuluan_kb.php";
                        break;

                    case 'identitas':
                        include "maternitas/resume_antenatal_care/halm_tambah_identitas.php";
                        break;
                    case 'pengkajian_anamnesa':
                        include "maternitas/resume_antenatal_care/halm_tambah_pengkajian_anamnesa.php";
                        break;

                    case 'pengkajian_tanda_vital':
                        include "maternitas/resume_antenatal_care/halm_tambah_pengkajian_tanda_vital.php";
                        break;

                    case 'pemeriksaan_fisik':
                        include "maternitas/resume_antenatal_care/halm_tambah_pemeriksaan_fisik.php";
                        break;

                    case 'program_terapi':
                        include "maternitas/resume_antenatal_care/halm_tambah_program_terapi.php";
                        break;

                    case 'terapi_lab':
                        include "maternitas/resume_antenatal_care/halm_data_terapi_lab.php";
                        break;
                    case 'lainnya':
                        include "maternitas/resume_antenatal_care/halm_tambah_lainnya.php";
                        break;

                    default:
                        include "maternitas/resume_antenatal_care/halm_laporan_pendahuluan_anc.php";
                }

                break;

            // =====================
            // MATERNITAS Ginekologi
            // =====================
            case 'maternitas/pengkajian_ginekologi':

                $tab = $tab ?: 'laporan_pendahuluan';

                switch ($tab) {

                    case 'laporan_pendahuluan':
                        include "maternitas/pengkajian_ginekologi/halm_laporan_pendahuluan.php";
                        break;

                    case 'demografi':
                        include "maternitas/pengkajian_ginekologi/halm_tambah_demografi.php";
                        break;
                    case 'riwayat':
                        include "maternitas/pengkajian_ginekologi/halm_tambah_riwayat.php";
                        break;
                    case 'pengkajianfisik':
                        include "maternitas/pengkajian_ginekologi/halm_tambah_pengkajianfisik.php";
                        break;
                    case 'terapi_lab':
                        include "maternitas/pengkajian_ginekologi/halm_data_terapi_lab.php";
                        break;
                    case 'lainnya':
                        include "maternitas/pengkajian_ginekologi/halm_tambah_lainnya.php";
                        break;
                    default:
                        include "maternitas/pengkajian_ginekologi/halm_laporan_pendahuluan.php";
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
            // GERONTIK NEW
            // =====================
            case 'gerontik/gerontik':

                var_dump($tab);

                switch ($tab) {

                    case 'identitas':
                        include "gerontik/halm_tambah_identitas.php";
                        break;

                    case 'riwayat_kesehatan':
                        include "gerontik/halm_tambah_riwayat_kesehatan.php";
                        break;

                    case 'pemeriksaan_fisik':
                        include "gerontik/halm_tambah_pemeriksaan_fisik.php";
                        break;

                    case 'kebiasaan_harian':
                        include "gerontik/halm_tambah_kebiasaan_harian.php";
                        break;

                    case 'psikososial_spiritual':
                        include "gerontik/halm_tambah_psikososial_spiritual.php";
                        break;

                    case 'status_fungsional':
                        include "gerontik/halm_tambah_status_fungsional.php";
                        break;

                    case 'skala_depresi':
                        include "gerontik/halm_tambah_skala_depresi.php";
                        break;

                    case 'apgar_spmsq_risiko_jatuh':
                        include "gerontik/halm_tambah_apgar_spmsq_risiko_jatuh.php";
                        break;

                    case 'catatan_keperawatan':
                        include "gerontik/halm_tambah_catatan_keperawatan.php";
                        break;

                    default:
                        include "gerontik/halm_tambah_identitas.php";
                }

                break;
            // =====================
            // KMB Format KMB RUANG DAMAR
            // =====================
            case 'kmb/format_kmb_r_damar':

                $tab = $tab ?: 'format_askep';

                switch ($tab) {

                    case 'analisa_data':
                        include "kmb/format_kmb_r_damar/halm_tambah_analisa_data.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "kmb/format_kmb_r_damar/halm_tambah_diagnosa_keperawatan.php";
                        break;

                    case 'rencana':
                        include "kmb/format_kmb_r_damar/halm_tambah_rencana.php";
                        break;

                    case 'implementasi':
                        include "kmb/format_kmb_r_damar/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi':
                        include "kmb/format_kmb_r_damar/halm_tambah_evaluasi.php";
                        break;

                    /* New */
                    case 'konsep_keperawatan':
                        include "kmb/format_kmb_r_damar/halm_konsep_keperawatan.php";
                        break;

                    case 'pengkajian':
                        include "kmb/format_kmb_r_damar/halm_tambah_pengkajian.php";
                        break;

                    case 'pengkajian_gordon':
                        include "kmb/format_kmb_r_damar/halm_tambah_pengkajian_gordon.php";
                        break;


                    case 'data_biologis':
                        include "kmb/format_kmb_r_damar/halm_data_biologis.php";
                        break;
                    case 'data_biologis_1':
                        include "kmb/format_kmb_r_damar/halm_data_biologis_1.php";
                        break;
                    case 'klasifikasi_analisa_data':
                        include "kmb/format_kmb_r_damar/halm_klasifikasi_analisa_data.php";
                        break;

                    case 'data_biologis_2':
                        include "kmb/format_kmb_r_damar/halm_data_biologis_2.php";
                        break;
                    case 'data_biologis_3':
                        include "kmb/format_kmb_r_damar/halm_data_biologis_3.php";
                        break;
                    case 'klasifikasi_analisa_data':
                        include "kmb/format_kmb_r_damar/halm_klasifikasi_analisa_data.php";
                        break;

                    case 'lainnya':
                        include "kmb/format_kmb_r_damar/halm_tambah_lainnya.php";
                        break;

                    default:
                        include "kmb/format_kmb_r_damar/halm_konsep_keperawatan.php";
                }

                break;

            // =====================
            // KMB Format KMB RUANG ANGSANA
            // =====================
            case 'kmb/format_kmb_r_angsana':

                $tab = $tab ?: 'format_askep';

                switch ($tab) {

                    case 'analisa_data':
                        include "kmb/format_kmb_r_angsana/halm_tambah_analisa_data.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "kmb/format_kmb_r_angsana/halm_tambah_diagnosa_keperawatan.php";
                        break;

                    case 'rencana':
                        include "kmb/format_kmb_r_angsana/halm_tambah_rencana.php";
                        break;

                    case 'implementasi':
                        include "kmb/format_kmb_r_angsana/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi':
                        include "kmb/format_kmb_r_angsana/halm_tambah_evaluasi.php";
                        break;

                    /* New */
                    case 'konsep_keperawatan':
                        include "kmb/format_kmb_r_angsana/halm_konsep_keperawatan.php";
                        break;

                    case 'pengkajian':
                        include "kmb/format_kmb_r_angsana/halm_tambah_pengkajian.php";
                        break;

                    case 'pengkajian_gordon':
                        include "kmb/format_kmb_r_angsana/halm_tambah_pengkajian_gordon.php";
                        break;


                    case 'data_biologis':
                        include "kmb/format_kmb_r_angsana/halm_data_biologis.php";
                        break;
                    case 'data_biologis_1':
                        include "kmb/format_kmb_r_angsana/halm_data_biologis_1.php";
                        break;
                    case 'klasifikasi_analisa_data':
                        include "kmb/format_kmb_r_angsana/halm_klasifikasi_analisa_data.php";
                        break;

                    case 'data_biologis_2':
                        include "kmb/format_kmb_r_angsana/halm_data_biologis_2.php";
                        break;
                    case 'data_biologis_3':
                        include "kmb/format_kmb_r_angsana/halm_data_biologis_3.php";
                        break;
                    case 'klasifikasi_analisa_data':
                        include "kmb/format_kmb_r_angsana/halm_klasifikasi_analisa_data.php";
                        break;

                    case 'lainnya':
                        include "kmb/format_kmb_r_angsana/halm_tambah_lainnya.php";
                        break;

                    default:
                        include "kmb/format_kmb_r_angsana/halm_konsep_keperawatan.php";
                }

                break;

            // =====================
            // KMB Format KMB RUANG DAHLIA
            // =====================
            case 'kmb/format_kmb_r_dahlia':

                $tab = $tab ?: 'format_askep';

                switch ($tab) {

                    case 'analisa_data':
                        include "kmb/format_kmb_r_dahlia/halm_tambah_analisa_data.php";
                        break;

                    case 'diagnosa_keperawatan':
                        include "kmb/format_kmb_r_dahlia/halm_tambah_diagnosa_keperawatan.php";
                        break;

                    case 'rencana':
                        include "kmb/format_kmb_r_dahlia/halm_tambah_rencana.php";
                        break;

                    case 'implementasi':
                        include "kmb/format_kmb_r_dahlia/halm_tambah_implementasi.php";
                        break;

                    case 'evaluasi':
                        include "kmb/format_kmb_r_dahlia/halm_tambah_evaluasi.php";
                        break;

                    /* New */
                    case 'konsep_keperawatan':
                        include "kmb/format_kmb_r_dahlia/halm_konsep_keperawatan.php";
                        break;

                    case 'pengkajian':
                        include "kmb/format_kmb_r_dahlia/halm_tambah_pengkajian.php";
                        break;

                    case 'pengkajian_gordon':
                        include "kmb/format_kmb_r_dahlia/halm_tambah_pengkajian_gordon.php";
                        break;


                    case 'data_biologis':
                        include "kmb/format_kmb_r_dahlia/halm_data_biologis.php";
                        break;
                    case 'data_biologis_1':
                        include "kmb/format_kmb_r_dahlia/halm_data_biologis_1.php";
                        break;
                    case 'klasifikasi_analisa_data':
                        include "kmb/format_kmb_r_dahlia/halm_klasifikasi_analisa_data.php";
                        break;

                    case 'data_biologis_2':
                        include "kmb/format_kmb_r_dahlia/halm_data_biologis_2.php";
                        break;
                    case 'data_biologis_3':
                        include "kmb/format_kmb_r_dahlia/halm_data_biologis_3.php";
                        break;
                    case 'klasifikasi_analisa_data':
                        include "kmb/format_kmb_r_dahlia/halm_klasifikasi_analisa_data.php";
                        break;

                    case 'lainnya':
                        include "kmb/format_kmb_r_dahlia/halm_tambah_lainnya.php";
                        break;

                    default:
                        include "kmb/format_kmb_r_dahlia/halm_konsep_keperawatan.php";
                }

                break;


            // =====================
            // KMB Format KMB
            // =====================
            case 'kmb/format_kmb':

                $tab = $tab ?: 'format_askep';

                switch ($tab) {

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

                    /* New */
                    case 'konsep_keperawatan':
                        include "kmb/format_kmb/halm_konsep_keperawatan.php";
                        break;

                    case 'pengkajian':
                        include "kmb/format_kmb/halm_tambah_pengkajian.php";
                        break;

                    case 'pengkajian_gordon':
                        include "kmb/format_kmb/halm_tambah_pengkajian_gordon.php";
                        break;


                    case 'data_biologis':
                        include "kmb/format_kmb/halm_data_biologis.php";
                        break;
                    case 'data_biologis_1':
                        include "kmb/format_kmb/halm_data_biologis_1.php";
                        break;
                    case 'klasifikasi_analisa_data':
                        include "kmb/format_kmb/halm_klasifikasi_analisa_data.php";
                        break;

                    case 'data_biologis_2':
                        include "kmb/format_kmb/halm_data_biologis_2.php";
                        break;
                    case 'data_biologis_3':
                        include "kmb/format_kmb/halm_data_biologis_3.php";
                        break;
                    case 'klasifikasi_analisa_data':
                        include "kmb/format_kmb/halm_klasifikasi_analisa_data.php";
                        break;

                    case 'lainnya':
                        include "kmb/format_kmb/halm_tambah_lainnya.php";
                        break;

                    default:
                        include "kmb/format_kmb/halm_konsep_keperawatan.php";
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

                    case 'pengkajian':
                        include "kmb/format_hd_kmb/halm_tambah_pengkajian.php";
                        break;

                    case 'pemeriksaan_fisik':
                        include "kmb/format_hd_kmb/halm_tambah_pemeriksaan_fisik.php";
                        break;

                    case 'pengkajian_kebutuhan':
                        include "kmb/format_hd_kmb/halm_tambah_pengkajian_kebutuhan.php";
                        break;
                    case 'analisa_data':
                        include "kmb/format_hd_kmb/halm_analisa_data.php";
                        break;

                    case 'lainnya':
                        include "kmb/format_hd_kmb/halm_tambah_lainnya.php";
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

                    case 'lainnya':
                        include "kmb/pengkajian_ruang_ok/halm_tambah_lainnya.php";
                        break;

                    default:
                        include "kmb/pengkajian_ruang_ok/halm_tambah_lp_ruangok.php";
                }

                break;
            // =====================
            // KMB Pengkajian Ruang OK
            // =====================
            case 'kmb/format_poli_tb':

                $tab = $tab ?: 'resume';

                switch ($tab) {

                    case 'resume':
                        include "kmb/format_poli_tb/halm_tambah_resume.php";
                        break;

                    case 'analisa':
                        include "kmb/format_poli_tb/halm_tambah_analisa.php";
                        break;

                    case 'lainnya':
                        include "kmb/format_poli_tb/halm_tambah_lainnya.php";
                        break;

                    default:
                        include "kmb/format_poli_tb/halm_tambah_resume.php";
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

                    case 'pengkajianlanjutan':
                        include "jiwa/jiwa_rsud/halm_tambah_pengkajianlanjutan.php";
                        break;

                    case 'lainnya':
                        include "jiwa/jiwa_rsud/halm_tambah_lainnya.php";
                        break;

                    default:
                        include "jiwa/jiwa_rsud/halm_tambah_format_laporan_pendahuluan.php";
                }

                break;

            // =====================
            // Jiwa Poli Jiwa
            // =====================
            case 'jiwa/poli_jiwa':

                $tab = $tab ?: 'halm_tambah_praktik_klinik_keperawatan_jiwa';

                switch ($tab) {

                    case 'halm_tambah_praktik_klinik_keperawatan_jiwa':
                        include "jiwa/poli_jiwa/halm_tambah_praktik_klinik_keperawatan_jiwa.php";
                        break;

                    case 'format_resume':
                        include "jiwa/poli_jiwa/halm_tambah_format_resume.php";
                        break;

                    case 'lainnya':
                        include "jiwa/poli_jiwa/halm_tambah_lainnya.php";
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

                    case 'pengkajian_riwayat':
                        include "anak/format_anggrek/halm_tambah_pengkajian_riwayat.php";
                        break;
                    case 'pengkajian_fisik':
                        include "anak/format_anggrek/halm_tambah_pengkajian_fisik.php";
                        break;
                    case 'analisa_data':
                        include "anak/format_anggrek/halm_analisa_data.php";
                        break;
                    case 'lainnya':
                        include "anak/format_anggrek/halm_tambah_lainnya.php";
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

                    case 'identitas_riwayat':
                        include "anak/format_aster/halm_identitas_riwayat.php";
                        break;

                    case 'keadaan_bayi':
                        include "anak/format_aster/halm_keadaan_bayi.php";
                        break;

                    case 'pengkajian_umum':
                        include "anak/format_aster/halm_pengkajian_umum.php";
                        break;

                    case 'pengkajian_fisik_1':
                        include "anak/format_aster/halm_pengkajian_fisik_1.php";
                        break;
                    case 'pengkajian_fisik_2':
                        include "anak/format_aster/halm_pengkajian_fisik_2.php";
                        break;
                    case 'analisa_data':
                        include "anak/format_aster/halm_analisa_data.php";
                        break;
                    case 'lainnya':
                        include "anak/format_aster/halm_lainnya.php";
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

                    case 'resume_keperawatan':
                        include "anak/format_resume/halm_tambah_format_resume_keperawatan.php";
                        break;

                    case 'analisa_resume':
                        include "anak/format_resume/halm_tambah_analisa_resume.php";
                        break;

                    case 'lainnya_resume':
                        include "anak/format_resume/halm_tambah_lainnya_resume.php";
                        break;

                    case 'lp_imunisasi':
                        include "anak/format_resume/halm_tambah_format_laporan_pendahuluan_imunisasi.php";
                        break;

                    case 'poli_imunisasi':
                        include "anak/format_resume/halm_tambah_format_laporan_poli.php";
                        break;

                    case 'analisa_poli':
                        include "anak/format_resume/halm_tambah_analisa_poli.php";
                        break;

                    case 'lainnya_poli':
                        include "anak/format_resume/halm_tambah_lainnya_poli.php";
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
                if ($_SESSION['level'] == 'Dosen') {
                    include "halaman_dashboard.php";
                } else {
                    include "halaman_dashboard_mahasiswa.php";
                }
                break;
        }
    }

    // =====================
    // STAFF DOKTER
    // =====================
    elseif ($_SESSION['level'] == 'Admin') {

        include_once "sidebar_admin.php";

        switch ($page) {

            case 'keluar':
                include "auth/logout.php";
                break;

            case 'ganti_password':
                include "halaman_ganti_password.php";
                break;

            case 'dashboard':
                include "halaman_manage_user.php.php";
                break;
            case 'manage_user':
                include 'halaman_manage_user.php';
                break;

            case 'form_user':
                include 'halaman_form_user.php';
                break;

            default:
                include "halaman_manage_user.php";
        }
    }
} else {

    include_once "auth/login.php";
}

include_once "footer.php";
?>
