<?php
    session_start();

    if (isset($_POST['timezone']))
    {
        $_SESSION['tz'] = $_POST['timezone'];
        exit;
    }
?>