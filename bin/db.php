<?php
	require_once "libs/rb.php";
	require_once "config.php";
	R::setup('mysql:host='.$conf_host.';dbname='.$conf_db.'',
        $conf_user, $conf_pass);
?>