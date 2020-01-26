<?php

	namespace SOS
	{
		class AccountView
		{
			public function showRegisterForm(array $data): string
			{
				return <<<EOF
				<div class="row">
					<section class="col">
						<article>
							<h3>Rejestracja</h3>
							<form id="register-form" method="POST" action="register">
								<div class="form-group row">
									<label class="col-sm-4 col-form-label" for="login">Login</label>
									<div class="col-sm-6">
										<input type="text" class="form-control" name="login" id="login" value="{$data['login']}" autocomplete="off" autofocus>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-4 col-form-label" for="email">E-mail</label>
									<div class="col-sm-6">
										<input type="email" class="form-control" name="email" id="email" value="{$data['email']}" autocomplete="off">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-4 col-form-label" for="password">Hasło</label>
									<div class="col-sm-6">
										<input type="password" class="form-control" name="password" id="password" autocomplete="off">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-4 col-form-label" for="re-password">Powtórz Hasło</label>
									<div class="col-sm-6">
										<input type="password" class="form-control" name="re-password" id="re-password" autocomplete="off">
									</div>
								</div>

								<div class="form-check">
										<input type="checkbox" class="form-check-input" name="agreement" id="agreement"></td>
										<label for="agreement" class="form-check-label"><small>Zapoznałem się z <a href="agreement">regulaminem</a> serwisu i wyrażam na nią zgodę</small></label>
								</div>

								<div class="offset-sm-4 g-recaptcha" data-sitekey="6LcgbhcUAAAAAGL1vYIfFTIUqfRoiUuGZNg3f3MZ"></div>
								<input type="submit" class="btn btn-lg" value="Zarejestruj">
								{$data['errors']}
							</form>
						</article>
					</section>
				</div>
EOF;
			}

			public function showLoginForm(array $data): string
			{
				$output = <<<EOF
				<form id="login-form" method="POST" action="login">
					<div class="form-group row">
						<div class="offset-2 col-8">
							<input type="text" name="login" class="form-control" placeholder="Login" autofocus>
						</div>
					</div>

					<div class="form-group row">
						<div class="offset-2 col-8">
							<input type="password" class="form-control" name="password" placeholder="Hasło">
						</div>
					</div>
EOF;
				if ($data['isMaxLoginCount'])
					$output .= '<div class="offset-2 col g-recaptcha" id="recaptcha" data-sitekey="6LcgbhcUAAAAAGL1vYIfFTIUqfRoiUuGZNg3f3MZ"></div>';

				$output .= <<<EOF
					<input type="submit" class="btn btn" value="Zaloguj">
					<small><a href="#" id="remind-button">Nie pamiętam hasła</a></small>
					{$data['errors']}
				</form>
EOF;
				return $output;
			}

			public function showRemindForm(array $data): string
			{
				return <<<EOF
				<form id="remind-form" method="POST" action="ajax/send-password">
					<div class="form-group row">
						<label for="email" class="col-4 col-form-label">Podaj e-mail</label>
						<div class="col-6">
							<input type="email" class="form-control" name="email" id="email" autofocus>
						</div>
					</div>

					<div class="offset-2 col g-recaptcha" id="recaptcha" data-sitekey="6LcgbhcUAAAAAGL1vYIfFTIUqfRoiUuGZNg3f3MZ"></div>
					<input type="submit" class="btn" value="Przypomnij">
					<button type="button" class="btn bg-success login-button">Powrót do logowania</button>
					{$data['errors']}
				</form>
EOF;
			}

			public function showChangeMailForm(array $data): string
			{
				return <<<EOF
				<form id="change-mail-form" method="POST" action="change-mail">
					<div class="form-group row">
						<label for="email" class="col-sm-4 col-form-label">Nowy e-mail</label>
						<div class="col-sm-6">
							<input type="email" class="form-control" name="email" id="email" autofocus>
						</div>
					</div>

					<div class="form-group row">
						<label for="re-email" class="col-sm-4 col-form-label">Powtórz e-mail</label>
						<div class="col-sm-6">
							<input type="email" class="form-control" name="re-email" id="re-email">
						</div>
					</div>

					<input type="submit" class="btn btn-lg" value="Zmień e-mail">
					{$data['errors']}
				</form>
EOF;
			}

			public function showChangePasswordForm(array $data): string
			{
				return <<<EOF
				<form id="change-pass-form" method="POST" action="change-password">
					<div class="form-group row">
						<label for="old-password" class="col-sm-4 col-form-label">Stare hasło</label>
						<div class="col-sm-6">
							<input type="password" class="form-control" name="old-password" id="old-password" autofocus>
						</div>
					</div>

					<div class="form-group row">
						<label for="password" class="col-sm-4 col-form-label">Nowe hasło</label>
						<div class="col-sm-6">
							<input type="password" class="form-control" name="password" id="password">
						</div>
					</div>

					<div class="form-group row">
						<label for="re-password" class="col-sm-4 col-form-label">Powtórz nowe hasło</label>
						<div class="col-sm-6">
							<input type="password" class="form-control" name="re-password" id="re-password">
						</div>
					</div>

				<input type="submit" class="btn btn-lg" value="Zmień hasło">
				{$data['errors']}
			</form>
EOF;
			}

			public function youAreLoggedAs(array $data): string
			{
				$output = '<h3>Witaj, '.$data['login'].'!</h3>';
				$output .= $data['verified'] ? '' : '<p>Aby zagrać aktywuj swoje konto.</p><a href="resend-activation">Wyślij ponownie e-mail aktywacyjny</a>';
				return $output;
			}

			public function showProfile(array $data): string
			{
				if (!empty($data['description']))
				{
					$data['description'] = <<<EOF
					<section class="col-12">
						<article>
							<section class="content">
								{$data['description']}
							</section>
						</article>
					</section>
EOF;
				}
				else $data['description'] = '';

				return <<<EOF
				<div class="row">
					<section class="col-12">
						<article>
							<img class="ml-sm-3" height="180" width="135" src="{$data['avatar']}">
							<div class="ml-sm-5 ml-3 d-inline-block">
								<h1>{$data['login']}</h1>
								{$data['permission']}
								<h6>Dołączył: {$data['start_date']}</h6>
							</div>
						</article>
					</section>

					{$data['description']}

					<section class="col-12">
						<article>
							<h3>Postacie gracza</h3>
							<div class="row thumbnails">
								{$data['generals']}
							</div>
						</article>
					</section>
				</div>
EOF;
			}

			public function showProfileEditor(array $data): string
			{
				return <<<EOF
				<div class="row">
					<div class="col">
						<article>
							<h3>Miniatura profilowa</h3>
							<form method="POST" action="change-profile-avatar" enctype="multipart/form-data" id="profile-avatar">
								<div class="input-group mb-3">
								  <div class="custom-file offset-2 col-sm-8">
								    <input type="file" class="custom-file-input" id="avatar" name="avatar" accept="image/*">
								    <label class="custom-file-label" for="avatar">Wybierz swoją miniaturę</label>
								  </div>
								</div>
								<input type="submit" class="btn btn-lg" value="Wyślij">
							</form>
						</article>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<article>
							<h3>Opis profilu</h3>
							<form method="POST" action="change-profile-description" id="profile-editor">
								<textarea class="form-control mb-3" name="description" id="description">{$data['description']}</textarea>
								<input type="submit" class="btn btn-lg" value="Zapisz">
							</form>
						</article>
					</div>
				</div>
EOF;
			}

			public function showPanel(array $data): string
			{
				return <<<EOF
				<div class="row">
					<section class="col-md-6">
						<article>
							<h3>Ustawienia</h3>
							<nav class="px-sm-5 pt-2">
								<a class="btn btn-block" href="profile-edit-form">Edytuj profil</a>
								<a class="btn btn-block" href="change-password-form">Zmień hasło</a>
								<a class="btn btn-block" href="change-mail-form">Zmień e-mail</a>
								<a class="btn btn-block" href="delete-account-form">Usuwanie konta</a>
							</nav>
						</article>
					</section>

					<section class="col-md-6">
						<article>
							<h3>Stan konta</h3>
							<p>Coinsów: {$data['coins']}</p>
							<a href="buy-coins" class="btn">Doładuj konto</a>
						</article>
					</section>
				</div>
EOF;
			}

			public function showDeleteForm(): string
			{
				return <<<EOF
					<h3>Usuwanie konta</h3>
					<p>Usunięcie konta trzeba potwierdzić drogą mailową. Po potwierdzeniu proces trwa jeszcze 15 dni. W tym czasie można cofnąć tą operacje.</p>
					<form method="POST" action="delete-account">
						<input type="submit" name="submit" class="btn btn-danger" id="delete-account" value="Usuń konto">
					</from>
EOF;
			}

			public function showBackForm(): string
			{
				return <<<EOF
					<h3>Zatrzymaj operacje usuwania konta</h3>
					<form method="POST" action="back-account">
						<input type="submit" name="submit" class="btn btn-lg bg-success" value="Zatrzymaj operacje">
					</from>
EOF;
			}
		}
	}

?>
