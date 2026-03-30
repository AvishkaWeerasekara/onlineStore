<?php
// logout.php — Clears session and goes to homepage
session_start();
session_destroy();
header('Location: index.php');
exit();
?>
