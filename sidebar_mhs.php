<?php
require_once 'utils.php';

$user_id = $_SESSION['id_user'];
// $forms ambil dari header.php yang sudah di-query sebelumnya
?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>


        <?php
        // Optional: icon mapping per department
        $deptIcons = [
            'Maternitas' => 'bi bi-person',
            'KMB' => 'bi bi-person-check',
            'Anak' => 'bi bi-emoji-laughing',
            'Jiwa' => 'bi bi-chat-left-dots',
        ];
        $currentPage = isset($_GET['page']) ? $_GET['page'] : '';
        foreach ($grouped as $dept => $formList):
            $navId = strtolower($dept) . '-nav';
            $icon = isset($deptIcons[$dept]) ? $deptIcons[$dept] : 'bi bi-folder';

            // Cek apakah salah satu child menu sedang aktif
            $isActiveParent = false;
            foreach ($formList as $form) {
                $slugCheck = strtolower($form['department']) . "/{$form['slug']}";
                if (strpos($currentPage, $slugCheck) === 0) {
                    $isActiveParent = true;
                    break;
                }
            }
        ?>
        <li class="nav-item">
            <a class="nav-link<?= $isActiveParent ? '' : ' collapsed' ?>" data-bs-target="#<?= $navId ?>" data-bs-toggle="collapse" href="#">
                <i class="<?= $icon ?>"></i>
                <span><?= htmlspecialchars($dept) ?></span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="<?= $navId ?>" class="nav-content collapse<?= $isActiveParent ? ' show' : '' ?>" data-bs-parent="#sidebar-nav">
                <?php foreach ($formList as $form):
                    $url = "index.php?page=" . strtolower($form['department']) . "/{$form['slug']}";
                ?>
                <li>
                    <a href="<?= htmlspecialchars($url) ?>">
                        <i class="bi bi-circle"></i>
                        <span><?= htmlspecialchars($form['form_name']) ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </li>
        <?php endforeach; ?>

    </ul>

</aside><!-- End Sidebar-->