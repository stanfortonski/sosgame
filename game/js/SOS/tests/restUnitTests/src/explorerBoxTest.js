SOS.tests.push(function(){
  QUnit.module('ExplorerBox Test');

  QUnit.test('Content Test', function(assert){
    let header = 'headerContent',
      content = 'Lorem Ipsum',
      footer = 'footerContent';

    let explorerBox = new SOS.interfaces.ExplorerBox;

    explorerBox.setContent(header, content, footer);
    assert.equal(explorerBox.header.find('.name').html(), header, 'Header content is correct');
    assert.equal(explorerBox.content.html(), content, 'Main content is correct');
    assert.equal(explorerBox.footer.html(), footer, 'Footer content is correct');

    explorerBox.setContent();
    assert.equal(explorerBox.header.find('.name').html(), header, 'Header content is correct');
    assert.equal(explorerBox.content.html(), content, 'Main content is correct');
    assert.equal(explorerBox.footer.html(), footer, 'Footer content is correct');

    explorerBox.setContent(null, null, 'Testo');
    assert.equal(explorerBox.header.find('.name').html(), header, 'Header content is correct');
    assert.equal(explorerBox.content.html(), content, 'Main content is correct');
    assert.notEqual(explorerBox.footer.html(), footer, 'Footer content is correct');

    explorerBox.setContent('Karaczan');
    assert.notEqual(explorerBox.header.find('.name').html(), header, 'Header content is correct');
    assert.equal(explorerBox.content.html(), content, 'Main content is correct');
    assert.equal(explorerBox.footer.html(), 'Testo', 'Footer content is correct');

    explorerBox.setContent(null, 'Bombel');
    assert.equal(explorerBox.header.find('.name').html(), 'Karaczan', 'Header content is correct');
    assert.equal(explorerBox.content.html(), 'Bombel', 'Main content is correct');
    assert.equal(explorerBox.footer.html(), 'Testo', 'Footer content is correct');
  });

  QUnit.test('Size Test', function(assert){
    let explorerBox = new SOS.interfaces.ExplorerBox;

    explorerBox.setSize(300, 400);
    assert.equal(explorerBox.obj.width(), 300, 'Width is correct');
    assert.equal(explorerBox.obj.height(), 400, 'Height is correct');

    explorerBox.setSize(500);
    assert.equal(explorerBox.obj.width(), 500, 'Width is correct');
    assert.equal(explorerBox.obj.height(), 400, 'Height is correct');

    explorerBox.setSize(null, 800);
    assert.equal(explorerBox.obj.width(), 500, 'Width is correct');
    assert.equal(explorerBox.obj.height(), 800, 'Height is correct');
  });

  QUnit.test('Position Test', function(assert){
    let explorerBox = new SOS.interfaces.ExplorerBox;
    explorerBox.obj.show();
    explorerBox.setPosition(100, 120);

    function between(value, compareTo, maxMistakeValue){
      return value - maxMistakeValue <= compareTo && value + maxMistakeValue >= compareTo;
    }
    function betweenLeft(value, compareTo){return between(value, compareTo, 8);}
    function betweenTop(value, compareTo){return between(value, compareTo, 25);}

    assert.ok(betweenLeft(explorerBox.obj.position().left, 100), 'Left pos is correct');
    assert.ok(betweenTop(explorerBox.obj.position().top, 120), 'Top pos is correct');

    explorerBox.setPosition(200);
    assert.ok(betweenLeft(explorerBox.obj.position().left, 200), 'Left pos is correct');
    assert.ok(betweenTop(explorerBox.obj.position().top, 120), 'Top pos is correct');

    explorerBox.setPosition(null, 500);
    assert.ok(betweenLeft(explorerBox.obj.position().left, 200), 'Left pos is correct');
    assert.ok(betweenTop(explorerBox.obj.position().top, 500), 'Top pos is correct');

    explorerBox.obj.hide();
  });
});

SOS.tests.push(function(){
  QUnit.module('ConfirmBox Test');

  QUnit.test('Buttons Management Test', function(assert){
    let name = 'SOMETHING',
      confirmBox = new SOS.interfaces.ConfirmBox;

    confirmBox.changeButton(name, 'CONTENT');
    assert.equal(confirmBox.buttonList.find('.'+name).length, 0 , 'No Changes');

    confirmBox.addButton(name, 'CONTENT2');
    let button = confirmBox.buttonList.find('.'+name);
    assert.equal(button.length, 1, 'Button was added');
    assert.equal(button.html(), 'CONTENT2', 'Content is correct');

    confirmBox.removeButton(name);
    assert.equal(confirmBox.buttonList.find('.'+name).length, 0 , 'Button was removed');
  });
});

SOS.tests.push(function(){
  QUnit.module('NotificationBox Test');

  QUnit.test('Adding Notify Test', function(assert){
    let done = assert.async(),
    time = 500,  message = 'Message',
    notifications = new SOS.interfaces.NotificationBox;

    notifications.addNotify(message, time);
    assert.equal(notifications.obj.children().length, 2, 'Notify was added');
    assert.equal(notifications.obj.children().eq(0).html(), message, 'Content of notify is correct');

    setTimeout(function(){
      assert.equal(notifications.obj.children().length, 1, 'Notify was removed');
      done();
    }, time+200);
  });
});
