<?php
    require_once '../bin/functions.php';
    require_once '../bin/config.php';
    session_start();

    if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== false){
		header("Location: ../");
		exit;
	}
    
    if ($_SERVER['REQUEST_URI'] == '/') {
        $Page = '';
        $ModuleOne = '';
    } else {
        $URL_Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $URL_Parts = explode('/', trim($URL_Path, '/'));
        $Page = array_shift($URL_Parts);
        $ModuleOne = array_shift($URL_Parts);
    }

    $user = $ModuleOne;
    $reallogin = R::getRow("SELECT `username` FROM `users` WHERE `realname` = '".strtolower($user)."'");
    
    $login = $reallogin['username'];
    $session_login = $_SESSION['username'];

    if (!$reallogin) {
        header("Location: /u/".strtolower($session_login)."");
		exit;
    }

    if ($login == $session_login) {
        $itsMe = true;
    } else {
        $itsMe = false;
    }

    if (isUserOnline($login)) {
        $online = 'online';
        $isOnline = 1;
    } else {
        $online = 'offline';
        $isOnline = 0;
    }

    $roles = getUserRolesFull($login);

    $lastlogin = getUserLastLogin($login);

    $lastDay = date('j', $lastlogin);
    $lastMonth = date('n', $lastlogin);
    $lastYear = date('Y', $lastlogin);

    $today = date('j');
    $tomonth = date('n');
    $toyear = date('Y');

    $now = time();
    $strLast = substr($lastlogin, 0, -3);

    $yesterDay = date('j', time() - (60 * 60 * 24));
    $dayBeforeYester = date('j', time() - (60 * 60 * 24 * 2));

    $lastTime = date(strtotime(date("Y-m-d", $strLast)));
    $daysDiff = $now - $lastTime;
    $daysBack = floor($daysDiff / (60 * 60 * 24));

    if (substr($daysBack, -1) > 1 && substr($daysBack, -1) <= 4){
        $dayStatus = 'дня';
    } elseif (substr($daysBack, -2, 1) > 1 && substr($daysBack, -1) == 1){
        $dayStatus = 'день';
    } elseif ((substr($daysBack, -1) > 4) || (substr($daysBack, -2, 1) == 1 && substr($daysBack, -1) == 1)){
        $dayStatus = 'дней';
    }

    if (($lastDay == $today && $lastMonth == $tomonth && $lastYear == $toyear) || (substr($daysBack, -1) == 0)){
        $lastOnlStatus = 'Сегодня в '. date('g:i', $lastlogin);
    } elseif (($lastDay == $yesterDay && $lastMonth == $tomonth && $lastYear == $toyear) || (substr($daysBack, -1) == 1)) {
        $lastOnlStatus = 'Вчера в '. date('g:i', $lastlogin);
    } elseif (($lastDay == $dayBeforeYester && $lastMonth == $tomonth && $lastYear == $toyear) || (substr($daysBack, -1) == 2)){
        $lastOnlStatus = 'Позавчера в '. date('g:i', $lastlogin);
    } else {
        $lastOnlStatus = $daysBack.' '.$dayStatus.' назад';
    }

    if ($isOnline == 1){
        $onlstatus = 'В сети';
    } else {
        $onlstatus = 'Был в сети '. $lastOnlStatus;
    }

    $pluuid = R::getRow("SELECT `uuid` FROM `web__player` WHERE `name` = '".$login."' ");
    $pltimestamp = R::getRow("SELECT `val` FROM web__stats WHERE `uuid` = '".$pluuid['uuid']."' AND `stat` = 'PLAY_ONE_MINUTE' ");
    $pldeathcnt = R::getRow("SELECT `val` FROM web__stats WHERE `uuid` = '".$pluuid['uuid']."' AND `stat` = 'DEATHS' ");
    $plkillscnt = R::getRow("SELECT `val` FROM web__stats WHERE `uuid` = '".$pluuid['uuid']."' AND `stat` = 'MOB_KILLS' ");
    $pltime = $pltimestamp['val'];
    $pldeaths = $pldeathcnt['val'];
    $playtime = round((($pltime/20)/60)/60, 1).'ч';

    $tableTitle = R::getAll("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'hours' AND ORDINAL_POSITION > 3");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title><?=$siteTitle?> - Кабинет</title>
