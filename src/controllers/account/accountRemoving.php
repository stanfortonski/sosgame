<?php

	namespace SOS
	{
		trait AccountRemoving
		{
			public function confirmRemove(): void
			{
				$link = empty($_GET['l']) ? '' : $_GET['l'];
				if (!$this->user->confirmDelete($link))
				{
					header('location: error?from='.Config::get()->path('actual'));
					exit;
				}
				else
				{
					setcookie('message', 'Twoje konto zostanie usunięte za 15 dni.');
					header('location: '.Config::get()->path('main'));
					exit;
				}
			}

			public function removeAndSendMailToConfirm(): void
			{
				try
				{
					if (empty($_POST['submit']) || $this->user->isInDeletePool())
						throw new \Exception;

					$link = $this->user->addToDeletePool();
					if ($this->sendMail('SOSGAME: Usuwanie konta', $this->getRemoveAccountMessage($link)))
						setcookie('message', 'Wiadomość potwierdzająca usuwanie konta została wysłana na pocztę.');
					else throw new \Exception;
				}
				catch (\Exception $e)
				{
					setcookie('message', 'Usuwanie konta nie powiodło się.');
				}
				header('location: delete-account-form');
				exit;
			}

			public function backFromRemove(): void
			{
				if (!empty($_POST['submit']))
					$this->user->removeFromDeletePool();
				header('location: delete-account-form');
				exit;
			}

			public function makeDeleteOrBackForm(): string
			{
				if ($this->user->isInDeletePool())
					return $this->view->showBackForm();
				return $this->view->showDeleteForm();
			}

			private function getRemoveAccountMessage(string $link): string
			{
				$href = Config::get()->path('main').'confirm-delete?l='.$link;
				return <<<EOF
				<p>Potwierdź usunięcie konta. Do 15 dni można wycofać się z operacji usunięcia konta.</p>
				<p>Aby cofnąć nalezy wejść w panel użytkownika, następnie usuwanie konta potem wciśnij cofnij usuwanie konta.</p>

				<p>Link potwierdzający usunięcie: <a href="{$href}">{$href}</a></p>
EOF;
			}
		}
	}

?>
