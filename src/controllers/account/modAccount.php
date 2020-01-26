<?php

	namespace SOS
	{
		class ModAccount extends Account
		{
			static private $instance;

			static public function get(): static
			{
				if (empty(static::$instance))
					static::$instance = new static;
				return static::$instance;
			}

			protected function __construct(){}
			protected function sessionName(): string
			{
				return 'mod-id';
			}

			public function login(): void
			{
				$this->loginWithPermissionCheck();
			}
		}
	}

?>
