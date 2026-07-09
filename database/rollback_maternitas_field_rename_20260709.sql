-- Rollback untuk perubahan data lama Maternitas.
-- Jalankan hanya jika backup table berikut masih ada:
--   submission_sections_backup_20260709

UPDATE submission_sections ss
JOIN submission_sections_backup_20260709 b ON b.id = ss.id
SET ss.submission_id = b.submission_id,
    ss.section_name = b.section_name,
    ss.section_label = b.section_label,
    ss.data = b.data,
    ss.status = b.status,
    ss.dosen_review_status = b.dosen_review_status,
    ss.preceptor_review_status = b.preceptor_review_status,
    ss.updated_at = b.updated_at;

-- Opsional: hapus backup kalau rollback sudah selesai dan sudah diverifikasi.
-- DROP TABLE submission_sections_backup_20260709;
