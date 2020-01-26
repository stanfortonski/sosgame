SOS.Battle = function(){
  this.team = [];
  this.enemies = [];
}

SOS.Battle.prototype.startBattle = function(team, enemies){
  this.team = team;
  this.enemies = enemies;
  SOS.BattleInterface.show();
}

SOS.Battle.prototype.endBattle = function(){
  this.team = [];
  this.enemies = [];
  SOS.BattleInterface.hide();
}
