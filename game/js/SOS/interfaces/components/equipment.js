SOS.interfaces.Equipment = function(){
  this.main = new SOS.interfaces.Tabs('eqPage');
  this.confirm = new SOS.interfaces.ConfirmBox;
  this.actualIndex = 1;
  this.init();
}

SOS.interfaces.Equipment.prototype.init = function(){
  this.main.tabs.addClass('unselectable').attr('unselectable', 'on');
}

SOS.interfaces.Equipment.prototype.generateTable = function(){
  this.table = $('<table class="equipment"></table>');
  for (let i = 0; i < 6; ++i){
    let tr = $('<tr></tr>');
    for (let j = 0; j < 6; ++j){
      let td = $('<td data-index="'+this.actualIndex+'"></td>').droppable({
        accept: '.item',
        drop: function(event, ui){
          if ($(this).find('.icon').length == 0){
            ui.draggable.detach();
            $(this).append(ui.draggable);
          }
        }
      });
      tr.append(td);
      ++this.actualIndex;
    }
    this.table.append(tr);
  }
  this.main.addTab(this.main.count+1, this.table);

  let self = this;
  (function(id){
    self.main.getButton(id).droppable({
      accept: '.item',
      drop: function(event, ui){
        if (!$(this).parent().hasClass('ui-state-active')){
          ui.draggable.detach();
          self.addItemAfter((id-1)*36+1, SOS.Item.itemDragged);
        }
      }
    });
  })(self.main.count);
}

SOS.interfaces.Equipment.prototype.addItem = function(index, item){
  let slot = this.get(index);
  if (slot.find('.icon').length > 0){
    slot = this.getFirstFreeSlot();
    if (slot === false) return;
  }
    item.icon.detach();
  slot.append(item.icon);
  }

SOS.interfaces.Equipment.prototype.addItemAfter = function(index, item){
  let slot = this.get(index);
  if (slot.find('.icon').length > 0){
    slot = this.getFirstFreeSlotAfterIndex(index);
    if (slot === false) return;
  }
  item.icon.detach();
  slot.append(item.icon);
}

SOS.interfaces.Equipment.prototype.getFirstFreeSlot = function(){
  let slot = false;
  $.each(this.main.tabs.find('td[data-index]'), function(i, cell){
    if ($(cell).find('.icon').length == 0){
      slot = $(cell);
      return false;
    }
  });
  return slot;
}

SOS.interfaces.Equipment.prototype.getFirstFreeSlotAfterIndex = function(index){
  let slot = false;
  $.each(this.main.tabs.find('td[data-index]'), function(i, cell){
    if ($(cell).data('index') > index){
      if ($(cell).find('.icon').length == 0){
        slot = $(cell);
        return false;
      }
    }
  });
  return slot;
}

SOS.interfaces.Equipment.prototype.removeItem = function(index){
  let icon = this.get(index).find('.icon');
  icon.draggable('destroy');
  icon.remove();
}

SOS.interfaces.Equipment.prototype.get = function(index){
  return this.main.tabs.find('td[data-index="'+index+'"]');
}
