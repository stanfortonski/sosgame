<?php

	namespace SOS
	{
		trait AccountRemind
		{
			public function makeRemindForm(): string
			{
				$errors = $this->showErrors();
				return $this->view->showRemindForm(['errors' => $errors]);
			}

			public function remind(): bool
			{
				$this->sanitizeRemind();
				if ($this->validRemind())
					return $this->sendMailWithNewPassword();
				return false;
			}

			private function sanitizeRemind(): void
			{
				$_POST['email'] = empty($_POST['email']) ? '' : trim($_POST['email']);
				$_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			}

			private function validRemind(): bool
			{
				$canRemind = $this->confirmRecaptcha();

				if (empty($_POST['email']))
				{
					$_SESSION['errors']['email'] = 'Uzupełnij pole z adresem e-mail';
					$canRemind = false;
				}

				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				{
					$_SESSION['errors']['syntax'] = 'Nieprawidłowy adres e-mail';
					$canRemind = false;
				}
				return $canRemind;
			}

			private function sendMailWithNewPassword(): bool
			{
				$this->user->where('mail = ? LIMIT 1', [$_POST['email']]);
				$data = $this->user->select(['id', 'mail']);

				if (!empty($data))
				{
					$password = randomString(static::PASSWORD_LENGTH);
					if ($this->sendMail('SOSGAME: Nowe hasło', 'Twoje nowe hasło to: '.$password, $data['mail']))
					{
						$oldId = $this->user->getId();
						$this->user->setId($data['id']);
						$this->user->changePassword($password);
						$this->user->setId($oldId);
						return true;
					}
				}
				else $_SESSION['errors']['user-mail'] = 'Użytkownik o podanym adresie email nie istnieje';
				return false;
			}
		}
	}

?>
