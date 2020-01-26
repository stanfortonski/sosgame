<!doctype html>
<html lang="pl">
<head>
	<title><?php echo $window->getTitle(); ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Stasiek Fortoński">
	<meta name="robots" content="noindex">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ranga:400,700&amp;subset=latin-ext">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merienda">
	<?php echo $styles; ?>
</head>
<body>
	<div id="splash" class="centrum loading unselectable" unselectable="on">
		SOSGAME
		<div class="bar">
			<div class="label">Ładowanie...</div>
		</div>
	</div>

	<main>
		<div id="mainInterface">
			<header class="header unselectable" unselectable="on">
				<div id="ping" data-title="Ping"></div>
				<div id="worldInfo"></div>
			</header>


			<div id="SOSEngine" class="SOSEngine">
				<div id="diedInformation" class="centrum introduce unselectable" unselectable="on"><p>Dostałeś bęcki, właśnie się kurujesz.</p><p class="time"></p></div>
				<div class="window">
					<?php echo $window->content()->get('main'); ?>
				</div>
			</div>

			<aside class="rightMenu unselectable" unselectable="on">
				<nav>
					<button type="button" id="map" class="button focus icon icon-map" data-title="Mapa (M)"></button>
					<button type="button" id="team" class="button focus icon icon-team" data-title="Drużyna (G)"></button>
					<button type="button" id="eq" class="button focus icon icon-eq" data-title="Ekwipunek (I)"></button>
					<button type="button" id="chat" class="button focus icon icon-envelope" data-title="Chat (T)"></button>
				</nav>
			</aside>

			<footer class="footer unselectable" unselectable="on">
				<nav>
					<button type="button" id="options" class="button focus icon icon-options" data-title="Opcje (O)"></button>
					<a href="<?php echo $mainPath; ?>" target="_blank" role="button" class="button focus icon icon-tools" data-title="Pomoc"></a>
					<button type="button" id="exit" class="button focus icon icon-exit" data-title="Wyjście"></button>
				</nav>
			</footer>
		</div>

		<div id="battleInterface">
			<aside class="leftMenu"><div class="teamPlace"></div></aside>

			<div id="battleWorld" class="SOSEngine">
				<div class="window">
					<div class="scene" data-texture="url(imgs/maps/war_ground.jpg)" data-width="300" data-height="300"></div>
				</div>
			</div>

			<aside class="rightMenu"><div class="teamPlace"></div></aside>

			<footer class="footer">
				<button type="button" class="focus button">Obrona<button>
				<button type="button" class="focus button">Pomiń</button>
				<button type="button" class="focus button">Auto walka</button>
				<button type="button" class="focus button">Uciekaj</button>
			</footer>
		</div>
	</main>

	<div id="contents">
		<div id="miniMap">
			<div class="window">
				<?php echo $window->content()->get('main'); ?>
			</div>
		</div>

		<div class="chat-main">
			<div class="position">
				<section class="screen"></section>
				<nav class="nav">
					<button id="usersButton" class="button focus icon icon-players" type="button" data-title="Lista graczy na mapie"></button>
					<button id="friendsButton" class="button focus icon icon-heart" type="button" data-title="Znajomi"></button>
					<button id="blockedButton" class="button focus icon icon-enemies" type="button" data-title="Zablokowani"></button>
				</nav>
			</div>
		</div>

		<div class="chat-footer">
			<input type="text" class="input" placeholder="Twoja wiadomość">
			<button type="button" class="button focus submit">Wyślij</button>
		</div>

		<div class="skills">
			skills
		</div>

		<div class="team">
			<div class="teamPlace"></div>
		</div>

		<div class="teamReserve">
			<div class="teamPlace"></div>
		</div>

		<div class="heroEquipment">
			<table class="equipment">
				<tr>
					<td style="visibility: hidden;"></td>
					<td data-title="Hełm" data-typeitem="4"><div class="icon icon-helmet default"></div></td>
					<td data-title="Amulet" data-typeitem="8"><div class="icon icon-neaklace default"></div></td>
				</tr>
				<tr>
					<td data-title="Lewa ręka" data-typeitem="11, 12, 13, 14"><div class="icon icon-hand default"></div></td>
					<td data-title="Górna część uzbrojenia" data-typeitem="5"><div class="icon icon-upperbody default"></div></td>
					<td data-title="Prawa ręka" data-typeitem="10, 11, 13, 15"><div class="icon icon-hand default"></div></td>
				</tr>
				<tr>
					<td data-title="Pierścień" data-typeitem="9"><div class="icon icon-ring default"></div></td>
					<td data-title="Dolna cześć uzbrojenia" data-typeitem="6"><div class="icon icon-lowerbody default"></div></td>
					<td data-title="Buty" data-typeitem="7"><div class="icon icon-boots default"></div></td>
				</tr>
			</table>
		</div>

		<div class="heroStats">
			<table>
				<tr>
					<td colspan="2">
						<img class="icon" alt="">
					</td>
				</tr>
				<tr>
					<th>Poziom</th>
					<td class="lvl"></td>
				</tr>
				<tr>
					<th>Punkty życia</th>
					<td><span class="hp_min"></span>/<span class="hp_max"></span></td>
				</tr>
				<tr>
					<th>Energia</th>
					<td class="energy"></td>
				</tr>
				<tr>
					<th>Siła</th>
					<td class="strength"></td>
				</tr>
				<tr>
					<th>Zręczność</th>
					<td class="dexterity"></td>
				</tr>
				<tr>
					<th>Współczynnik mocy magicznej</th>
					<td class="magic_power"></td>
				</tr>
				<tr>
					<th>Siła ciosu krytycznego</th>
					<td class="critical_strength"></td>
				</tr>
				<tr>
					<th>Szansa na cios krytyczny</th>
					<td class="critical_chance"></td>
				</tr>
				<tr>
					<th>Szasna na unikniecie ciosu</th>
					<td class="escape"></td>
				</tr>
				<tr>
					<th>Szansa na wyprowadzenie kontry</th>
					<td class="counter"></td>
				</tr>
			</table>
		</div>

		<div class="options">
			<table>
				<tr>
					<th>Głośności muzyki</th>
					<td>
						<div id="musicVolume">
							<div class="ui-slider-handle"></div>
						</div>
					</td>
				</tr>
				<tr>
					<th>Głośności efektów</th>
					<td>
						<div id="effectVolume">
							<div class="ui-slider-handle"></div>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="soundMute">Wyłącz dźwięk</label></th>
					<td><input type="checkbox" id="soundMute" class="focus"></td>
				</tr>
				<tr>
					<th><label for="chatMute">Wyłącz czat</label></th>
					<td><input type="checkbox" id="chatMute" class="focus"></td>
				</tr>
				<tr>
					<th><label for="chatMutePm">Zablokuj prywatne wiadomości</label></th>
					<td><input type="checkbox" id="chatMutePm" class="focus"></td>
				</tr>
				<tr>
					<th><label for="pvpAble">Ignoruj zaposzenia do pojedynków</label></th>
					<td><input type="checkbox" id="pvpAble" class="focus"></td>
				</tr>
				<tr>
					<th><label for="unsetInvitations">Ignoruj zaproszenia do znajomych</label></th>
					<td><input type="checkbox" id="unsetInvitations" class="focus"></td>
				</tr>
				<tr>
					<th><label for="saveInterface">Blokuj zapis pozycji i rozmiaru interfejsu</label></th>
					<td><input type="checkbox" id="saveInterface" class="focus"></td>
				</tr>
				<tr>
					<th>Resetuj pozycje i rozmiary interfejsu</th>
					<td><button class="button focus" id="resetInterface">Reset</button></td>
				</tr>
			</table>
		</div>
	</div>

	<?php echo $window->content()->get('config'); ?>
	<?php echo $scripts; ?>
	<?php echo $window->content()->get('socket'); ?>
</body>
</html>
