SOS.interfaces.FriendStack = function(){
  this.obj = $('<div class="friendStack"></div>');
}

SOS.interfaces.FriendStack.prototype.get = function(id){
  return this.obj.find('[data-id="'+id+'"]');
}

SOS.interfaces.FriendStack.prototype.remove = function(id){
  this.get(id).remove();
}

SOS.interfaces.FriendStack.prototype.removeAll = function(){
  this.obj.children().remove();
}

SOS.interfaces.FriendStack.prototype.add = function(data){
  let content = this.getIcon(data.online)+' <span class="name">'+data.name+'</span> '+data.lvl+' LVL',
    figure = $('<figure role="button" data-id="'+data.id+'"><div class="icon"></div><figcaption>'+content+'</figcaption></figure>');

  figure.find('.icon:first').css('background-image', 'url('+data.path+'icon.jpg)');
  this.obj.append(figure);
  SOS.interfaces.Title.add(figure, data.name+' '+data.lvl+' LVL');
  figure.click(function(e){
    this.setContext.call(figure, e);
    e.stopPropagation();
  }.bind(this));
}

SOS.interfaces.FriendStack.prototype.setContext = function(e){
  SOS.ContextMenu.setList(e, $(this), [{
    name: 'Rozmawiaj',
    click: SOS.Chat.changeListener.bind(SOS.Chat, $(this).find('.name').text())
  },{
    name: 'Profil',
    click: function(){
      console.log('PROFIL!');
  }},{
    name: 'Usuń ze znajomych',
    click: SOS.Chat.removeFriend.bind(SOS.Chat, $(this).data('id'))
  }]);
}

SOS.interfaces.FriendStack.prototype.getIcon = function(status){
  if (status === undefined)
    return '<span class="icon icon-offline"></span>';
  return '<span class="icon icon-online"></span>';
}

SOS.interfaces.FriendStack.prototype.changeStatus = function(id, status){
  let icon = this.get(id).find('figcaption > .icon');
  if (icon.length > 0){
    if (status === undefined)
      icon.removeClass('icon-online').addClass('icon-offline');
    else icon.removeClass('icon-offline').addClass('icon-online');
  }
}
