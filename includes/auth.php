<?php
session_start();
function is_authenticated() {
    return isset($_SESSION['user_id']);
}
function check_authentication() {
    if (!is_authenticated()) {
        header('Location: index.php');
        exit;
    }
}
?>
    