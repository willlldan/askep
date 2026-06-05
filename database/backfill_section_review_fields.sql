-- Backfill status reviewer per section untuk production.
-- Jalankan setelah `add_section_review_fields.sql`.

UPDATE submission_sections
SET
  dosen_review_status = status,
  preceptor_review_status = status
WHERE dosen_review_status = 'draft' AND preceptor_review_status = 'draft';
