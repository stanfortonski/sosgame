SOS.Chat = function(){
	this.main = new SOS.interfaces.ExplorerBox({name: 'chat', savePosition: true, saveSize: true});
	this.userBox = new SOS.interfaces.ExplorerBox;
	this.friendBox = new SOS.interfaces.ExplorerBox;
	this.blockedBox = new SOS.interfaces.ExplorerBox;
	this.linkBox = new SOS.interfaces.ConfirmBox;
	this.offerBox = new SOS.interfaces.ConfirmBox;
	this.openButton = $('#chat');
	this.name = null;
	this.pmNotify = false;
	this.allowOffInformation = true;
	this.invitations = [];
	this.init();
}

SOS.Chat.prototype.init = function(){
	this.initContents();
	this.initSizes();
	this.initEvents();
}

SOS.Chat.prototype.initContents = function(){
	this.main.obj.addClass('chat');
	this.main.setContent('Chat', $('.chat-main').html(), $('.chat-footer').html());
	this.screen = this.main.content.find('.screen');
	this.submit = this.main.footer.find('.submit');
	this.input = this.main.footer.find('.input');

	this.friendBox.setContent('Znajomi', SOS.friendStack.obj);
	this.blockedBox.setContent('Zablokowani', SOS.enemiesList.obj);
	this.userBox.setContent('Aktywni gracze', SOS.playersList.obj);
	this.linkBox.setContent('Przekierowanie', 'Czy chesz przejść na zewnętrzną stronę?');
	this.linkBox.addButton('goto');
	this.linkBox.addButton('exit', 'Zamknij');
	this.offerBox.setContent('Zaproszenie');
	this.offerBox.addButton('accept');
	this.offerBox.addButton('refuse', 'Odrzuć');

	let scroll = new PerfectScrollbar(this.screen[0], {
		handlers: ['click-rail', 'drag-thumb', 'wheel', 'touch']
	}), scrollNav = new PerfectScrollbar(this.main.content.find('.nav')[0], {
		handlers: ['click-rail', 'drag-thumb', 'wheel', 'touch']
	});
}

SOS.Chat.prototype.initSizes = function(){
	this.offerBox.obj.css({width: '50%'});
	this.userBox.obj.resizable({maxWidth: 320});
	this.blockedBox.obj.resizable({maxWidth: 320});
	this.friendBox.obj.css({width: '100%', height: 200}).resizable({disabled: true});
	this.friendBox.content.css({padding: 0});
}

SOS.Chat.prototype.initEvents = function(){
	var self = this;

	this.main.onOpen = function(){
		self.linkBox.setPositionFromObject(self.main.obj, 'center', 'center');
		self.userBox.setPositionFromObject(self.main.obj, 'outside right', 'inside top');
		if (self.userBox.obj.is(':hidden'))
			self.userBox.onClose();
		else self.userBox.onOpen();
	}
	this.main.onResize = this.main.onDrag = this.main.onOpen;

	this.userBox.onOpen = function(){
		self.blockedBox.setPositionFromObject(self.userBox.obj, 'outside right', 'inside top');
	}
	this.userBox.onResize = this.userBox.onDrag = this.userBox.onOpen;

	this.userBox.onClose = function(){
		self.blockedBox.setPositionFromObject(self.main.obj, 'outside right', 'inside top');
	}

	this.offerBox.onOpen = function(){
		self.offerBox.setPosition('center', 'center');
		if (self.invitations.length > 0){
			self.offerBox.setContent('Zaproszenie', 'Gracz '+self.invitations[0].name+' zaprasza cię do swojej listy znajomych. Czy akceptujesz zaproszenie?');
			self.offerBox.changeButton('accept', 'Akceptuj', function(){
				SOS.synchro.query('friend-add', {idOffered: self.invitations[0].id});
			});
		}
	}

	this.offerBox.onClose = function(){
		self.invitations.splice(0, 1);
		if (self.invitations.length > 0)
			self.offerBox.open();
	}

	this.openButton.click(function(){
		self.main.toggle();
	});

	this.submit.click(this.sendMessage.bind(this));

	this.input.focus(function(){
		SOS.events.offMovement();
		SOS.events.offInterface();
	});

	this.input.blur(function(){
		SOS.events.onMovement();
		SOS.events.onInterface();
	});

	this.input.keydown(function(e){
		if (e.keyCode == 13 || e.which == 13){
			self.sendMessage();
		}
	});

	$(document).on('click', '.chat .screen .name', function(){
		self.changeListener($(this).text());
	});

	$(document).on('click', '.chat .screen .link', function(){
		self.makeLinkMessage($(this).data().href);
	});

	$('#friendsButton').click(function(){
		self.friendBox.toggle();
	});

	$('#blockedButton').click(function(){
		self.blockedBox.toggle();
	});

	$('#usersButton').click(function(){
		self.userBox.toggle();
	});
}

