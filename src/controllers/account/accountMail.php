<?php

	namespace SOS
	{
		trait AccountMail
		{
			protected function sendMail(string $title, string $content, $otherMail = null): bool
			{
				if (empty($otherMail))
					$mail = $this->user->selectThis(['mail']);
				else $mail = $otherMail;

				$sender = new \MailSender\Sender(new \MailSender\Mail('SOSGAME', Config::get()->mail()));
				if (!$sender->send(Config::get()->mail(), $title, $content))
				{
					$_SESSION['errors']['cant-send'] = 'Nie udało się wysłać wiadomości';
					return false;
				}
				return true;
			}
		}
	}

?>
