SOS.events.onDie = function(e){
  let diedInformation = $('#diedInformation');
  if (diedInformation.is(':hidden')){
    SOS.events.offMovement();
    SOS.view.Camera.effects.sleep(1);
    SOS.BattleInterface.hide();
    SOS.backgroundMusic.pause();
    SOS.audioEffects.play('No Hope.mp3');
    SOS.point.obj.hide();

    diedInformation.fadeIn();
    let stoper = new SOS.interfaces.Stoper(diedInformation.find('.time'));
    stoper.start(e.time, function(){
      SOS.synchro.query('respawn');
    });
  }
}
