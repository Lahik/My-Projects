<?php
    session_start();
    session_unset();
    session_destroy();
    setcookie('remember_admin', '', time() + -1, "/");

    header('location:../admin/admin_login.php');
?>