<?php

	namespace SOS
	{
		trait AccountMailChanger
		{
			public function makeChangeMailForm(): string
			{
				$errors = $this->showErrors();
				return $this->view->showChangeMailForm(['errors' => $errors]);
			}

			public function changeMail(): void
			{
				try
				{
					$this->sanitizeMail();
					if (!$this->validMail())
						throw new Exception;

					if ($this->sendMailWithConfirmNewMail($_POST['email']))
					{
						setcookie('message', 'Wiadomość potwierdzająca została wysłana na pocztę.');
						header('location: '.Config::get()->path('main'));
					}
					else throw new Excpetion;
				}
				catch (Exception $e)
				{
					setcookie('message', 'Zmiana e-maila nie powiodła się.');
					header('location: change-mail-form');
				}
			}

			public function confirmNewMail(): void
			{
				$link = empty($_GET['l']) ? '' : $_GET['l'];
				if (!$this->user->confirmNewMail($link))
				{
					header('location: error');
					exit;
				}
				else
				{
					setcookie('message', 'Twój e-mail został zmieniony.');
					header('location: '.Config::get()->path('main'));
					exit;
				}
			}

			private function sanitizeMail(): void
			{
				$_POST['email'] = empty($_POST['email']) ? '' : trim($_POST['email']);
				$_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
				$_POST['re-email'] = empty($_POST['re-email']) ? '' : trim($_POST['re-email']);
				$_POST['re-email'] = filter_var($_POST['re-email'], FILTER_SANITIZE_EMAIL);
			}

			private function validMail(): bool
			{
				$canChange = true;

				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				{
					$_SESSION['errors']['email'] = 'Nieprawidłowy adres e-mail';
					$canChange = false;
				}

				if ($_POST['email'] !== $_POST['re-email'])
				{
					$_SESSION['errors']['not-same-email'] = 'Podane e-maile nie są takie same';
					$canChange = false;
				}
				return $canChange;
			}

			protected function sendMailWithConfirmNewMail(string $newMail): bool
			{
				$oldMail = $this->user->selectThis(['mail']);

				$this->user->prepareConfirmNewMail($newMail);
				$link = $this->user->getConfirmNewMailLink();

				return $this->sendMail('SOSGAME: Potwierdź zmiane e-maila', $this->makeConfirmMessage($link), $oldMail);
			}

			private function makeConfirmMessage(string $link): string
			{
				$href = Config::get()->path('main').'confirm-mail?l='.$link;
				return <<<EOF
				<p>Aby sfinalizować zmiane e-maila musisz potwierdź jej zmianę. Kliknij link poniżej by potwierdzić:</p>
				<p>Link: <a href="{$href}">{$href}</a></p>
EOF;
			}
		}
	}
?>
