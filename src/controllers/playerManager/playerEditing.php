<?php

	namespace SOS
	{
		trait PlayerEditing
		{
			public function makeEditGeneralForm(): string
			{
				try
				{
					if (!empty($_GET['id']) && is_numeric($_GET['id']))
					{
						$general = $this->player->getGeneralManipulator($_GET['id']);
						if (!$general)
							throw new Exception;

						$data = $general->selectThis(['id', 'name']);
 						$data['outfits'] = $this->makeOutfits($general);

						return $this->view->showEditGeneralForm($data);
					}
					else throw new Exception;
				}
				catch (Exception $e)
				{
					header('location: error');
					exit;
				}
			}

			private function makeOutfits(&$general): string
			{
				$output = '';
				$outfit = $general->getOutfitManipulator();
				$actual = $outfit->getId();

				$characterId = $general->getHeroManipulator()->getCharacterManipulator()->getId();
				$owned = $outfit->getOwnedCharacterOutfits($characterId, $this->player->getId());

				foreach ($owned as $id)
				{
					$outfit->setId($id);
					$data = $outfit->selectThis(['id', 'name', 'dir AS path']);
					$data['path'] = $outfit->getViewPath();

					if ($actual == $id)
						$data['checked'] = 'checked';
					$output .= $this->view->showChangeOutfitOption($data);
				}
				return $output;
			}

			public function changeOutfit(): void
			{
				try
				{
					if (!$this->validChangeOutfit())
						throw new \Exception;

					$general = $this->player->getGeneralManipulator($_POST['general-id']);
					if (!$general)
						throw new \Exception;
					else if ($general->selectThis(['id_outfit']) == $_POST['outfit-id'])
						throw new \Exception;
					else
					{
						$general->updateThis(['id_outfit' => $_POST['outfit-id']]);
						setcookie('message', 'Wygląd postaci został zmieniony.');
					}
				}
				catch (\Exception $e)
				{
					setcookie('message', 'Zmiana wyglądu nie powiodła się.');
				}

				header('location: edit-player-form?id='.$_POST['general-id']);
				exit;
			}

			private function validChangeOutfit(): bool
			{
				return !empty($_POST['general-id']) && is_numeric($_POST['general-id']) && !empty($_POST['outfit-id']) && is_numeric($_POST['outfit-id']);
			}

			public function changeName(): void
			{
				try
				{
					if (!$this->validChangeName())
						throw new \Exception;

					if (Account::get()->hasAmountOfCoins(10) && $this->player->changeGeneralName($_POST['general-id'], $_POST['name']))
					{
						Account::get()->reduceAmountOfCoins(10);
						setcookie('message', 'Nazwa postaci została zmieniona.');
					}
					else throw new \Exception;
				}
				catch (\Exception $e)
				{
					if (!Account::get()->hasAmountOfCoins(10))
						setcookie('message', 'Nie wystarczająca ilość funduszy.');
					else setcookie('message', 'Zmiana nazwy postaci nie powiodła się.');
				}

				header('location: edit-player-form?id='.$_POST['general-id']);
				exit;
			}

			private function validChangeName(): bool
			{
				if (empty($_POST['general-id']) || !is_numeric($_POST['general-id']))
					return false;

				$_POST['name'] = empty($_POST['name']) ? '' : trim($_POST['name']);
				return preg_match_all("/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/", $_POST['name']);
			}

			public function removeGeneral(): void
			{
				if (!empty($_POST['general-id']))
				{
					if ($this->player->deleteAfterYouDidNotPlayForFifteenDays($_POST['general-id']))
						setcookie('message', 'Postać została usunięta.', 0, '', '.sos.localhost');
					else
					{
						setcookie('message', 'Postać nie możę zostać usunięta ponieważ ostatnio była używana.');
						header('location: edit-player-form?id='.$_POST['general-id']);
						exit;
					}
				}
				header('location: player-manager');
				exit;
			}
		}
	}

?>
