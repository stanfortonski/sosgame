SOS.tests.push(function(){
  QUnit.module('CharactersNet Test');

  QUnit.test('Empty Generating Test', function(assert){
    let net = new SOS.CharactersNet,
    expectedTable = '<tr><td><div class="borderBox"><figure data-index="2" role="button"></figure></div></td>';
    expectedTable += '<td><div class="borderBox"><figure data-index="1" role="button"></figure></div></td></tr>';
    expectedTable += '<tr><td><div class="borderBox"><figure data-index="4" role="button"></figure></div></td>';
    expectedTable += '<td><div class="borderBox"><figure data-index="3" role="button"></figure></div></td></tr>';
    expectedTable += '<tr><td><div class="borderBox"><figure data-index="6" role="button"></figure></div></td>';
    expectedTable += '<td><div class="borderBox"><figure data-index="5" role="button"></figure></div></td></tr>';

    assert.equal(net.table.html(), expectedTable, 'Generated table is correct');
  });

  QUnit.test('Adding and Getting Figure By Index Test', function(assert){
    let data = {first: {
      id: 1,
      size: 2,
      path: SOS.PATH+'imgs/knight/icon.jpg'
    }, last: {
      id: 3,
      size: 1,
      path: SOS.PATH+'imgs/knight2/icon.jpg'
    }}

    let net = new SOS.CharactersNet;

    net.addIndex(data.first.id, data.first.size, data.first.path);
    net.addIndex(data.last.id, data.last.size, data.last.path);

    let firstFigure = net.get(data.first.id),
    clearPath = firstFigure.css('background-image').replace('url(', '').replace('\"', '').replace('")', '');
    assert.equal(clearPath, data.first.path, 'First image is correct');
    assert.equal(firstFigure.parent().parent().attr('colspan'), data.first.size, 'Size of first figure is correct');

    let lastFigure = net.get(data.last.id);
    clearPath = lastFigure.css('background-image').replace('url(', '').replace('\"', '').replace('")', '');
    assert.equal(clearPath, data.last.path, 'Last image is correct');

    let colspan = lastFigure.parent().parent().attr('colspan') || null;
    assert.equal(colspan, null, 'Size of last figure is correct');
    assert.notEqual(colspan, data.last.size, 'Size of last figure is correct');

    assert.notOk(firstFigure.parent().hasClass('dead'), 'first figure has no class');
    net.setDead(data.first.id);
    assert.ok(firstFigure.parent().hasClass('dead'), 'Dead class was added');

    assert.notOk(lastFigure.parent().hasClass('focus'), 'Last figure has no class');
    net.setFocus(data.last.id);
    assert.ok(lastFigure.parent().hasClass('focus'), 'Focus class was added');
    net.unsetFocus(data.last.id);
    assert.notOk(lastFigure.parent().hasClass('focus'), 'Last figure has no class');
  });
});
