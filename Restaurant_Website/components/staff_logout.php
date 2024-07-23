<?php

    include('../db/database.php');

    session_start();
    session_unset();
    session_destroy();

    header('location:../staff/staff_login.php');

?>