</head>
<body>
    <header>
		<div class="container">
			<div class="header-inner">
				<div class="logo">
					<a href="../"><img src="../assets/img/logo1.png" alt="FusionMC"></a>
				</div>
				<nav>
					<ul class="nav-menu">
						<li class="nav-item">
                            <div class="nav-user" id="nav-head">
                                <img src="https://crafatar.com/avatars/<?=getUserAvatar($session_login);?>?overlay">
                            </div>
                            <div class="nav-popup" id="nav-popup">
                                <div class="nav-popup-element">
                                <a href="notifications">
                                        <div class="nav-popup-icon">
                                            <img src="../assets/img/notifications.png" alt="Уведомления">
                                        </div>
                                        <div class="nav-popup-text">
                                            <p>Уведомления</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="nav-popup-element">
                                <a href="settings">
                                        <div class="nav-popup-icon">
                                            <img src="../assets/img/settings.png" alt="Настройки">
                                        </div>
                                        <div class="nav-popup-text">
                                            <p>Настройки</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="nav-popup-element">
                                    <a href="../logout">
                                        <div class="nav-popup-icon">
                                            <img src="../assets/img/logout.png" alt="Выход">
                                        </div>
                                        <div class="nav-popup-text">
                                            <p>Выход</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</header>
    <section class="section-profile">
        <div class="container">
            <div class="profile-inner">
                <nav class="left-nav-menu">
                    <ul class="left-nav-items">
                        <li class="left-nav-item">
                            <a href="/u/<?=strtolower($session_login)?>">
                                <img src="../assets/img/user.png">
                                <p id="#profile">Профиль</p>
                            </a>
                        </li>
                        <li class="left-nav-item">
                            <a href="/lk/news">
                                <img src="../assets/img/news.png">
                                <p id="#news">Новости</p>
                            </a>
                        </li>
                        <li class="left-nav-item">
                            <a href="/lk/friends">
                                <img src="../assets/img/friends.png">
                                <p id="#friends">Друзья</p>
                            </a>
                        </li>
                        <li class="left-nav-item">
                            <a href="/lk/bank">
                                <img src="../assets/img/bank.png">
                                <p id="#bank">Банк</p>
                            </a>
                        </li>
                        <li class="left-nav-item">
                            <a href="/lk/city">
                                <img src="../assets/img/cities.png">
                                <p id="#city">Город</p>
                            </a>
                        </li>
                        <li class="left-nav-item">
                            <a href="/lk/map">
                                <img src="../assets/img/map.png">
                                <p id="#map">Карта</p>
                            </a>
                        </li>
                        <li class="left-nav-item">
                            <a href="/lk/delivery">
                                <img src="../assets/img/delivery.png">
                                <p id="#delivery">Доставка</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="profile">
                    <div class="profile-inner">
                        <div class="profile-left">
                            <div class="profile-user">
                                <img src="https://crafatar.com/avatars/<?=getUserAvatar($login);?>?overlay">
                            </div>
                            <?php if ($itsMe): ?>
                            <div class="profile-edit">
                                <a href="/lk/edit">
                                    <p>Редактировать</p>
                                </a>
                            </div>
                            <?php endif; ?>
                            <?php if (isUserAdmin($session_login)): ?>
                                <div class="profile-edit">
                                    <a href="../admin/manage/<?=strtolower($login)?>">
                                        <p>Управление</p>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="profile-groups">
                                <?php foreach ($roles as $role): 
                                    $color = getColorByCodeInColorList($role['color'], $role_colors);
                                    $rgb = hexToRGB($color);
                                ?>
                                <div class="profile-group">
                                    <p style="color: <?=$color?>;
                                    background: rgba(<?=$rgb?>, 0.2);
                                    box-shadow: 0px 0px 5px rgba(<?=$rgb?>, 0.25);">
                                        <?=$role['role']?>
                                    </p>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="profile-title">Банковские карты</div>
                            <div class="profile-cards">
                                <div class="profile-card" id="blue-card">
                                    <div class="card-inner">
                                        <div class="profile-card-balance">
                                            23 АР
                                        </div>
                                        <div class="profile-card-number">
                                            123123
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-card" id="green-card">
                                    <div class="card-inner">
                                        <div class="profile-card-balance">
                                            1024 АР
                                        </div>
                                        <div class="profile-card-number">
                                            321321
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-right">
                            <div class="profile-username">
                                <?=$login?>
                            </div>
                            <div class="profile-status">
                                <div class="online-circle" id="<?=$online?>"></div>
                                <p class="online-text"><?=$onlstatus?></p>
                            </div>
                            <div class="profile-played">
                                Наиграно: <?=$playtime?>
                            </div>
                            <div class="profile-deaths">
                                Смертей: <?=$pldeaths?>
                            </div>
                            <div class="profile-city">
                                <div class="profile-city-inner">
                                    <div class="profile-city-head">
                                        <div class="profile-city-img">
                                            <img src="https://avatars.mds.yandex.net/get-zen_doc/1588093/pub_5d65226f2f1e4409e4e405ef_5d652adcddfef600ae0911ca/scale_1200">
                                        </div>
                                        <div class="profile-city-title">
                                            <div class="profile-city-name">
                                                бебраГРАД
                                            </div>
                                            <div class="profile-city-owner">
                                                __Palpatine__
                                            </div>
                                            <div class="profile-city-route">
                                                <div class="city-route-name">
                                                    Зелёная ветка
                                                </div>
                                                <div class="city-route-coord">
                                                    54
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-city-description">
                                        Город для творческих личностей.
                                        Здесь ты можешь реализовать любые
                                        свои идеи. 
                                    </div>
                                </div>
                            </div>
                            <div class="profile-friends">
                                <div class="profile-friends-inner">
                                    <div class="profile-friends-head">
                                        <a href="/lk/friends">
                                            <div class="profile-friends-title">
                                                Друзья
                                            </div>
                                        </a>
                                        <div class="profile-friends-count">
                                            <p><?=getAllFriends($login, false)?></p>
                                        </div>
                                    </div>
                                    <div class="profile-friends-list">
                                        <?php
                                            $friends = getLastFriends($login, 3);
                                            foreach ($friends as $friend):

                                            if (isUserOnline($friend['username'])) {
                                                $friend_online = 'online';
                                            } else {
                                                $friend_online = 'offline';
                                            }
                                        ?>
                                        <div class="profile-friend">
                                            <a href="/u/<?=strtolower($friend['username'])?>">
                                                <div class="profile-friend-avatar">
                                                    <img src="https://crafatar.com/avatars/<?=getUserAvatar($friend['username'])?>?overlay">
                                                </div>
                                                <div class="profile-friend-circle" id="<?=$friend_online?>"></div>
                                                <div class="profile-friend-name">
                                                    <?=$friend['username']?>
                                                </div>
                                            </a>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (getAllRequests($login, false) > 0 && $login == $session_login): ?>
                            <div class="profile-friends">
                                <div class="profile-friends-inner">
                                    <div class="profile-friends-head">
                                        <a href="/lk/requests">
                                            <div class="profile-friends-title">
                                                Запросы
                                            </div>
                                        </a>
                                        <div class="profile-friends-count">
                                            <p><?=getAllRequests($login, false)?></p>
                                        </div>
                                    </div>
                                    <div class="profile-friends-list">
                                        <?php
                                            $requests = getLastRequests($login, 3);
                                            foreach ($requests as $req):

                                            if (isUserOnline($req['username'])) {
                                                $req_online = 'online';
                                            } else {
                                                $req_online = 'offline';
                                            }
                                        ?>
                                        <div class="profile-friend">
                                            <a href="/u/<?=strtolower($req['username'])?>">
                                                <div class="profile-friend-avatar">
                                                    <img src="https://crafatar.com/avatars/<?=getUserAvatar($req['username'])?>?overlay">
                                                </div>
                                                <div class="profile-friend-circle" id="<?=$req_online?>"></div>
                                                <div class="profile-friend-name">
                                                    <?=$req['username']?>
                                                </div>
                                            </a>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="profile-title">Активность</div>
                    <div class="profile-activity">
                        <?php
                            foreach($tableTitle as $tbitem):
                                $tbcolumn = $tbitem['COLUMN_NAME'];
                                $tbtime = R::getRow("SELECT * FROM (SELECT `".$tbcolumn."` FROM `hours` WHERE `nick` = '".$login."'ORDER BY `".$tbcolumn."` DESC LIMIT 70) t ORDER BY `".$tbcolumn."` ");
                        ?>
                            <?php
                                foreach($tbtime as $titem):
                                    $cell_color = $titem/24;
                            ?>
                                <div class="profile-activity-cell" style="background: rgba(35, 150, 199, <?=$cell_color?>);"></div>
                            <?php
                                endforeach;
                            ?>
                        <?php
                            endforeach;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="../assets/js/main.js"></script>
</body>
</html>