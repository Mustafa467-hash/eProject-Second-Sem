<?php
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function flashMessage($type, $message) {
    $_SESSION['flash'][$type][] = $message;
}

function displayFlashMessages() {
    if (!isset($_SESSION['flash'])) return;

    foreach ($_SESSION['flash'] as $type => $messages) {
        foreach ($messages as $msg) {
            echo "<div class='alert alert-$type'>$msg</div>";
        }
    }

    // Clear after showing
    unset($_SESSION['flash']);
}
?>