SOS.tests.push(function(){
  QUnit.module('Character Object Test');

  QUnit.test('Constructor Test', function(assert){
    let expectedData = {
      id: 999, name: 'Stefan',
      lvl: 25, path: SOS.PATH+'imgs/outfits/dragon/',
      posX: 25, posY: 10
    },
    character = new SOS.Character(expectedData),
    generatedData = character.view.obj.data();

    assert.equal(expectedData.path, character.anim.imgPath, 'Character image path is correct');
    assert.equal(character.view.position.x, expectedData.posX, 'Character position x-axis is correct');
    assert.equal(character.view.position.y, expectedData.posY, 'Character position y-axis is correct');
    assert.equal(generatedData.id, expectedData.id, 'Character ID is correct');
    assert.equal(generatedData.lvl, expectedData.lvl, 'Character LVL is correct');
    assert.equal(generatedData.name, expectedData.name, 'Character name is correct');
  });
});

SOS.tests.push(function(){
  QUnit.module('Player Object Test');

  QUnit.test('Constructor Test', function(assert){
    let expectedData = {
      id: 999, name: 'Stefan',
      lvl: 25, path: SOS.PATH+'imgs/outfits/dragon/',
      posX: 25, posY: 10
    },
    player = new SOS.Player(expectedData);

    assert.equal(
      player.description.html(),
      expectedData.name+' '+expectedData.lvl+' LVL',
      'Player description is correct'
    );
  });
});

SOS.tests.push(function(){
  QUnit.module('Mob Object Test');

  QUnit.test('Constructor Test', function(assert){
    let expectedData = {
      id: 999, name: 'Adam',
      lvl: 25, path: SOS.PATH+'imgs/outfits/dragon/',
      posX: 25, posY: 10,
      relations: 'war'
    },
    mob = new SOS.Mob(expectedData);

    assert.equal(mob.view.obj.data('relations'), expectedData.relations, 'Player relations are correct');
    assert.equal(
      mob.view.obj.data('title'),
      expectedData.name+' '+expectedData.lvl+' LVL',
      'Player relations are correct'
    );
  });
});
