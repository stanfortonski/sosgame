<?php

	namespace SOS
	{
		trait AccountRegister
		{
			public function makeRegisterForm(): string
			{
				$errors = $this->showErrors();
				$login = empty($_SESSION['register']['login']) ? '' : $_SESSION['register']['login'];
				$email = empty($_SESSION['register']['email']) ? '' : $_SESSION['register']['email'];

				if (!empty($_SESSION['register']))
					unset($_SESSION['register']);

				return $this->view->showRegisterForm(['login' => $login, 'email' => $email, 'errors' => $errors]);
			}

			public function register(): void
			{
				$this->sanitizeRegister();
				if ($this->validRegister())
				{
					try
					{
						$userId = $this->user->register($_POST['login'], $_POST['email'], $_POST['password']);
						$_SESSION[$this->sessionName()] = $userId;

						$this->sendMailWithActivation();
						$content = '<p>'.$_POST['login'].', dziękujemy za rejestrację.</p><p>Wiadomość aktywująca konto została wysłana.</p>';
						setcookie('message', $content);
						header('location: '.Config::get()->path('panel'));
						exit;
					}
					catch (\Exception $e)
					{
						$_SESSION['errors']['unique'] = $e->getMessage();
					}
				}
				unset($_POST["g-recaptcha-response"]);
				$_SESSION['register'] = $_POST;
				header('location: join');
				exit;
			}

			private function sanitizeRegister(): void
			{
				$_POST['email'] = empty($_POST['email']) ? '' : trim($_POST['email']);
				$_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
				$_POST['login'] = empty($_POST['login']) ? '' : trim($_POST['login']);
				$_POST['password'] = empty($_POST['password']) ? '' : $_POST['password'];
				$_POST['re-password'] = empty($_POST['re-password']) ? '' : $_POST['re-password'];
			}

			private function validRegister(): bool
			{
				$canRegister = $this->confirmRecaptcha();

				if (empty($_POST) || empty($_POST['login']) || empty($_POST['email']) || empty($_POST['re-password']) || empty($_POST['password']))
				{
					$_SESSION['errors']['fill'] = 'Uzupełnij wszystkie pola';
					$canRegister = false;
				}

				$isLoginBad = preg_match_all("/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/", $_POST['login']);
				if ($isLoginBad == 0)
				{
					$_SESSION['errors']['invalid-login'] = 'Login może składać się jedynie ze znakow alfabetu, cyfr oraz "_" i "-"';
					$canRegister = false;
				}

				$loginLen = strlen($_POST['login']);
				if ($loginLen < static::MIN_LOGIN_LENGTH)
				{
					$_SESSION['errors']['login-size'] = 'Login musi mieć co najmniej '.static::MIN_LOGIN_LENGTH.' znaków';
					$canRegister = false;
				}
				else if ($loginLen > static::MAX_LOGIN_LENGTH)
				{
					$_SESSION['errors']['login-size'] = 'Login musi być krótszy niż '.static::MAX_LOGIN_LENGTH.' znaków';
					$canRegister = false;
				}

				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				{
					$_SESSION['errors']['email'] = 'Nieprawidłowy adres e-mail';
					$canRegister = false;
				}

				if (strlen($_POST['password']) < static::PASSWORD_LENGTH)
				{
					$_SESSION['errors']['password-size'] = 'Hasło musi mieć co najmniej '.static::PASSWORD_LENGTH.' znaków';
					$canRegister = false;
				}
				else if ($_POST['password'] !== $_POST['re-password'])
				{
					$_SESSION['errors']['same-passwords'] = 'Hasła nie są identyczne';
					$canRegister = false;
				}

				if (empty($_POST['agreement']))
				{
					$_SESSION['errors']['agreement'] = 'Nie akceptujesz regulaminu serwisu';
					$canRegister = false;
				}
				return $canRegister;
			}
		}
	}

?>
