SOS.tests.push(function(){
  QUnit.module('Chat Test');

  QUnit.test('Changing Listener Test', function(assert){
    let name = 'Franek';

    assert.ok(SOS.Chat.canSendToPlayerOfName(name), 'Message can be send');

    SOS.Chat.changeListener(name);
    assert.equal(SOS.Chat.input.val(), '@'+name+';', 'Listener was changed');

    name = 'WielkiBobo';
    SOS.Chat.changeListener(name);
    assert.equal(SOS.Chat.input.val(), '@'+name+';', 'Listener was changed');

    name = 'Hitler';
    let message = 'Special message for you';
    SOS.Chat.input.val(message);
    SOS.Chat.changeListener(name);
    assert.equal(SOS.Chat.input.val(), '@'+name+';'+message, 'Listener was changed, message is still available');

    SOS.Chat.reload();
    assert.equal(SOS.Chat.input.val(), '', 'Listener was removed');

    SOS.Chat.name = name;
    SOS.Chat.reload();
    assert.equal(SOS.Chat.input.val(), '@'+SOS.Chat.name+';', 'Listener was changed');

    SOS.name = null;
    SOS.Chat.reload();
  });

  QUnit.test('Changing Into Links Test', function(assert){
    let message = 'Content XYZ', link = 'http://play.sosgame.online/';

    let changed = SOS.Chat.changeToLinks(link);
    assert.notEqual(link, changed, 'Link was changed');
    assert.equal('<span class="link" data-href="'+link+'" data-title="'+link+'">LINK</span>', changed, 'Link is correct');

    changed = SOS.Chat.changeToLinks(link+' '+message);
    assert.equal('<span class="link" data-href="'+link+'" data-title="'+link+'">LINK</span> '+message, changed, 'Link is correct, message is still available');

    changed = SOS.Chat.changeToLinks(message+' '+link);
    assert.equal(message+' <span class="link" data-href="'+link+'" data-title="'+link+'">LINK</span>', changed, 'Link is correct, message is still available');
  });

  QUnit.test('Sending Message', function(assert){
    let normalMessage = 'Content: Lorem Ipsum',
      name = 'franek', pmMessage = 'Something Message';

    let data = SOS.Chat.prepareMessageData(normalMessage);
    assert.equal(data.name, undefined, 'This message is not private');
    assert.equal(data.content, normalMessage, 'Message is correct');

    data = SOS.Chat.prepareMessageData('@'+name+';'+pmMessage);
    assert.equal(data.name, name, 'This message is private');
    assert.equal(data.content, pmMessage, 'Message is correct');
  });
});
