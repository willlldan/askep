
<?php
// utils/form_helpers.php
// Helper untuk parsing dan validasi data dinamis (klasifikasi, analisa, dsb)

// Helper untuk parsing field datar (bukan array rows)
function parse_dynamic_fields($post_data, $fields)
{
    $result = [];
    foreach ($fields as $f) {
        $result[$f] = isset($post_data[$f]) ? $post_data[$f] : '';
    }
    return $result;
}

function parse_dynamic_rows($post_data, $fields)
{
    $result = [];
    if (!empty($post_data)) {
        foreach ($post_data as $row) {
            $empty = true;
            $parsed = [];
            foreach ($fields as $field) {
                $val = isset($row[$field]) ? $row[$field] : '';
                $parsed[$field] = $val;
                if ($val !== '') $empty = false;
            }
            if (!$empty) $result[] = $parsed;
        }
    }
    return $result;
}

// Helper untuk proses approval/revisi reviewer
function handle_dosen_action($submission_id, $section_name, $action, $comment, $reviewer_id, $reviewer_role, $mysqli)
{
    if ($action === 'approve') {
        updateSectionStatus($submission_id, $section_name, 'approved', $mysqli, $reviewer_role);
        if (!empty($comment)) {
            saveComment($submission_id, $section_name, $comment, $reviewer_id, $mysqli);
        }
    } elseif ($action === 'revision') {
        if (empty($comment)) {
            return ['error' => 'Komentar wajib diisi saat meminta revisi.'];
        }
        updateSectionStatus($submission_id, $section_name, 'revision', $mysqli, $reviewer_role);
        saveComment($submission_id, $section_name, $comment, $reviewer_id, $mysqli);
    } elseif ($action === 'cancel_approval') {
        updateSectionStatus($submission_id, $section_name, 'draft', $mysqli, $reviewer_role);
    }
    updateReviewer($submission_id, $reviewer_id, $mysqli, $reviewer_role, $action);
    return [];
}

    // Helper untuk parsing field checkbox (menghasilkan json_encode array untuk setiap field)
    function parse_dynamic_checkboxes($post_data, $checkbox_fields) {
        $result = [];
        foreach ($checkbox_fields as $cf) {
            $result[$cf] = json_encode(isset($post_data[$cf]) ? (array)$post_data[$cf] : []);
        }
        return $result;
    }
