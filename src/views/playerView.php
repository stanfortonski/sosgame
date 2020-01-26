<?php

	namespace SOS
	{
		class PlayerView
		{
			public function showGeneralThumbnail(array $data): string
			{
				$data['lvl'] = empty($data['lvl']) ? '' : ' '.$data['lvl'].' LVL';
				$data['name'] = empty($data['name']) ? '' : $data['name'];
				return <<<EOF
				<figure class="generalThumbnail">
					<img src="{$data['path']}icon.jpg">
					<figcaption>{$data['name']}{$data['lvl']}</figcaption>
				</figure>
EOF;
			}

			public function showGeneralAnimations(array $data): string
			{
				return <<<EOF
				<div class="generalAnimations">
					<img src="{$data['path']}stand.gif">
					<img src="{$data['path']}run.gif">
					<img src="{$data['path']}attack.gif">
				</div>
EOF;
			}

			public function showGeneralsList(array $data): string
			{
				return <<<EOF
				<form method="POST" action="{$data['action']}" id="choose-general">
					<div class="row mt-4">
						<div class="input-group col-md-9 mt-4 mt-md-0">
							<select class="form-control form-control-lg" name="generalAndServer">
								{$data['options']}
							</select>
							<div class="input-group-append">
								<input type="submit" class="btn btn-lg" value="{$data['value']}">
							</div>
						</div>
					</div>
				</form>
EOF;
			}

			public function showAddPlayerForm(array $data): string
			{
				return <<<EOF
				<form id="add-general-form" method="POST" action="ajax/add-player">
					<div class="row">
						<div class="col">
							<article id="nameSet"><h3>Tworzenie postaci</h3>{$data['nameset']}</article>
						</div>
					</div>

					<div class="row">
						<div class="col mt-5" style="display: none;">
							<h3 class="pb-3">Wybierz przynależność do grupy</h3>
							<div class="row mt-2 thumbnails full-fig fadeIn" id="races">{$data['races']}</div>
						</div>
					</div>

					<div class="row">
						<div class="col mt-5" style="display: none;">
							<h3>Wybierz postać</h3>
							<div class="row thumbnails scale" id="presets"></div>
							<input type="submit" class="ml-1 btn btn-lg" value="Zakończ tworzenie postaci">
						</div>
					</div>
				</form>
EOF;
			}

			public function showChoosingServerAndName(array $data): string
			{
				return <<<EOF
				<div class="form-group row">
					<label for="name" class="col-sm-4 col-form-label">Nazwa postaci</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="name" id="name" autofocus>
					</div>
				</div>

				<div class="form-group row">
					<label for="server" class="col-sm-4 col-form-label">Serwer</label>
					<div class="col-sm-6">
						<select class="form-control" id="server" name="server">
							<option value="">Wybierz serwer</option>
							{$data['options']}
						</select>
					</div>
				</div>
EOF;
			}

			public function showOptionRace(array $data): string
			{
				return <<<EOF
				<div class="form-group col-lg-3 col-md-6 px-5 px-sm-0">
					<h4>{$data['name']}</h4>
					<input type="radio" name="race" id="race{$data['id']}" value="{$data['id']}">
					<label for="race{$data['id']}">
						<figure>
							<img src="{$data['src']}" alt="">
							<figcaption>{$data['description']}</figcaption>
						</figure>
					</label>
				</div>
EOF;
			}

			public function showOptionGeneralPreset(array $data): string
			{
				$data['checked'] = empty($data['checked']) ? '' : $data['checked'];

				$output = <<<EOF
					<div class="col-md-6 col-lg-4">
						<input type="radio" name="char" class="form-control" id="char{$data['id']}" value="{$data['id']}" {$data['checked']}>
						<label for="char{$data['id']}">
EOF;
				$output .= $this->showGeneralThumbnail($data);
				return $output.'</label></div>';
			}

			public function showEditGeneralForm(array $data): string
			{
				return <<<EOF
				<h2>{$data['name']}</h2>
				<form method="POST" action="change-player-name" class="row mt-4 d-block" id="change-player-name-form">
					<input type="hidden" name="general-id" value="{$data['id']}">
					<input type="hidden" id="actualName" value="{$data['name']}">
					<div class="col">
						<article>
							<h4>Zmiana nazwy</h4>
							<div class="form-group row">
								<label for="name" class="col-sm-4 col-form-label">Nazwa postaci</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" name="name" id="name" value="{$data['name']}">
									<small class="danger">Koszt zmiany wynosi 10 coinsów.</small>
								</div>
							</div>
							<input type="submit" class="btn" value="Zatwierdź">
						</article>
					</div>
				</form>

				<form method="POST" action="change-player-outfit" class="row mt-4 d-block">
					<input type="hidden" name="general-id" value="{$data['id']}">
					<div class="col">
						<article>
							<h4>Zmiana wyglądu</h4>
						 	{$data['outfits']}
							<input type="submit" class="btn" value="Zatwierdź">
						</article>
					</div>
				</form>

				<form method="POST" action="delete-player" class="row mt-4 d-block">
					<input type="hidden" name="general-id" value="{$data['id']}">
					<div class="col">
						<article>
							<h4>Usuwanie postaci</h4>
							<p>Postać można usunąć tylko i wyłacznie gdy nie była używania przez okres 15 dni.</p>
							<p>Pamiętaj operacji tej nie można cofnąć!</p>
							<input type="submit" class="btn" value="Usuń postać">
						</article>
					</div>
				</form>
EOF;
			}

			public function showChangeOutfitOption(array $data): string
			{
				$data['checked'] = empty($data['checked']) ? '' : $data['checked'];

				$output = <<<EOF
				<div class="row">
					<div class="offset-md-1 col-md-5 thumbnails fadeIn">
						<input type="radio" name="outfit-id" class="form-control" id="outfit{$data['id']}" value="{$data['id']}" {$data['checked']}>
						<label for="outfit{$data['id']}">
EOF;
				$output .= $this->showGeneralThumbnail($data);
				$output .= '</label></div><div class="col-md-6 d-none d-sm-block">'.$this->showGeneralAnimations($data).'</div>';
				return $output.'</div><hr>';
			}
		}
	}

?>
