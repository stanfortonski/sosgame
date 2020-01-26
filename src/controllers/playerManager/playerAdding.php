<?php

	namespace SOS
	{
		trait PlayerAdding
		{
			public function addGeneral(): bool
			{
				if ($this->validAddingForm())
					return $this->player->addGeneral($_POST['server'], $_POST['char'], $_POST['name']);
				return false;
			}

			public function makeAddPlayerForm(): string
			{
				$nameset = $this->makeChooseServerAndName();
				$races = $this->makeChooseRace();
				return $this->view->showAddPlayerForm(['nameset' => $nameset, 'races' => $races]);
			}

			private function makeChooseServerAndName(): string
			{
				$options = '';
				$serverManager = new Server;
				$serverManager->orderBy('id');
				$servers = $serverManager->selectArray();
				foreach ($servers as $server)
				{
					if ($this->player->canAddGeneral($server['id']))
						$options .= '<option value="'.$server['id'].'">'.$server['name'].'</option>';
				}
				return $this->view->showChoosingServerAndName(['options' => $options]);
			}

			public function makeChooseRace(): string
			{
				$output = '';
				$races = (new Race)->selectArray();
				foreach ($races as $race)
				{
					$race['src'] = Race::getPath().$race['icon'];
					$output .= $this->view->showOptionRace($race);
				}
				return $output;
			}

			public function makeChooseGeneralPreset(int $raceId): string
			{
				$output = '';
				$character = new Character;
				$outfit = new Outfit;
				$generalPresets = General::getPresetsCharactersForRace($raceId);
				foreach ($generalPresets as $id)
				{
					$character->setId($id);
					$data = $character->selectThis();
					$outfit->setId($character->selectThis(['id_outfit']));
					$data['path'] = $outfit->getViewPath();
					$output .= $this->view->showOptionGeneralPreset($data);
				}
				return $output;
			}

			private function validAddingForm(): bool
			{
				if (empty($_POST['server']) || empty($_POST['char']))
					return false;

				$_POST['name'] = empty($_POST['name']) ? '' : trim($_POST['name']);
				return preg_match_all("/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/", $_POST['name']);
			}
		}
	}

?>
