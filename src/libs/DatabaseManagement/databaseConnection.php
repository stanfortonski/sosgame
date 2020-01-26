<?php

	namespace DBManagement
	{
		class DatabaseConnection
		{
			private const CONNECTION_NAME = 'DB_CONNECTION';
			static private $amountOfInstances = 0;
			static public $database = ['host' => '', 'dbname' => '', 'user' => '', 'password' => ''];
			static private $isAllowCommit = true;

			public function & getConnect()
			{
				return $_SESSION[self::CONNECTION_NAME];
			}

			public function __construct()
			{
				++self::$amountOfInstances;
				if (empty($this->getConnect()))
				{
					try
					{
						$db = self::$database;
						$_SESSION[self::CONNECTION_NAME] = new \PDO("mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8", $db['user'], $db['password'], [
							\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
							\PDO::ATTR_EMULATE_PREPARES => false,
							\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
							\PDO::ATTR_AUTOCOMMIT => 0
						]);
					}
					catch (\PDOException $e)
					{
						echo '<h2>Failed Connection</h2>';
						echo '<p>'.$e->getMessage().'</p>';
						exit;
					}
				}
			}

			public function __destruct()
			{
				if (--self::$amountOfInstances <= 0)
					$this->close();
			}

			public function close(): void
			{
				$_SESSION[self::CONNECTION_NAME] = null;
			}

			public function refresh(): void
			{
				$this->close();
				--self::$amountOfInstances;
				$this->__construct();
			}

			public function beginTransaction(): void
			{
				$this->getConnect()->beginTransaction();
			}

			public function lockCommit(): void
			{
				self::$isAllowCommit = false;
			}

			public function unLockCommit(): void
			{
				self::$isAllowCommit = true;
			}

			public function commit(): void
			{
				if (self::$isAllowCommit)
					$this->getConnect()->commit();
				self::$isAllowCommit = true;
			}

			public function rollBack(): void
			{
				$this->getConnect()->rollback();
			}

			public function selectDB(string $dbname): void
			{
				try
				{
					$this->getConnect()->exec('USE '.$dbname);
				}
				catch (\PDOException $e)
				{
					echo '<p>Failed: USE '.$dbname.'</p>';
					echo '<p>Can\'t use this database.</p>';
				}
			}
		}
	}

?>