SOS.Chat.prototype.makeLinkMessage = function(link){
	this.linkBox.close();
	this.linkBox.changeButton('goto', '<a href="'+link+'" target="_blank" rel="nofollow">Otwórz w nowej karcie</a>');
	this.linkBox.open();
}

SOS.Chat.prototype.prepareMessageData = function(msg){
 	var message = {},
		pattern = /^@([a-z0-9 -])+;/gim;

	if (msg != ''){
		let exec = pattern.exec(msg);
		msg = msg.replace(pattern, '');
		msg = msg.substring(0, 250);
		if (msg == '');
		else if (exec != null){
			message.name = exec[0].replace('@', '').replace(';', '');
			message.content = msg;
		}
		else message.content = msg;
	}
	return message;
}

SOS.Chat.prototype.sendMessage = {};
(function(){
	let timer = new SOSEngine.Timer,
		isCounting = false,
		count = 0;

	SOS.Chat.prototype.sendMessage = function(){
		try{
			if (SOS.Config.chatMute) throw 1;
			else if (count > 8) throw 4;

			let message = this.prepareMessageData(this.input.val().trim());
			if (message.content !== undefined){
				console.log('das');
				if (message.name !== undefined){
					this.name = message.name;
					if (this.name.toLowerCase() == SOS.me.view.obj.data('name').toLowerCase()) throw 2;
					else if (!this.canSendToPlayerOfName(this.name)) throw 3;

					SOS.synchro.query('chat-with', {name: message.name, content: message.content});
				}
				else{
					this.name = null;
					SOS.synchro.query('chat', {content: message.content});
				}
				++count;
			}
			this.reload();
		}
		catch (err){
			switch(err){
				case 1:{
					if (this.allowOffInformation){
						this.newLineError('Chat jest wyłączony! Sprawdź swoje ustawienia.');
						this.allowOffInformation = false;
					}
				}
				break;

				case 2:
					this.newLineError('Nie można wysyłać wiadomości do samego siebie!');
				break;

				case 3:
					this.newLineError('Nie można wysyłać wiadomości do zablokowanego gracza!');
				break;

				case 4:{
					if (this.allowOffInformation){
						this.newLineError('Aby wysłać kolejną wiadomość musisz poczekać.');
						this.allowOffInformation = false;
					}
				}
				break;
			}
		}
		finally{
			if (!isCounting){
				isCounting = true;
				timer.set(function(){count = 0; isCounting = false; this.allowOffInformation = true;}.bind(this), 30000);
			}
		}
	}
})();

SOS.Chat.prototype.reload = function(){
	if (this.name != null)
		this.input.val('@'+this.name+';');
	else this.input.val('');
	this.input.focus();
}

SOS.Chat.prototype.canSendToPlayerOfName = function(name){
	if (SOS.enemiesList.findByName(name) == -1)
		return true;
	return false;
}

SOS.Chat.prototype.changeListener = function(name){
	let message = this.input.val().replace(/^@([a-z0-9 ])+;/gim, '');
	this.input.val('@'+name+';'+message);
	this.main.focus();
	this.input.focus();
}

