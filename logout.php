<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php");
setcookie("remember_username", "", time() - 3600, "/");
setcookie("remember_role", "", time() - 3600, "/");

exit;
