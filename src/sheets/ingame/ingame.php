<?php

	namespace STV\Sheets
	{
		class InGame extends \STV\Sheet
		{
			public function render(\STV\Window &$window): void
			{
				$mainPath = \SOS\Config::get()->path('main');

				$window->setStyles([
					'reset.css',
					$mainPath.'css/jquery-ui.min.css',
					'SOSEngine.css',
					'main.css',
					'world.css',
					'form.css',
					'explorer-box.css',
					'components.css',
					'other.css',
					'interface.main.css',
					'interface.battle.css',
					'media.css',
					$mainPath.'css/perfect-scrollbar.css',
				]);

				$window->setScripts([
					$mainPath.'js/libs/jquery/jquery.min.js',
					$mainPath.'js/libs/jquery/jquery-ui.min.js',
					$mainPath.'js/libs/jquery/jquery-ui.touch-punch.min.js',
					$mainPath.'js/libs/jquery/plugins/support.js',
					$mainPath.'js/libs/perfect-scrollbar.min.js',
					$mainPath.'js/libs/js.cookie.min.js',
					'SOSEngine/src/main.js',
					'SOSEngine/src/timer.js',
					'SOSEngine/src/group/groupManipulator.js',
					'SOSEngine/src/group/group.js',
					'SOSEngine/src/group/staticGroup.js',
					'SOSEngine/src/object/objectManipulator.js',
					'SOSEngine/src/object/objectMath.js',
					'SOSEngine/src/object/object.js',
					'SOSEngine/src/object/staticObject.js',
					'SOSEngine/src/image/image.js',
					'SOSEngine/src/image/staticImage.js',
					'SOSEngine/src/scene.js',
					'SOSEngine/src/window.js',
					'SOSEngine/src/camera-effects.js',
					'SOSEngine/src/camera.js',
					'SOSEngine/src/shapes/cross.js',
					'SOSEngine/src/shapes/line.js',
					'SOSEngine/src/shapes/arc.js',
					'SOSEngine/src/shapes/circle.js',
					'SOSEngine/src/make.js',
					'SOS/main.js',
					'SOS/config.js',
					'SOS/group.js',
					'SOS/audio.js',
					'SOS/objects/properties/animationList.js',
					'SOS/objects/properties/movement.js',
					'SOS/objects/properties/movement-ext.js',
					'SOS/objects/properties/travel.js',
					'SOS/objects/properties/routing.js',
					'SOS/interfaces/components/title.js',
					'SOS/interfaces/components/explorerBox.js',
					'SOS/interfaces/components/bar.js',
					'SOS/interfaces/components/contextMenu.js',
					'SOS/interfaces/components/stoper.js',
					'SOS/interfaces/components/charactersNet.js',
					'SOS/interfaces/components/tabs.js',
					'SOS/interfaces/components/equipment.js',
					'SOS/interfaces/components/list.js',
					'SOS/interfaces/components/friendStack.js',
					'SOS/loading.js',
					'SOS/objects/item.js',
					'SOS/objects/character.js',
					'SOS/objects/player.js',
					'SOS/objects/mob.js',
					'SOS/interfaces/main.js',
					'SOS/interfaces/battle.js',
					'SOS/battle.js',
					'SOS/events/events.js',
					'SOS/events/movement.js',
					'SOS/events/touchCamera.js',
					'SOS/events/interface.js',
					'SOS/events/options.js',
					'SOS/events/battle.js',
					'SOS/events/doors.js',
					'SOS/events/die.js',
					'SOS/chat.js',
					'SOS/onload.js',
					'SOS/sockets/socket.js'
				]);

				$title = $window->getTitle();
				$styles = $this->outputStyles($window->getStyles());
				$config = $window->content()->get('config');
				$content = $window->content()->get('main');
				$battleContent = $window->content()->get('battle');
				$scripts = $this->outputScripts($window->getScripts());
				$socket = $window->content()->get('socket');

				require_once 'template.php';
			}
		}
	}

?>
