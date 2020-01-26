<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<?php if ($window->getTitle() != 'Panel')
			echo '<li class="breadcrumb-item"><a href="index">Panel</a></li>';
			echo $window->content()->get('breadcrumb');
		?>
		<li class="breadcrumb-item active" aria-current="page"><?php echo $window->getTitle(); ?></li>
	</ol>
</nav>

<main class="container">
	<?php echo $window->content()->get('main'); ?>
</main>
