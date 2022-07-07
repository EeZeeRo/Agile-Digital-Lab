<?php
	$conf_host = 'localhost'; // Хост базы
	$conf_port = 3306; // порт базы
	$conf_user = 'root'; // пользователь базы
	$conf_pass = 'guysesman123'; // пароль базы
	$conf_db = 'fiuzeon'; // база базы :)

	$siteTitle = 'FusionMC'; // заголовок сайта
	// $streamers = ["EeZeeRo", "Stilever_", "Cookie", "AMADEY_"]; // Скины для ютуберов на красном блоке (должны быть в skins restorer базе)
	// $supportMoney = 150; // Сумма для поддержки сервера на синем блоке

	$promo_mask = 'XXXX-5555-XXXX-5555'; // Вместо символа mask_char будет подставлена случайная буква или цифра
	$mask_char = 'X'; //Символ который будет заменяться на случайную букву или цифру
	$chars = '12345ABCDEFGHIJKLMNOPQRSTUVWXYZ67890'; // Символы для генерации промокода

	$role_colors = array(
		'#0000BE' => '&1',
		'#00BE00' => '&2',
		'#00BEBE' => '&3',
		'#BE0000' => '&4',
		'#BE00BE' => '&5',
		'#F4A000' => '&6',
		'#BEBEBE' => '&7',
		'#3F3F3F' => '&8',
		'#3F3FFE' => '&9',
		'#000000' => '&0',
		'#FE3F3F' => '&c',
		'#FEFE3F' => '&e',
		'#3FFE3F' => '&a',
		'#3FFEFE' => '&b',
		'#FE3FFE' => '&d',
	);

	$pages = [
		'admin',
		'notifications',
		'settings',
		'news',
		'friends',
		'requests',
		'bank',
		'map',
		'delivery'
	];

	// $frnd_main_count = 3; // Список друзей для вывода на главной странице слева

	$count_last_transfers = 5; // Количество последних транзакций в банке

	//ВК авторизация
	$vk_client_id = 8056579; // ID приложения
	$vk_client_secret = '2z1wMeav7WoXIG7TxL69'; // Защищённый ключ
	$vk_redirect_uri = 'http://web.fusion-mc.ru/api/vk_auth'; // Адрес сайта

	// //Ютуб авторизация
	// $yt_cliend_id = '592390858985-un9ijejud02slam0u5st7cicrpjeqpcl.apps.googleusercontent.com';
	// $yt_redirect_uri = 'http://web.fusion-mc.ru/api/vk_youtube';
	// $yt_scope = 'https://www.googleapis.com/auth/youtube.readonly';

	// // https://accounts.google.com/o/oauth2/v2/auth?client_id=592390858985-un9ijejud02slam0u5st7cicrpjeqpcl.apps.googleusercontent.com&redirect_uri=http://web.fusion-mc.ru&response_type=code&scope=https://www.googleapis.com/auth/youtube.readonly

	// //Твич авторизация
	// $twitch_client_id = 'dummod30hij8pr3nanrnp5cnd91476';
	// $twitch_secret = '9t4ddwcd0mdfze6jrm25q1j83febkr';
	// $twitch_redirect_uri = 'https://web.fusion-mc.ru/api/tw_auth';
	// $twitch_scope = 'channel:read:stream_key';
?>