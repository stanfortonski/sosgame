SOS.tests.push(function(){
  QUnit.module('Stoper Test');

  var stoper = new SOS.interfaces.Stoper($('#SOSEngine'));

  QUnit.test('Starting Test', function(assert){
    let seconds = 100;
    stoper.start(seconds);
    assert.equal(stoper.secondsTotal, seconds);
    assert.equal(stoper.hours, 0);
    assert.equal(stoper.minutes, 1);
    assert.equal(stoper.seconds, 40);
  });

  QUnit.test('Waiting Test', function(assert){
    let done = assert.async();
    setTimeout(function(){
      assert.equal(stoper.seconds, 39);
      done();
    }, 1100);
  });

  QUnit.test('Pause Test', function(assert){
    stoper.pause();
    let done = assert.async();
    setTimeout(function(){
      assert.equal(stoper.seconds, 39);
      done();
    }, 1100);
  });

  QUnit.test('Resume Test', function(assert){
    stoper.resume();
    let done = assert.async();
    setTimeout(function(){
      assert.equal(stoper.seconds, 38);
      stoper.pause();
      done();
    }, 1100);
  });
});
