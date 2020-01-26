SOS.Config = function(config){
  this.soundVolume = config.soundVolume;
  this.effectVolume = config.effectVolume;
  this.soundMute = config.soundMute == 1;
  this.chatMute = config.chatMute == 1;
  this.chatMutePm = config.chatMutePm == 1;
  this.pvpAble = config.pvpAble == 1;
  SOS.interfaces.ExplorerBox.saveAble = this.saveInterface = config.saveInterface == 1;
  this.unsetInvitations = config.unsetInvitations == 1;
}

SOS.Config.prototype.init = function(){
  SOS.backgroundMusic.volume = this.soundVolume/100;
  SOS.battleMusic.volume = this.soundVolume/100;
  SOS.audioEffects.volume = this.effectVolume/100;
}

SOS.Config.prototype.change = function(){
  SOS.managementServer.query('options-set', this);
}

SOS.Config.prototype.changeSoundVolume = function(volume){
  this.soundVolume = volume;
  SOS.backgroundMusic.volume = volume/100;
  SOS.battleMusic.volume = volume/100;
}

SOS.Config.prototype.changeEffectVolume = function(volume){
  this.effectVolume = volume;
  SOS.audioEffects.volume = volume/100;
}
