<?php

	namespace SOS
	{
		trait PlayerChoosing
		{
			public function makeGeneralsList(string $action, string $value): string
			{
				$serversWithGenerals = $this->player->getServersWithGenerals();
				if (!empty($serversWithGenerals))
				{
					$general = new General;
					$server = new Server;
					$hero = new Hero;
					$options = '';
					foreach ($serversWithGenerals as $serverAndGenerals)
					{
						$server->setId($serverAndGenerals[0]);
						$serverName = $server->selectThis(['name']);
						$options .= '<option disabled>'.$serverName.'</option>';

						foreach ($serverAndGenerals[1] as $generalId)
						{
							$general->setId($generalId);
							$data = $general->selectThis(['id', 'id_hero', 'name']);
							$hero->setId($data['id_hero']);
							$lvl = $hero->selectThis(['lvl']);
							$val = json_encode(['server' => $serverAndGenerals[0], 'general' => $data['id']]);
							$options .= '<option value=\''.$val.'\'>'.$data['name'].' | '.$lvl.' LVL</option>';
						}
					}
					return $this->view->showGeneralsList(['options' => $options, 'action' => $action, 'value' => $value]);
				}
				return '';
			}

			public function makeGeneralsIconsListWithServers(): string
			{
				$output = '';
				$serversWithGenerals = $this->player->getServersWithGenerals();
				if (!empty($serversWithGenerals))
				{
					$general = new General;
					$server = new Server;

					foreach ($serversWithGenerals as $serverAndGenerals)
					{
						$server->setId($serverAndGenerals[0]);
						$output .= '<h4 class="w-100 p-3">'.$server->selectThis(['name']).'</h4>';

						foreach ($serverAndGenerals[1] as $idGeneral)
						{
							$general->setId($idGeneral);
							$output .= '<div class="col-6 col-sm-4 col-md-3">';
							$output .= $this->getGeneralThumbnail($idGeneral, $general->selectThis(['name'])).'</div>';
						}
					}
				}
				return $output;
			}

			public function makeGeneralThumbnail(): string
			{
				if (!empty($_GET['general-id']) && is_numeric($_GET['general-id']))
					return $this->getGeneralThumbnail($_GET['general-id']);
				return '';
			}

			private function getGeneralThumbnail(int $idGeneral, string $name = '', int $lvl = -1): string
			{
				if ($general = $this->player->getGeneralManipulator($idGeneral))
				{
					$path = $general->getOutfitManipulator()->getViewPath();
					$hero = $general->getHeroManipulator();
					$lvl = $lvl == -1 ? null : $hero->selectThis(['lvl']);
					$name = strlen($name) > 0 ? $name : $hero->getCharacterManipulator()->selectThis(['name']);
					return $this->view->showGeneralThumbnail(['path' => $path, 'name' => $name, 'lvl' => $lvl]);
				}
				return '';
			}
		}
	}

?>
