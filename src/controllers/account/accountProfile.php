<?php

	namespace SOS
	{
		trait AccountProfile
		{
			public function makeProfile(int $id): string
			{
				try
				{
					if (empty($id) || !is_numeric($id))
						throw new \Exception;

					$this->user->setId($id);
					PlayerManager::get()->setId($id);
					$data = $this->user->selectThis(['login', 'description', 'start_date', 'permission']);
					if (!empty($data))
					{
						$data['start_date'] = (new \DateTime($data['start_date']))->format('d.m.Y');
						$data['generals'] = PlayerManager::get()->makeGeneralsIconsListWithServers();
						$data['avatar'] = $this->getAvatar();

						if ($data['permission'] == 'normal')
							$data['permission'] = '';
						else if ($data['permission'] == 'mod')
							$data['permission']	= '<h6 class="text-success">Moderator</h6>';
						else $data['permission'] = '<h6 class="text-danger">Administrator</h6>';

						return $this->view->showProfile($data);
					}
					else throw new \Exception;
				}
				catch(\Exception $e)
				{
					header('location: error?type=404');
					exit;
				}
				$this->user->setId($this->getUserId());
				PlayerManager::get()->setId($this->getUserId());
			}

			public function makeProfileEditor(): string
			{
				$data['description'] = $this->user->selectThis(['description']);
				if (empty($data['description']))
					$data['description'] = '';

				return $this->view->showProfileEditor($data);
			}

			public function changeProfileDescription(): void
			{
				$this->user->changeDescription($_POST['description']);
				header('location: profile-edit-form');
				exit;
			}

			public function	changeAvatar(): void
			{
				if ($this->user->changeAvatar($_FILES['avatar']))
					setcookie('message', 'Miniatura została zmieniona.');
				else setcookie('message', 'Nie udało się zmienić miniatury.');

				header('location: profile-edit-form');
				exit;
			}

			public function getAvatar(): string
			{
				$public = Config::get()->path('main');
				$fileName = $this->user->selectThis(['avatar']);
				if (empty($fileName)) $fileName = 'imgs/user.png';
				else $fileName = 'imgs/avatars/'.$fileName;
				return $public.$fileName;
			}
		}
	}

?>
