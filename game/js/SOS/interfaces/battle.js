SOS.interfaces.Battle = function(){
  this.obj = this.obj = $('#battleInterface');
  this.init();
}

SOS.interfaces.Battle.prototype.init = function(){
  SOS.enemiesNet.addIndex(2, 4, 'imgs/outfits/knight/icon.jpg');
  SOS.enemiesNet.addIndex(6, 2, 'imgs/outfits/knight2/icon.jpg');
  SOS.enemiesNet.setFocus();
  SOS.enemiesNet.setDead(6);
  this.obj.find('.rightMenu .teamPlace').append(SOS.enemiesNet.table);
}

SOS.interfaces.Battle.prototype.show = function(){
  if (this.obj.is(':hidden')){
    if (SOS.ContextMenu.obj.is(':visible'))
      SOS.ContextMenu.toggle();

    SOS.teamNet.table.detach();
    this.obj.find('.leftMenu .teamPlace').append(SOS.teamNet.table);
    SOS.teamNet.unsetFocus();

    SOS.events.offInterface();
    SOS.events.offMovement();
    SOS.backgroundMusic.pause();

    SOS.events.adjustInterfaceSize(null, $('#battleWorld'));
    this.obj.show();
  	SOS.MainInterface.obj.hide('explode', 800, function(){
      SOS.battleMusic.play(false);
    });
  }
}

SOS.interfaces.Battle.prototype.hide = function(){
  if (this.obj.is(':visible')){
    SOS.teamNet.table.detach();
    SOS.teamNet.setFocus();
    SOS.MainInterface.bookmarks.team.content.find('.teamPlace').append(SOS.teamNet.table);

    SOS.events.onInterface();
    SOS.events.onMovement();
    SOS.battleMusic.pause();

    this.obj.hide('explode', 800, function(){
      SOS.backgroundMusic.play(false);
      SOS.events.adjustInterfaceSize(null, $('#SOSEngine'));
    });
    SOS.MainInterface.obj.show();
  }
}
