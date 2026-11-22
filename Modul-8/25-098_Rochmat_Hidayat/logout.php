<?php
require_once 'koneksi.php';

$_SESSION = [];
session_unset();
session_destroy();

header("Location: login.php?pesan=logout");
exit;
