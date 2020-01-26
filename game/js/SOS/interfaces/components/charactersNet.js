SOS.CharactersNet = function(){
  this.table = $('<table class="charactersNet"></table>');
  this.bars = [];
  this.generateEmpty();
}

SOS.CharactersNet.prototype.generateEmpty = function(){
  this.table.remove('tr');
  for (let row = 1; row <= 3; ++row){
    let index = row * 2,
    tr = $('<tr></tr>');

    tr.append('<td><div class="borderBox"><figure data-index="'+index+'" role="button"></figure></div></td>');
    tr.append('<td><div class="borderBox"><figure data-index="'+(index-1)+'" role="button"></figure></div></td>');
    this.table.append(tr);
  }
}

SOS.CharactersNet.prototype.addIndex = {};
(function(){
  SOS.CharactersNet.prototype.addIndex = function(index, size, src){
    let figure = this.get(index),
      box = figure.parent(),
      td = box.parent();

    box.append(addBar.call(this, index));
    figure.css('background-image', 'url('+src+')');

    if (size >= 2){
      td.attr('colspan', 2);
      if (td.next().length > 0)
        td.next().remove();
      else if (td.prev().length > 0)
        td.prev().remove();
    }

    if (size == 4){
      td.attr('rowspan', 2);
      td.parent().next().children().each(function(){
        $(this).remove();
      });
    }
  }

  function addBar(index){
    let bar = new SOS.interfaces.Bar;
    bar.main.removeClass('bar-position').addClass('hpbar');
    bar.update(1, 0, true);
    this.bars[index] = bar;
    return bar.main;
  }
})();

SOS.CharactersNet.prototype.setDead = function(index){
  this.get(index).parent().removeClass('focus').addClass('dead').off('click.battle');
}

SOS.CharactersNet.prototype.setFocus = function(){
  this.table.find('figure[style]').parent().not('.dead').addClass('focus');
}

SOS.CharactersNet.prototype.unsetFocus = function(){
  this.table.find('figure').parent().removeClass('focus');
}

SOS.CharactersNet.prototype.get = function(index){
  return this.table.find('figure[data-index="'+index+'"]');
}
