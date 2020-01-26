SOS.tests.push(function(){
  QUnit.module('Item Object Test');

  var expectedData = {
    id: 2, name: 'Miecz',
    path: SOS.PATH+'imgs/items/axe.png',
    posX: 55, posY: 50,
    amount: 2
  },
  item = new SOS.Item(expectedData);

  QUnit.test('Constructor Test', function(assert){
    iconData = item.icon.data();

    assert.equal(item.view.position.x, expectedData.posX, 'Item position x-axis is correct');
    assert.equal(item.view.position.y, expectedData.posY, 'Item position y-axis is correct');

    assert.equal(iconData.id, expectedData.id, 'Item ID is correct');
    //TODO assert.equal(iconData.name, expectedData.name, 'Item name is correct');
    assert.equal(item.icon.find('.amount').html(), expectedData.amount, 'Item amount is correct');

    let clearPath = item.icon.css('background-image').replace('url(', '').replace('\"', '').replace('")', '');
    assert.equal(clearPath, expectedData.path, 'Icon path is correct');
  });

  QUnit.test('Change Amount Test', function(assert){
    item.changeAmount(5);
    assert.equal(item.icon.find('.amount').html(), 5, 'Item amount is correct');
  });
});
