<?php

require_once 'config/session.php';

if (isset($_SESSION['login'])) {
    header('Location: admin/dashboard.php');
    exit;
}

header('Location: beranda.php');
exit;
