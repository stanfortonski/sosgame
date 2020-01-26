SOS.interfaces.List = function(){
  this.obj = $('<ul class="adjustList"></ul>');
  this.amount = 1;
}

SOS.interfaces.List.prototype.add = function(content){
  this.obj.append('<li data-index="'+this.amount+'">'+content+'</li>');
  ++this.amount;
}

SOS.interfaces.List.prototype.get = function(index){
  return this.obj.find('[data-index="'+index+'"]');
}

SOS.interfaces.List.prototype.remove = function(index){
  this.get(index).remove();
}

SOS.interfaces.List.prototype.removeAll = function(){
  this.obj.children().remove();
}

SOS.interfaces.OrderedList = function(){
  this.obj = $('<ol class="adjustList"></ol>');
  this.amount = 1;
}
SOS.interfaces.OrderedList.prototype = SOS.interfaces.List.prototype;

SOS.interfaces.ListOfPlayers = function(){
  SOS.interfaces.OrderedList.call(this);
  this.obj.addClass('listOfPlayers');
}
SOS.interfaces.ListOfPlayers.prototype = Object.create(SOS.interfaces.OrderedList.prototype);

SOS.interfaces.ListOfPlayers.prototype.add = function(data){
  SOS.interfaces.OrderedList.prototype.add.call(this, '<span class="name">'+data.name+'</span> '+data.lvl+' LVL');
  let li = SOS.interfaces.OrderedList.prototype.get.call(this, this.amount-1).attr('data-id', data.id).attr('role', 'button');
  this.addClickEvent(li);
}

SOS.interfaces.ListOfPlayers.prototype.addClickEvent = function(obj){
  obj.click(function(e){
    if (SOS.enemiesList.get(obj.data('id')).length > 0)
      SOS.enemiesList.setContext.call(obj, e);
    else if (SOS.friendStack.get(obj.data('id')).length > 0)
      SOS.friendStack.setContext.call(obj, e);
    else this.setContext.call(obj, e);

    e.stopPropagation();
  }.bind(this));
}

SOS.interfaces.ListOfPlayers.prototype.setContext = function(e){
  SOS.ContextMenu.setList(e, $(this), [{
    name: 'Rozmawiaj',
    click: SOS.Chat.changeListener.bind(SOS.Chat, $(this).find('.name').text())
  },{
    name: 'Dodaj do znajomych',
    click: SOS.Chat.offerFriend.bind(SOS.Chat, $(this).data('id'))
  },{
    name: 'Zablokuj',
    click: SOS.Chat.addEnemy.bind(SOS.Chat, $(this).data('id'))
  }]);
}

SOS.interfaces.ListOfPlayers.prototype.get = function(id){
  return this.obj.find('[data-id="'+id+'"]');
}

SOS.interfaces.ListOfPlayers.prototype.findByName = function(name){
  var result = -1, regexp = new RegExp('^'+name+'$', "i");
  this.obj.find('[class="name"]').each(function(i){
    result = $(this).text().search(regexp);
    if (result != -1) return result;
  });
  return result;
}

SOS.interfaces.ListOfPlayers.prototype.remove = function(id){
  this.get(id).remove();
}

SOS.interfaces.ListOfEnemies = function(){
  SOS.interfaces.ListOfPlayers.call(this);
}
SOS.interfaces.ListOfEnemies.prototype = Object.create(SOS.interfaces.ListOfPlayers.prototype);

SOS.interfaces.ListOfEnemies.prototype.addClickEvent = function(obj){
  obj.click(function(e){
    this.setContext.call(obj, e);
    e.stopPropagation();
  }.bind(this));
}

SOS.interfaces.ListOfEnemies.prototype.setContext = function(e){
  SOS.ContextMenu.setList(e, $(this), [{
    name: 'Odblokuj',
    click: SOS.Chat.removeEnemy.bind(SOS.Chat, $(this).data('id'))
  }]);
}
