<?php
  	require_once 'bin/functions.php';
  	require_once 'bin/config.php';

  	session_start();

	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		header("location: /");
		exit;
	}

	$data = $_POST;
	$action = isset($data['action']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/style.css">
	<title><?=$siteTitle?> - Авторизация</title>
</head>
	<body>
		<header>
			<div class="container">
				<div class="header-inner">
					<div class="logo">
						<img src="assets/img/logo1.png" alt="FusionMC">
						<!-- <div class="logo-text">
							<p>IP: play.fusion-mc.ru</p>
						</div> -->
					</div>
				</div>
			</div>
		</header>
		<section class="section-login">
			<div class="container">
				<div class="login">
					<div class="login-inner">
						<form action="/login" method="POST">
						<?php
							if($action) {
								$user = R::findOne('users', 'username = ?', array($data['username']));
								$was_successful = false;

								if($user)
								{
									//логин существует
									if (password_verify($data['password'], $user->password)){
										//логиним
										$was_successful = true;
										$_SESSION['loggedin'] = true;
										$_SESSION['username'] = $user->username;
										echo '<meta http-equiv="refresh" content="1;URL=/lk" />';
									} else {
										$was_successful = false;
									}
								} else {
									$was_successful = false;
								}
							}
						?>
							<div class="login-head">
								<div class="login-head-inner">
									<a href="/">
										<img src="assets/img/back.png" alt="Назад">
										<div class="login-head-text">Назад</div>
									</a>
								</div>
							</div>
							<div class="login-body">
								<div class="login-input">
									<input type="text" name="username" placeholder="Логин" required>
								</div>
								<div class="login-input">
									<input type="password" name="password" placeholder="Пароль" required>
								</div>
							</div>
							<div class="login-footer">
								<div class="login-footer-text">Авторизация</div>
								<div class="login-button">
									<button type="submit" name="action" value="login">Войти</button>
								</div>
							</div>
						</form>
					</div>
					<div class="login-message">
						<?php if (($action) && !$was_successful): ?>
							<p class="message-error">Вы указали неверный логин или пароль</p>
						<?php elseif (($action) && $was_successful): ?>
							<p class="message-success">Вы успешно вошли в аккаунт</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>
	</body>
</html>