-- Backfill data review lama untuk production.
-- Jalankan setelah `add_review_status_fields.sql` dan `add_preceptor_review_fields.sql`.

UPDATE submissions s
JOIN tbl_user u ON u.id_user = s.reviewed_by
SET
  s.dosen_review_status = s.status,
  s.dosen_reviewed_by = s.reviewed_by,
  s.dosen_reviewed_at = COALESCE(s.reviewed_at, s.submitted_at, s.updated_at)
WHERE u.level = 'Dosen'
  AND s.reviewed_by IS NOT NULL
  AND s.dosen_review_status IS NULL;

-- Untuk data lama yang kemungkinan direview Preceptor, isi manual setelah verifikasi.
