SOS.synchro.onerror = function(){}

SOS.synchro.onopen = function(){
	let timer = new SOSEngine.Timer, attempts = 0;

	function connect(){
		if (SOS.battleSynchro.readyState == 0 || SOS.managementServer.readyState == 0){
			if (attempts < 10){
				++attempts;
				timer.set(connect.bind(this), 50);
			}
		}
		else if (SOS.battleSynchro.readyState == 1 && SOS.managementServer.readyState == 1){
			this.query('init', {
				generalId: SOS.idGeneral,
				playerId: SOS.idPlayer
			});
			timer.clear();
		}
		else this.close();
	}
 	connect.call(this);
}

SOS.synchro.onclose = function(){
	if (SOS.canClose)
		document.location.href = 'index';
}

SOS.synchro.onmessage = function(msg){
	let query = JSON.parse(msg.data);
	console.log(msg.data);

	if (query.name === undefined) return;
	switch (query.name){
		case 'connected':{
			if (query.value.id_map == $('#startData').data('id_map')){
				let plr = new SOS.Player(query.value);
				plr.view.setPosition(query.value.posX, query.value.posY);
			}
			SOS.friendStack.changeStatus(query.value.id, 1);
		}
		break;

		case 'logouted':{
			$.each(SOS.players.storage, function(i, player){
				if (player.view.obj.data('id') == query.value.id){
					console.log(player);
					SOS.players.remove(i);
					SOS.playersList.remove(query.value.id);
					return false;
				}
			});
			SOS.friendStack.changeStatus(query.value.id);
		}
		break;

		case 'init':{
			SOS.beforeOnload();
			SOS.me = new SOS.Player(query.value);
			SOS.me.move = new SOS.Travel(SOS.me.view, SOS.me.anim);
			SOS.me.view.obj.off('click').off('dblclick').data('id', 'me');
			SOS.players.remove(SOS.players.storage.length-1);
			SOS.view.Scene.add(SOS.me.view);
			SOS.view.Camera.lookAt(SOS.me.view);
			SOS.onload();
		}
		break;

		case 'died-init':{
			SOS.timeToRespawn = query.value.time;
		}
		break;

		case 'died':{
			SOS.timeToRespawn = query.value.time;
			SOS.events.onDie({time: query.value.time});
		}
		break;

		case 'players':{
			SOS.playersList.removeAll();
			SOS.players.clear();
			$.each(query.value, function(i, value){
				(new SOS.Player(value));
			});
		}
		break;

		case 'mobs':{
			SOS.mobs.clear();
			$.each(query.value, function(i, value){
				(new SOS.Mob(value));
			});
		}
		break;

		case 'items':{
			SOS.items.clear();
			$.each(query.value, function(i, value){
				(new SOS.Item(value));
			});
		}
		break;

		case 'item-taken':{
			$.each(SOS.items.storage, function(i, item){
				if (item.icon.data('id') == query.value.id && item.icon.data('position') == query.value.posid){
					SOS.items.remove(i);
					return false;
				}
			});
		}
		break;

		case 'lvlup':{
			$.each(SOS.players.storage, function(i, player){
				if (player.view.obj.data('id') == query.value.id){
					player.updateDescription(query.value.lvl);
					return false;
				}
			});
		}
		break;

		case 'moved':{
			$.each(SOS.players.storage, function(i, player){
				if (player.view.obj.data('id') == query.value.id){
					player.anim.play('run');

					if (player.view.position.x > query.value.posX)
						player.move.turnLeft();
					else if (player.view.position.x < query.value.posX)
						player.move.turnRight();

					player.view.setPositionAnimate(query.value.posX, query.value.posY, 1, function(){
						player.move.timer.set(function(){player.move.stand();}, 100);
					});
					return false;
				}
			});
		}
		break;

		case 'change-map':{
			SOS.canClose = false;
			document.location.reload();
		}
		break;

		case 'chat':{
			if (query.value.search('[PM]') == 1 && query.value.search(SOS.me.name) == -1){
				if (!SOS.Config.chatMute && !SOS.Config.chatMutePm){
					SOS.Chat.newLine(query.value);
					if (SOS.Chat.main.obj.is(':hidden') && !SOS.Chat.pmNotify){
						SOS.Chat.pmNotify = true;
						SOS.Notification.addNotify('Nowa wiadomość prywatna', 15000, function(){
							SOS.Chat.pmNotify = false;
						});
					}
				}
			}
			else {
				if (!SOS.Config.chatMute)
					SOS.Chat.newLine(query.value);
			}
		}
		break;

		case 'chat-error':{
			SOS.Chat.newLineError('Nie udało się wysłać wiadomości do gracza '+query.value+'.');
		}
		break;

		case 'friends':{
			SOS.friendStack.removeAll();
			$.each(query.value, function(i, value){
				SOS.friendStack.add(value);
			});
		}
		break;

		case 'enemies':{
			$.each(query.value, function(i, value){
				SOS.enemiesList.add(value);
			});
		}
		break;

		case 'friend-offer':{
			if (!SOS.Config.unsetInvitations)
				SOS.Chat.makeOfferBox(query.value.id, query.value.name);
		}
		break;

		case 'friend-add':{
			SOS.Chat.addFriend(query.value.id);
		}
		break;

		case 'friend-remove':{
			SOS.Chat.removeFriend(query.value.id, false);
		}
		break;
	}
}
