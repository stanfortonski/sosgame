SOS.Player = function(data){
	SOS.Character.call(this, data);
	this.move = new SOS.Movement(this.view, this.anim);
	this.description = $('<p class="description"></p>');
	this.initPlayer(data);
}
SOS.Player.prototype = Object.create(SOS.Character.prototype);

SOS.Player.prototype.initPlayer = function(data){
	this.view.obj.addClass('player').prepend(this.description);
	this.view.obj.click(this.clickOnPlayer.bind(this));

	this.updateDescription();

	if (SOS.enemiesList.get(data.id).length > 0)
		this.description.addClass('enemy');
	else if (SOS.friendStack.get(data.id).length > 0)
		this.description.addClass('friend');

	SOS.players.add(this);
	SOS.playersList.add({lvl: data.lvl, id: data.id, name: data.name});
}

SOS.Player.prototype.updateDescription = function(){
	let data = this.view.obj.data();
	this.description.html(data.name+' '+data.lvl+' LVL');
}

SOS.Player.prototype.clickOnPlayer = function(e){
	var id = this.view.obj.data('id'),
		contextMenu = [{
		name: 'Rozmawiaj',
		click: this.clickTalk.bind(this)
	},{
		name: 'Zaatakuj',
		click: this.clickAttack.bind(this)
	},{
		name: 'Handluj',
		click: this.clickTrade.bind(this)
	}];

	if (this.description.hasClass('friend')){
		contextMenu.push({
			name: 'Usuń ze znajomych',
			click: SOS.Chat.removeFriend.bind(this, id)
		});
	}
	else if (this.description.hasClass('enemy')){
		contextMenu.push({
			name: 'Odblokuj',
			click: SOS.Chat.removeEnemy.bind(SOS.Chat, id)
		});
	}
	else{
		contextMenu.push({
			name: 'Dodaj do znajomych',
			click: SOS.Chat.offerFriend.bind(SOS.Chat, id)
		});

		contextMenu.push({
			name: 'Zablokuj',
			click: SOS.Chat.addEnemy.bind(SOS.Chat, id)
		});
	}

	SOS.ContextMenu.setList(e, this.view.obj, contextMenu);
	e.stopPropagation();
}

SOS.Player.prototype.clickTalk = function(){
	SOS.Chat.main.open();
	SOS.Chat.changeListener(this.view.obj.data('name'));
}

SOS.Player.prototype.clickAttack = function(e){
	this.toFollow.setPosition(this.view.position.x, this.view.position.y);

	let self = this;
	SOS.me.move.to(self.toFollow, function(){
		SOS.battleInterface.obj.show();
		SOS.mainInterface.obj.hide();
		SOS.battleView.Scene.add(SOS.me);
		SOS.battleView.Scene.add(self.view);
		SOS.battleView.Camera.lookAt(SOS.me.view);
	});
}

SOS.Player.prototype.clickTrade = function(e){
	console.log('Handel');
}
