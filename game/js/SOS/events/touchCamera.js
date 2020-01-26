(function(){
  if (SOS.isMobile){
    var objectStart = new SOSEngine.Object(1, 1),
      objectEnd = new SOSEngine.Object(1, 1),
      viewPoint = new SOSEngine.Object(1, 1);

    SOS.events.touchStart = function(e){
      let pos = SOS.events.targetOnScene(e.originalEvent.touches[0]);
      objectStart.setPosition(pos.x, pos.y);
      viewPoint.setPosition(objectStart.position.x, objectStart.position.y);
    }

    SOS.events.touchMove = function(e){
      let pos = this.targetOnScene(e.originalEvent.touches[0]);
      objectEnd.setPosition(pos.x, pos.y);

      pos = objectStart.calcAndGetDistanceBetween(objectEnd);
      viewPoint.setPosition(viewPoint.position.x - pos.x, viewPoint.position.y - pos.y);

      let safeDistance = SOS.me.view.calcAndGetDistanceBetween(viewPoint);
      if (Math.abs(safeDistance.x) < this.maxCameraDistance && Math.abs(safeDistance.y) < this.maxCameraDistance)
        SOS.view.Camera.lookAt(viewPoint);
    }.bind(SOS.events);
  }
})();

SOS.events.onTouchCamera = function(){
  if (SOS.isMobile)
    $('#SOSEngine > .window > .scene, .introduce').on('touchstart.touchCamera', SOS.events.touchStart).on('touchmove.touchCamera', SOS.events.touchMove);
}

SOS.events.offTouchCamera = function(){
  $('#SOSEngine > .window > .scene, .introduce').off('.touchCamera');
}
