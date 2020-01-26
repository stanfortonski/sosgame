<main class="container-fluid">
	<div class="row">
		<div id="slider" class="col-12 d-none p-0 m-0 d-sm-block carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<li data-target="#slider" data-slide-to="0" class="active"></li>
				<li data-target="#slider" data-slide-to="1"></li>
			</ol>

			<div class="carousel-inner">
				<div class="carousel-item active">
					<img src="imgs/slider/1.gif" alt="">
					<div class="carousel-caption">
						<h4>Ciągłe nowości</h4>
						<p>Gra cały czas jest rozwijana!</p>
					</div>
				</div>

				<div class="carousel-item">
					<a href="join">
						<img src="imgs/slider/2.gif" alt="">
						<div class="carousel-caption">
							<h3>Dołącz do gry</h3>
							<p>Rozpocznij przygodę razem z innymi graczmi!</p>
						</div>
					</a>
				</div>
			</div>

			<a class="carousel-control-prev" href="#slider" role="button" data-slide="prev">
	 			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
	 			<span class="sr-only">Previous</span>
 			</a>

 			<a class="carousel-control-next" href="#slider" role="button" data-slide="next">
	 			<span class="carousel-control-next-icon" aria-hidden="true"></span>
	 			<span class="sr-only">Next</span>
 			</a>
		</div>
	</div>

	<div class="row mt-sm-3 ml-1 mr-1 ml-sm-2 ml-md-3">
		<aside id="sideMenu" class="col-md-3 order-md-2 p-3 pt-md-5 p-lg-5 mt-2 mt-sm-0 mb-2 mb-md-2">
			<nav>
				<?php echo $window->content()->get('side-navigation'); ?>
			</nav>

			<section class="d-none d-md-block ad bg-primary">
				ADVERTISE HERE
			</section>

			<section class="d-none d-md-block ad bg-primary">
				ADVERTISE HERE
			</section>
		</aside>

		<section class="col-12 col-md-9 p-0 px-md-3">
			<?php echo $window->content()->get('main'); ?>
		</section>
	</div>
</main>
