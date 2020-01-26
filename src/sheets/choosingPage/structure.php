<main class="container-fluid">
	<div class="row">
		<section class="col-md-7 pb-5 pb-md-2" id="generalThumbnail">
		</section>

		<section class="col-md-5" id="selecting">
				<?php echo $window->content()->get('main'); ?>
		</section>
	</div>

	<div class="row" id="toolbar">
		<section class="col-md-6 d-none d-md-block">
			<h2><?php echo $window->getTitle(); ?></h2>
		</section>

		<nav class="col-md-6">
			<div class="btn-group" role="group" aria-label="Grupa przycisków">
				<a href="<?php echo SOS\Config::get()->path('panel'); ?>edit-player-form" id="edit-player" class="btn">Edytuj</a>
  			<a href="<?php echo SOS\Config::get()->path('panel'); ?>add-player-form" class="btn">Nowa postać</a>
  			<a href="<?php echo SOS\Config::get()->path('panel'); ?>" class="btn">Powrót <img class="icon" src="imgs/icons/back.png"></a>
			</div>
		</nav>
	</div>
</main>
