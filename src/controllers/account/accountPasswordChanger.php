<?php

	namespace SOS
	{
		trait AccountPasswordChanger
		{
			public function makeChangePasswordForm(): string
			{
				$errors = $this->showErrors();
				return $this->view->showChangePasswordForm(['errors' => $errors]);
			}

			public function changePassword(): void
			{
				try
				{
					$this->sanitizePasswords();
					$oldPassword = $this->user->selectThis(['password']);
					if (!$this->validPasswords($oldPassword))
						throw new \Exception;

					$this->user->changePassword($_POST['password']);
					setcookie('message', 'Hasło zostało zmienionę.');
					header('location: '.Config::get()->path('main'));
				}
				catch(\Exception $e)
				{
					setcookie('message', 'Zmiana hasła nie powiodła się.');
					header('location: change-password-form');
				}
			}

			private function sanitizePasswords(): void
			{
				$_POST['old-password'] = empty($_POST['old-password']) ? '' : $_POST['old-password'];
				$_POST['password'] = empty($_POST['password']) ? '' : $_POST['password'];
				$_POST['re-password'] = empty($_POST['re-password']) ? '' : $_POST['re-password'];
			}

			private function validPasswords(string $oldPassword): bool
			{
				$canChange = true;

				if (empty($_POST['old-password']) || empty($_POST['password']) || empty($_POST['re-password']))
				{
					$_SESSION['errors']['fill'] = 'Uzupełnij wszystkie pola';
					$canChange = false;
				}

				if (!password_verify($_POST['old-password'], $oldPassword))
				{
					$_SESSION['errors']['old-password'] = 'Podane stare hasło nie jest prawidłowe';
					$canChange = false;
				}

				if ($_POST['password'] !== $_POST['re-password'])
				{
					$_SESSION['errors']['same-passwords'] = 'Hasła nie są identyczne';
					$canChange = false;
				}

				if (password_verify($_POST['password'], $oldPassword))
				{
					$_SESSION['errors']['old-like-new'] = 'Stare hasło nie może być takie samo jak nowe';
					$canChange = false;
				}

				if (strlen($_POST['password']) < static::PASSWORD_LENGTH)
				{
					$_SESSION['errors']['password-size'] = 'Hasło musi mieć co najmniej '.static::PASSWORD_LENGTH.' znaków';
					$canChange = false;
				}
				return $canChange;
			}
		}
	}

?>
