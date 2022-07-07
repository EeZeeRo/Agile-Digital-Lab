<?php
	require_once 'bin/functions.php';
    require_once 'bin/config.php';
    session_start();

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false){
        $hasSession = false;
    } else {
        $hasSession = true;
    }

    $login = $_SESSION['username'];
    $online = R::getAll("SELECT * FROM (SELECT * FROM `online` ORDER BY `id` DESC LIMIT 30) t ORDER BY id");
    $players = R::getAll("SELECT `id`, `username` FROM `users` ORDER BY `id`");
    $jsonpl = json_encode($players);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$siteTitle?></title>
	<link rel="stylesheet" href="assets/css/style.css">
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
				<nav>
					<ul class="nav-menu">
						<li class="nav-item">
                            <?php
                                if ($hasSession):
                            ?>
								<a href="/u/<?=strtolower($login)?>">
                                    <div class="main-button">
                                        Личный кабинет
                                    </div>
                                </a>
                            <?php else: ?>
                                <a href="/login">
                                    <div class="main-button">
                                        Авторизация
                                    </div>
                                </a>
                            <?php endif; ?>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</header>
	<section class="section-main">
		<div class="container">
			<div class="main">
				<div class="main-left">
					<div class="site-title">
					Добро пожаловать на<br>
					один из крупнейших<br>
					серверов по Minecraft - <span class="site-main">Fusion</span>
					</div>
					<div class="main-button">
						<a href="#servers">
							<p>Начать играть</p>
						</a>
					</div>
				</div>
				<div class="main-right">
					<img src="assets/img/planet.png" alt="Планета">
				</div>
			</div>
		</div>
	</section>
	<section class="section-servers" id="servers">
		<div class="container">
			<div class="section-title">Наши сервера</div>
			<div class="servers">
				<div class="server">
					<div class="server-head">
						<div class="server-logo">
							<img src="assets/img/fusion.png" alt="Пещеры&Скалы">
						</div>
						<div class="server-button" id="fusion">
							<a href="/info/fusion">
								<p>Подробнее</p>
							</a>
						</div>
					</div>
					<div class="server-body">
						<div class="server-stat">
						    <div class="chart" id="charter1"></div>
						</div>
					</div>
				</div>
				<div class="server">
					<div class="server-head">
						<div class="server-logo">
							<img src="assets/img/kingdom.png" alt="Пещеры&Скалы">
						</div>
						<div class="server-button" id="kingdom">
							<a href="/info/kingdom">
								<p>Подробнее</p>
							</a>
						</div>
					</div>
					<div class="server-body">
						<div class="server-stat">
						    <div class="chart" id="charter2"></div>
						</div>
					</div>
				</div>
				<div class="server">
					<div class="server-head">
						<div class="server-logo">
							<img src="assets/img/creative.png" alt="Пещеры&Скалы">
						</div>
						<div class="server-button" id="creative">
							<a href="/info/creative">
								<p>Подробнее</p>
							</a>
						</div>
					</div>
					<div class="server-body">
						<div class="server-stat">
						    <div class="chart" id="charter3"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.seriesTypes.line.prototype.getPointSpline = Highcharts.seriesTypes.spline.prototype.getPointSpline;

            Highcharts.chart('charter1', {
                chart: {
                    type: 'area',
                    events: null,
                    height: 150,
                    navigations: {
                        events: null,
                    }
                },
                credits: {
                    enabled: 0
                },
                xAxis: {
                  type: 'datetime',
                },
                plotOptions: {
                    area: {
                        color: '#FF7466',
                        fillColor: {
                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },
                            stops: [
                                [0, Highcharts.getOptions().colors[10]],
                                [1, Highcharts.color(Highcharts.getOptions().colors[10]).setOpacity(0).get('rgba')]
                            ]
                        },
                        marker: {
                            radius: -7
                        },
                        lineWidth: 4,
                        states: {
                            hover: {
                                lineWidth: 4
                            }
                        },
                        threshold: null
                    },
                    spline: {
                      lineWidth: 4,
                      states: {
                        hover: {
                          lineWidth: 4
                        }
                      },
                      marker: {
                        enabled: false
                      },
                      pointInterval: 600000, // ten minutes
                      pointStart: 1641920735/1000
                    }
                },
                series: [{
                    name: 'Игроки',
                    data: [
                      <?php 
                        foreach ($online as $row){
                            $onlPl = $row['online'];
                            $onlTime = $row['time'];

                            echo "{x:" . $onlTime . ", y:" . $onlPl . ",},";
                        }

                      ?>
                    ]
                  }],
            });
        });

        document.addEventListener('DOMContentLoaded', function () {

            Highcharts.chart('charter2', {
                chart: {
                    type: 'area',
                    events: null,
                    height: 150,
                    navigations: {
                        events: null,
                    }
                },
                credits: {
                    enabled: 0
                },
                xAxis: {
                  type: 'datetime',
                },
                plotOptions: {
                    area: {
                        color: '#9C54C8',
                        fillColor: {
                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },
                            stops: [
                                [0, Highcharts.getOptions().colors[11]],
                                [1, Highcharts.color(Highcharts.getOptions().colors[11]).setOpacity(0).get('rgba')]
                            ]
                        },
                        marker: {
                            radius: -7
                        },
                        lineWidth: 4,
                        states: {
                            hover: {
                                lineWidth: 4
                            }
                        },
                        threshold: null
                    },
                    spline: {
                      lineWidth: 4,
                      states: {
                        hover: {
                          lineWidth: 4
                        }
                      },
                      marker: {
                        enabled: false
                      },
                      pointInterval: 600000, // ten minutes
                      pointStart: 1641920735/1000
                    }
                },
                series: [{
                    name: 'Игроки',
                    data: [
                      <?php 
                        foreach ($online as $row){
                            $onlPl = $row['online'];
                            $onlTime = $row['time'];

                            echo "{x:" . $onlTime . ", y:" . $onlPl . ",},";
                        }

                      ?>
                    ]
                  }],
            });
        });

        document.addEventListener('DOMContentLoaded', function () {

            Highcharts.chart('charter3', {
                chart: {
                    type: 'area',
                    events: null,
                    height: 150,
                    navigations: {
                        events: null,
                    }
                },
                credits: {
                    enabled: 0
                },
                xAxis: {
                  type: 'datetime',
                },
                plotOptions: {
                    area: {
                        color: '#2396C7',
                        fillColor: {
                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },
                            stops: [
                                [0, Highcharts.getOptions().colors[12]],
                                [1, Highcharts.color(Highcharts.getOptions().colors[12]).setOpacity(0).get('rgba')]
                            ]
                        },
                        marker: {
                            radius: -7
                        },
                        lineWidth: 4,
                        states: {
                            hover: {
                                lineWidth: 4
                            }
                        },
                        threshold: null
                    },
                    spline: {
                      lineWidth: 4,
                      states: {
                        hover: {
                          lineWidth: 4
                        }
                      },
                      marker: {
                        enabled: false
                      },
                      pointInterval: 600000, // ten minutes
                      pointStart: 1641920735/1000
                    }
                },
                series: [{
                    name: 'Игроки',
                    data: [
                      <?php 
                        foreach ($online as $row){
                            $onlPl = $row['online'];
                            $onlTime = $row['time'];

                            echo "{x:" . $onlTime . ", y:" . $onlPl . ",},";
                        }

                      ?>
                    ]
                  }],
            });
        });
    </script>
	<script src="assets/js/highcharts/highcharts.js"></script>
    <script src="assets/js/highcharts/series-label.js"></script>
    <script src="assets/js/highcharts/exporting.js"></script>
    <script src="assets/js/highcharts/export-data.js"></script>
    <script src="assets/js/highcharts/accessibility.js"></script>
</body>
</html>