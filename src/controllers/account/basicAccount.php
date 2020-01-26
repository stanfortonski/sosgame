<?php

	namespace SOS
	{
		abstract class BasicAccount
		{
			protected const PASSWORD_LENGTH = 8;
			protected const MIN_LOGIN_LENGTH = 3;
			protected const MAX_LOGIN_LENGTH = 63;
			protected const MAX_QUERY_COUNT = 3;

			protected $user;

			abstract protected function sessionName(): string;

			protected function __construct()
			{
				$this->user = new User;
				if ($this->isLogged())
					$this->user->setId($this->getUserId());
			}

			public function & getUserId()
			{
				return $_SESSION[$this->sessionName()];
			}

			public function isLogged(): bool
			{
				return !empty($_SESSION[$this->sessionName()]);
			}

			public function logout(): void
			{
				$this->destroySession();
				header('location: '.Config::get()->path('main'));
				exit;
			}

			protected function destroySession(): void
			{
				unset($_SESSION[$this->sessionName()]);
				session_destroy();
			}
		}
	}

?>
