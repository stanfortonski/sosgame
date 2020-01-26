<?php

	namespace SOS
	{
		trait AccountActivation
		{
			public function activation(): void
			{
				$link = empty($_GET['l']) ? '' : $_GET['l'];
				if (!$this->user->activation($link))
				{
					header('location: error');
					exit;
				}
				else
				{
					setcookie('message', 'Twoje konto zostało aktywowanę.');
					header('location: '.Config::get()->path('main'));
					exit;
				}
			}

			public function resendActivation(): void
			{
				if (!$this->sendMailWithActivation())
				{
					header('location: error?from='.Config::get()->path('actual'));
					exit;
				}
				else
				{
					setcookie('message', '>Wiadomość z kodem aktywacyjnym została wysłana na pocztę.');
					header('location: '.Config::get()->path('main'));
					exit;
				}
			}

			public function isVerified(): bool
			{
				return $this->user->isVerified();
			}

			public function sendMailWithActivation(): bool
			{
				if (!$this->user->isVerified())
				{
					$link = $this->user->getActivationLink();
					return $this->sendMail('SOSGAME: Aktywacja konta', $this->getActivationMessage($link));
				}
				return false;
			}

			private function getActivationMessage(string $link): string
			{
				$href = Config::get()->path('main').'activation?l='.$link;
				return <<<EOF
				<p>Aby zakończyć rejestrację kliknij link poniżej.</p>
				<p>Link aktywacyjny: <a href="{$href}">{$href}</a></p>
EOF;
			}
		}
	}

?>
