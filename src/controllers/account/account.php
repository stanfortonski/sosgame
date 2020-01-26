<?php

	namespace SOS
	{
		class Account extends BasicAccount
		{
			use AccountProfile, AccountErrors, AccountLogin, AccountRegister, AccountActivation,
				AccountRemind, AccountPasswordChanger, AccountMailChanger, AccountMail, AccountFundManagement,
				AccountRemoving;

			static private $instance;
			protected $view;

			protected function sessionName(): string
			{
				return 'user-id';
			}

			static public function get(): self
			{
				if (empty(static::$instance))
					static::$instance = new static(new AccountView);
				return static::$instance;
			}

			protected function __construct(AccountView $view)
			{
				parent::__construct();
				$this->view = $view;
			}

			public function changeView(AccountView $view): void
			{
				$this->view = $view;
			}

			public function login(): void
			{
				$this->loginStandard();
			}

			public function makePanel(): string
			{
				return $this->view->showPanel(['coins' => $this->getAmountOfCoins()]);
			}
		}
	}

?>
