-- Backfill role Preceptor untuk user lama.
-- Jalankan setelah `add_preceptor_level.sql`.

UPDATE tbl_user
SET level = 'Preceptor'
WHERE password = 'preceptor'
  AND level <> 'Preceptor';

-- Verifikasi dulu kalau mau:
-- SELECT id_user, nama, username, password, level FROM tbl_user WHERE password = 'preceptor';
