<?php

require_once "bin/db.php";
session_start();

$_SESSION = array();
session_destroy();

header("location: /");
exit;

?>