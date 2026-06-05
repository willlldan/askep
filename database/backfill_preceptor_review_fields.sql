-- Backfill data lama untuk skema review multi-role.
-- Jalankan setelah `add_preceptor_review_fields.sql`.
-- Aman untuk production karena hanya mengisi kolom yang masih NULL.

-- Isi field reviewer dosen dari reviewed_by lama, hanya jika reviewer-nya memang level Dosen.
UPDATE submissions s
JOIN tbl_user u ON u.id_user = s.reviewed_by
SET
  s.dosen_reviewed_by = s.reviewed_by,
  s.dosen_reviewed_at = COALESCE(s.reviewed_at, s.submitted_at, s.updated_at)
WHERE u.level = 'Dosen'
  AND s.reviewed_by IS NOT NULL
  AND s.dosen_reviewed_by IS NULL;

-- Jika ada data lama yang reviewed_by-nya bukan Dosen, biarkan NULL dulu.
-- Nanti bisa diisi manual kalau memang diketahui reviewer aslinya.
