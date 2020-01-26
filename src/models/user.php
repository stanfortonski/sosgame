<?php

	namespace SOS
	{
		class User extends DatabaseOperationsAdapter
		{
			private const MAX_AVATAR_SIZE = 2097152;

			static public function getTable(): string
			{
				return 'USERS';
			}

			static public function localPath(): string
			{
				return Config::get()->path('main-local').'imgs/avatars/';
			}

			public function __construct()
			{
				parent::__construct();
			}

			public function getPlayerManipulator(): Player
			{
				$player = new Player;
				$player->setId($this->getId());
				return $player;
			}

			public function getOptionsManipulator(): Options
			{
				$options = new Options;
				$id = $this->selectThis(['id_options']);
				$options->setId(empty($id) ? 0 : $id);
				return $options;
			}

			public function getActivationLink(): string
			{
				$this->useTable('USERS_TO_ACTIVATION');
				$link = $this->selectThis(['link']);
				$this->useDefaultTable();
				if (empty($link)) return '';
				return $link;
			}

			public function getConfirmNewMailLink(): string
			{
				$this->useTable('USERS_CONFIRM_EMAILS');
				$link = $this->selectThis(['link']);
				$this->useDefaultTable();
				if (empty($link)) return '';
				return $link;
			}

			public function register(string $login, string $mail, string $password): int
			{
				if (!$this->isMailAvailable($mail))
				{
					throw new \Exception('Użytkownik o podanym e-mailu już istnieje');
				}
				else if (!$this->isLoginAvailable($login))
				{
					throw new \Exception('Użytkownik o podanym loginie już istnieje');
				}
				else
				{
					$now = date('Y-m-d H:i:s');
					$id = $this->insert([
						null, Options::makeDefaultOptions(),
						'normal', $mail, $login, static::hashPassword($password),
						0, static::getIpAddress(), false, $now, $now, '', ''
					]);

					$this->setId($id);
					$this->prepareActivation();
					return $id;
				}
				return -1;
			}

			public function isMailAvailable(string $mail): bool
			{
				$this->where('mail = ?', [$mail]);
				$userData = $this->select();
				return empty($userData);
			}

			public function isLoginAvailable(string $login): bool
			{
				$this->where('login = ?', [$login]);
				$userData = $this->select();
				return empty($userData);
			}

			public function login(string $loginOrMail, string $password): bool
			{
				$this->where('login = ? OR mail = ? LIMIT 1', [$loginOrMail, $loginOrMail]);
				$userData = $this->select();
				if (!empty($userData))
				{
					if (password_verify($password, $userData['password']))
					{
							$this->setId($userData['id']);
							$this->updateLastLoginDate();
							return true;
					}
				}
				return false;
			}

			public function changeAvatar(array $file): bool
			{
				if (!empty($file) && $file['error'] == 0 && $file['size'] < static::MAX_AVATAR_SIZE && strpos($file['type'], 'image/') !== false)
				{
					$oldFileName = static::localPath().$this->selectThis(['avatar']);
					if (file_exists($oldFileName)) unlink($oldFileName);

					$ext = '.'.pathinfo($file['name'])['extension'];
					$fileName = sha1_file($file['tmp_name']).$ext;
					$this->updateThis(['avatar' => $fileName]);
					move_uploaded_file($file['tmp_name'], static::localPath().$fileName);
					return true;
				}
				return false;
			}

			public function changePassword(string $password): void
			{
				$this->updateThis(['password' => static::hashPassword($password)]);
			}

			public function changeDescription(string $description): void
			{
				$this->updateThis(['description' => htmlentities($description)]);
			}

			public function isVerified(): bool
			{
				return $this->selectThis(['verified']) == true;
			}

			public function activation(string $link): bool
			{
				$this->useTable('USERS_TO_ACTIVATION');
				$this->where('link = ?', [$link]);
				$result = $this->select();
				if (!empty($result))
				{
					$this->setId($result['id']);
					$this->deleteThis();

					$this->useDefaultTable();
					$this->updateThis(['verified' => true]);
					return true;
				}
				$this->useDefaultTable();
				return false;
			}

			public function prepareConfirmNewMail(string $newMail): void
			{
				$link = $this->generateLink();
				$this->useTable('USERS_CONFIRM_EMAILS');
				$this->deleteThis();
				$this->insert([$this->getId(), $link, $newMail]);
				$this->useDefaultTable();
			}

			public function confirmNewMail(string $link): bool
			{
				$this->useTable('USERS_CONFIRM_EMAILS');
				$this->where('link = ?', [$link]);
				$result = $this->select();
				if (!empty($result))
				{
					$this->setId($result['id']);
					$this->deleteThis();

					$this->useDefaultTable();
					$this->updateThis(['mail' => $result['new_mail']]);
					return true;
				}
				$this->useDefaultTable();
				return false;
			}

			public function removeAllUserData(): void
			{
				$this->removeFromDeletePool();

				$this->useTable('USERS_CONFIRM_EMAILS');
				$this->deleteThis();

				$this->useTable('USERS_TO_ACTIVATION');
				$this->deleteThis();

				$this->useDefaultTable();

				$player = $this->getPlayerManipulator();
				$player->removeAllPlayerData();

				$options = $this->getOptionsManipulator();
				$options->deleteThis();

				$this->deleteThis();
			}

			public function addToDeletePool(): string
			{
				$link = $this->generateLink();
				$this->useTable('USERS_TO_DELETE');
				$this->insert([$this->getId(), $link, null]);
				$this->useDefaultTable();
				return $link;
			}

			public function removeFromDeletePool(): void
			{
				$this->useTable('USERS_TO_DELETE');
				$this->deleteThis();
				$this->useDefaultTable();
			}

			public function isInDeletePool(): bool
			{
				$this->useTable('USERS_TO_DELETE');
				$data = $this->selectThis();
				$this->useDefaultTable();
				return !empty($data);
			}

			public function confirmDelete(string $link): bool
			{
				$date = new \DateTime;
				$date->add(new \DateInterval('P15D'));

				$this->useTable('USERS_TO_DELETE');
				$this->where('link = ?', [$link]);
				if (!empty($this->select()))
				{
					$this->where('link = ?', [$link]);
					$this->update(['end_date' => $date->format('Y-m-d H:i:s')]);
					$this->useDefaultTable();
					return true;
				}
				$this->useDefaultTable();
				return false;
			}

			private function prepareActivation(): void
			{
				$link = $this->generateLink();
				$this->useTable('USERS_TO_ACTIVATION');
				$this->insert([$this->getId(), $link]);
				$this->useDefaultTable();
			}

			private function generateLink(): string
			{
				$data = $this->selectThis(['mail', 'start_date']);
				$str = $data['mail'].$data['start_date'];

				return hash('sha512', static::hashPassword($str));
			}

			private function updateLastLoginDate(): void
			{
				$this->updateThis(['last_login_date' => date('Y-m-d H:i:s')]);
			}

			static public function deleteUsersInPool(): void
			{
				$db = new static;
				$db->useTable('USERS_TO_DELETE');
				$db->where('end_date <= NOW() AND end_date IS NOT NULL');
				$ids = $db->selectArray(['id']);

				foreach ($ids as $id)
				{
					$db->setId($id);
					$db->removeAllUserData();
				}
			}

			static private function getIpAddress(): string
			{
				$ip = empty($_SERVER['REMOTE_ADDR']) ? '' : $_SERVER['REMOTE_ADDR'];
				if (!empty($_SERVER['HTTP_CLIENT_IP']))
					$ip = $_SERVER['HTTP_CLIENT_IP'];
				else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				return $ip;
			}

			static private function hashPassword(string $password): string
			{
				return password_hash($password, PASSWORD_DEFAULT);
			}
		}
	}

?>