SOS.Chat.prototype.changeToLinks = function(msg){
  let pattern = /(https?:\/\/[^\s]+)/g;
  return msg.replace(pattern, function(url){
    return '<span class="link" data-href="'+url+'" data-title="'+url+'">LINK</span>';
  });
}

SOS.Chat.prototype.newLine = function(msg){
	this.screen.append('<p>'+this.changeToLinks(msg)+'</p>');
	SOS.interfaces.Title.init();
	this.scrollDown();
}

SOS.Chat.prototype.newLineError = function(msg){
	this.name = null;
	this.reload();
	this.screen.append('<p class="error">'+msg+'</p>');
	this.scrollDown();
}

SOS.Chat.prototype.scrollDown = function(){
	if (this.canScroll())
		this.screen.scrollTop(this.screen[0].scrollHeight);
}

SOS.Chat.prototype.canScroll = function(){
	let pHeight = this.screen.find('p').outerHeight() || 0;
	return this.screen[0].scrollHeight - this.screen.scrollTop() <= this.screen.outerHeight() + pHeight;
}

SOS.Chat.prototype.makeOfferBox = function(id, name){
	if (this.invitations.length < 5){
		$.each(this.invitations, function(i, obj){
			if (obj.id == id){
				this.invitations.splice(i, 1);
				return false;
			}
		});
		this.invitations.push({id: id, name: name});
		this.offerBox.open();
	}
}

SOS.Chat.prototype.offerFriend = {};
(function(){
	let timer = new SOSEngine.Timer,
		offeredCount = Cookies.get('offeredCount') || 0,
		isSet = false;

	function decreaseOfferedCount(){
		Cookies.set('offeredCount', --offeredCount);
		if (offeredCount == 0) isSet = false;
		else timer.set(decreaseOfferedCount, 20000);
	}

	SOS.Chat.prototype.offerFriend = function(id){
		if (offeredCount < 5){
			Cookies.set('offeredCount', ++offeredCount);
	  	SOS.synchro.query('friend-offer', {idTarget: id});
			SOS.Notification.addNotify('Wysłano zaproszenie');
		}

		if (!isSet){
			isSet = true;
			timer.set(decreaseOfferedCount, 20000);
		}
	}
})();

SOS.Chat.prototype.addFriend = function(id){
	$.each(SOS.players.storage, function(i, player){
		let data = player.view.obj.data();
		if (data.id == id){
			player.description.removeClass('enemy').addClass('friend');
			SOS.friendStack.add({id: id, name: data.name, lvl: data.lvl, path: player.anim.imgPath});
			return false;
		}
	});

	SOS.enemiesList.remove(id);
	SOS.friendStack.changeStatus(id, true);
	SOS.Notification.addNotify('Dodano znajomego');
}

SOS.Chat.prototype.removeFriend = function(id, send){
	$.each(SOS.players.storage, function(i, player){
		if (player.view.obj.data('id') == id){
			player.description.removeClass('friend');
			return false;
		}
	});
	SOS.friendStack.remove(id);

	if (send === undefined)
  	SOS.synchro.query('friend-remove', {idTarget: id});
}

SOS.Chat.prototype.addEnemy = function(id){
	$.each(SOS.players.storage, function(i, player){
		let data = player.view.obj.data();
		if (data.id == id){
			player.description.addClass('enemy');
			SOS.enemiesList.add({id: id, name: data.name, lvl: data.lvl, path: player.anim.imgPath});
			return false;
		}
	});
	SOS.managementServer.query('enemy-add', {idTarget: id});
}

SOS.Chat.prototype.removeEnemy = function(id){
	$.each(SOS.players.storage, function(i, player){
		if (player.view.obj.data('id') == id){
			player.description.removeClass('enemy');
			return false;
		}
	});
	SOS.enemiesList.remove(id);
  SOS.managementServer.query('enemy-remove', {idTarget: id});
}
