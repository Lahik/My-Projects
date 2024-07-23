<?php

    include("../db/database.php");

    session_start();
    session_unset();
    session_destroy();

    header('location:../customer/home.php');

?>