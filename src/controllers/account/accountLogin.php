<?php

	namespace SOS
	{
		trait AccountLogin
		{
			public function makeLoginForm(): string
			{
				return $this->view->showLoginForm(['errors' => $this->showErrors(), 'isMaxLoginCount' => $this->isMaxLoginCount()]);
			}

			protected function loginStandard(): void
			{
				$this->doLoginTasks();

				if ($this->validLogin())
				{
					if ($this->user->login($_POST['login'], $_POST['password']))
					{
						$_SESSION[$this->sessionName()] = $this->user->getId();

						$this->clearLoginCount();
						header('location: '.Config::get()->path('main'));
						exit;
					}
					else
					{
						$_SESSION['errors']['not-found'] = 'Błędny login lub hasło';
						$this->increaseLoginCount();
					}
				}
				header('location: '.Config::get()->path('main').'?login=');
				exit;
			}

			protected function loginWithPermissionCheck(): void
			{
				$this->doLoginTasks();

				if ($this->validLogin())
				{
					$isLogged = $this->user->login($_POST['login'], $_POST['password']);
					if ($isLogged)
					{
						$permission = $this->user->selectThis(['permission']);
						$permission = $permission != 'normal';
					}
					else
					{
						$this->user->setId(null);
						$permission = false;
					}

					if ($isLogged && $permission)
					{
						$_SESSION[$this->sessionName()] = $this->user->getId();

						$this->clearLoginCount();
						header('location: '.Config::get()->path('main'));
						exit;
					}
					else
					{
						$_SESSION['errors']['not-found'] = 'Błędny login lub hasło';
						$this->increaseLoginCount();
					}
				}
				header('location: '.Config::get()->path('main').'?login=');
				exit;
			}

			private function validLogin(): bool
			{
				$canLogin = true;

				if (empty($_POST) || empty($_POST['login']) || empty($_POST['password']))
				{
					$_SESSION['errors']['fill'] = 'Uzupełnij wszystkie pola';
					$canLogin = false;
				}

				if ($this->isMaxLoginCount())
					$canLogin = $this->confirmRecaptcha();
				return $canLogin;
			}

			private function isMaxLoginCount(): bool
			{
				if (empty($_SESSION['login-count'])) return false;
				return $_SESSION['login-count'] > static::MAX_QUERY_COUNT+1;
			}

			private function increaseLoginCount(): void
			{
				if (empty($_SESSION['login-count']))
					$_SESSION['login-count'] = 0;
				++$_SESSION['login-count'];
			}

			private function clearLoginCount(): void
			{
				if (!empty($_SESSION['login-count']))
					unset($_SESSION['login-count']);
			}

			private function doLoginTasks(): void
			{
				User::deleteUsersInPool();
			}
		}
	}

?>
