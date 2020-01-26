SOS.interfaces.Stoper = function(where){
  this.parent = where;
  this.timer = new SOSEngine.Timer;
  this.step = 1000;
}

SOS.interfaces.Stoper.prototype.pause = function(){
  this.timer.clear();
}

SOS.interfaces.Stoper.prototype.resume = function(){
  this.timer.set(this.onTimer.bind(this), this.step);
}

SOS.interfaces.Stoper.prototype.start = function(time, callback){
  this.pause();
  
  this.secondsTotal = time;
  this.secondsActual = time;
  this.callback = callback;

  this.onTimer();
}

SOS.interfaces.Stoper.prototype.onTimer = function(){
  this.calc();
  this.show();

  if (--this.secondsActual <= -1)
    this.callback();
  else this.timer.set(this.onTimer.bind(this), this.step);
}

SOS.interfaces.Stoper.prototype.calc = function(){
  this.hours = Math.floor(this.secondsActual/3600);
  this.minutes = Math.floor(this.secondsActual/60%60);
  this.seconds = Math.floor(this.secondsActual%60);
}

SOS.interfaces.Stoper.prototype.show = function(){
  this.hours = this.hours < 10 ? '0'+this.hours : this.hours;
  this.minutes = this.minutes < 10 ? '0'+this.minutes : this.minutes;
  this.seconds = this.seconds < 10 ? '0'+this.seconds : this.seconds;

  if (Math.floor(this.secondsTotal/3600) > 0)
    this.parent.html(this.hours+':'+this.minutes+':'+this.seconds);
  else this.parent.html(this.minutes+':'+this.seconds);
}
