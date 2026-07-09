-- Backfill data lama untuk perubahan key JSON dan section_name di Maternitas.
-- Jalankan setelah schema migration dan setelah backup table dibuat.
-- Backup table dipakai sebagai sumber rollback.

CREATE TABLE submission_sections_backup_20260709 AS
SELECT ss.*
FROM submission_sections ss
JOIN submissions s ON s.id = ss.submission_id
WHERE (
        s.form_id = 2
        AND ss.section_name IN ('identitas', 'data_biologis', 'riwayat_kehamilan', 'lainnya')
    )
   OR (
        s.form_id = 12
        AND ss.section_name IN ('data_demografi', 'riwayat_kehamilan_kesehatan', 'lainnya')
    )
;

-- Rename section_name dari "lainnya" ke "catatan_keperawatan".
UPDATE submission_sections ss
JOIN submissions s ON s.id = ss.submission_id
LEFT JOIN submission_sections existing
  ON existing.submission_id = ss.submission_id
 AND existing.section_name = 'catatan_keperawatan'
SET ss.section_name = 'catatan_keperawatan',
    ss.section_label = 'Catatan Keperawatan'
WHERE ss.section_name = 'lainnya'
  AND s.form_id IN (2, 12)
  AND existing.id IS NULL;

-- Pascapartum: identitas.diagnosa_medik -> identitas.diagnosamedik
UPDATE submission_sections ss
JOIN submissions s ON s.id = ss.submission_id
SET ss.data = JSON_SET(
    ss.data,
    '$.diagnosamedik', JSON_EXTRACT(ss.data, '$.diagnosa_medik')
)
WHERE s.form_id = 2
  AND ss.section_name = 'identitas'
  AND JSON_EXTRACT(ss.data, '$.diagnosa_medik') IS NOT NULL
  AND JSON_EXTRACT(ss.data, '$.diagnosamedik') IS NULL;

-- Ginekologi: data_demografi.diagnosa_medik -> data_demografi.diagnosamedik
UPDATE submission_sections ss
JOIN submissions s ON s.id = ss.submission_id
SET ss.data = JSON_SET(
    ss.data,
    '$.diagnosamedik', JSON_EXTRACT(ss.data, '$.diagnosa_medik')
)
WHERE s.form_id = 12
  AND ss.section_name = 'data_demografi'
  AND JSON_EXTRACT(ss.data, '$.diagnosa_medik') IS NOT NULL
  AND JSON_EXTRACT(ss.data, '$.diagnosamedik') IS NULL;

-- Pascapartum: data_biologis key rename.
UPDATE submission_sections ss
JOIN submissions s ON s.id = ss.submission_id
SET ss.data = JSON_SET(
    ss.data,
    '$.biologisfisiologis', JSON_EXTRACT(ss.data, '$.biologis_fisiologis'),
    '$.bayirawatgabung', JSON_EXTRACT(ss.data, '$.bayi_rawat_gabung'),
    '$.tidakadaalasan', JSON_EXTRACT(ss.data, '$.tidak_ada_alasan'),
    '$.keadaanumum', JSON_EXTRACT(ss.data, '$.keadaan_umum'),
    '$.bbtb', JSON_EXTRACT(ss.data, '$.bb_tb')
)
WHERE s.form_id = 2
  AND ss.section_name = 'data_biologis'
  AND (
      JSON_EXTRACT(ss.data, '$.biologis_fisiologis') IS NOT NULL
      OR JSON_EXTRACT(ss.data, '$.bayi_rawat_gabung') IS NOT NULL
      OR JSON_EXTRACT(ss.data, '$.tidak_ada_alasan') IS NOT NULL
      OR JSON_EXTRACT(ss.data, '$.keadaan_umum') IS NOT NULL
      OR JSON_EXTRACT(ss.data, '$.bb_tb') IS NOT NULL
  );

-- Ginekologi: riwayat_kehamilan_kesehatan.riwayat_ginekologi -> riwayatginekologi
UPDATE submission_sections ss
JOIN submissions s ON s.id = ss.submission_id
SET ss.data = JSON_SET(
    ss.data,
    '$.riwayatginekologi', JSON_EXTRACT(ss.data, '$.riwayat_ginekologi')
)
WHERE s.form_id = 12
  AND ss.section_name = 'riwayat_kehamilan_kesehatan'
  AND JSON_EXTRACT(ss.data, '$.riwayat_ginekologi') IS NOT NULL
  AND JSON_EXTRACT(ss.data, '$.riwayatginekologi') IS NULL;

-- Pascapartum: nested riwayat_kehamilan[*].menyesui_berapa_lama -> menyusui_berapa_lama.
UPDATE submission_sections ss
JOIN submissions s ON s.id = ss.submission_id
JOIN (
    SELECT ss2.id,
           JSON_ARRAYAGG(
               JSON_OBJECT(
                   'jenis_persalinan', jt.jenis_persalinan,
                   'penolong', jt.penolong,
                   'jenis_kelamin', jt.jenis_kelamin,
                   'bbtb_bayi', jt.bbtb_bayi,
                   'menyesui_berapa_lama', jt.menyesui_berapa_lama,
                   'menyusui_berapa_lama', COALESCE(jt.menyusui_berapa_lama, jt.menyesui_berapa_lama),
                   'masalah_kehamilan', jt.masalah_kehamilan
               )
               ORDER BY jt.ord
           ) AS riwayat_json
    FROM submission_sections ss2
    JOIN submissions s2 ON s2.id = ss2.submission_id
    JOIN JSON_TABLE(
        ss2.data,
        '$.riwayat[*]'
        COLUMNS (
            ord FOR ORDINALITY,
            jenis_persalinan VARCHAR(255) PATH '$.jenis_persalinan',
            penolong VARCHAR(255) PATH '$.penolong',
            jenis_kelamin VARCHAR(255) PATH '$.jenis_kelamin',
            bbtb_bayi VARCHAR(255) PATH '$.bbtb_bayi',
            menyesui_berapa_lama VARCHAR(255) PATH '$.menyesui_berapa_lama',
            menyusui_berapa_lama VARCHAR(255) PATH '$.menyusui_berapa_lama',
            masalah_kehamilan VARCHAR(255) PATH '$.masalah_kehamilan'
        )
    ) jt
    WHERE s2.form_id = 2
      AND ss2.section_name = 'riwayat_kehamilan'
      AND JSON_EXTRACT(ss2.data, '$.riwayat') IS NOT NULL
    GROUP BY ss2.id
) x ON x.id = ss.id
SET ss.data = JSON_SET(ss.data, '$.riwayat', CAST(x.riwayat_json AS JSON))
WHERE s.form_id = 2
  AND ss.section_name = 'riwayat_kehamilan';
