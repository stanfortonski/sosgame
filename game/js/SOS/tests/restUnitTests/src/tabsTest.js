SOS.tests.push(function(){
  QUnit.module('Tabs Test');

  QUnit.test('Complex Tabs Test', function(assert){
    let name = 'alfredo',
      contents = ['test1', 'test2'],
      buttons = ['OK', 'NEXT'],
      tabs = new SOS.interfaces.Tabs(name);

      tabs.addTab(buttons[0], contents[0]);
      tabs.addTab(buttons[1], contents[1]);

      let firstTab = tabs.getTab(1);
      assert.equal(firstTab.attr('id'), name+'-'+1, 'First tab ID is correct');
      assert.equal(firstTab.html(), contents[0], 'First tab content is correct');
      assert.equal(tabs.getButton(1).html(), buttons[0], 'First button value is correct');

      let lastTab = tabs.getTab(2);
      assert.equal(lastTab.attr('id'), name+'-'+2, 'Last tab ID is correct');
      assert.equal(lastTab.html(), contents[1], 'Last tab content is correct');
      assert.equal(tabs.getButton(2).html(), buttons[1], 'Last button value is correct');
  });
});
