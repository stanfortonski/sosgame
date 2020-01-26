<!doctype html>
<html lang="pl">
<head>
	<title><?php echo $window->getTitle(); ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="gra, przeglądarkowa, za darmo, game, web browser, browser, for free, rpg, mmo, mmorpg, team android, ios, webos, tv, online">
	<meta name="author" content="Stasiek Fortoński">

	<?php echo $window->content()->get('meta'); ?>
	<?php echo $styles; ?>
</head>
<body>
	<header class="navbar navbar-dark navbar-expand-md" role="navigation">
		<a href="<?php echo SOS\Config::get()->path('main') ?>" class="navbar-brand">SOSGAME</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-expanded="false" aria-controls="mainmanu" aria-label="Przełącznik nawigacji">
			<span class="navbar-toggler-icon">
		</button>

		<div class="navbar-collapse collapse justify-content-between" id="mainmenu">
			<ul class="navbar-nav">
				<?php echo $window->content()->get('navigation'); ?>
			</ul>

			<ul class="navbar-nav">
				<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="user-submenu">
					<img id="avatar-radius" src="<?php echo $window->content()->get('icon'); ?>">
				</a>

				<nav class="dropdown-menu dropdown-menu-right" aria-labelledby="user-submenu">
					<?php echo $window->content()->get('user-navigation'); ?>
				</nav>
			</li>
			</ul>
		</div>
	</header>

	<?php echo $window->content()->get('structure'); ?>

	<footer class="container-fluid">
		<div class="row">
			<div class="col mb-2 mx-sm-2 ad bg-primary">ADVERTISE HERE</div>
		</div>
		<div class="row">
			<div class="col footer">SOSGAME &copy; <?php echo '2018 - '.date('Y'); ?></div>
		</div>
	</footer>
	<?php echo $scripts; ?>
</body>
</html>
