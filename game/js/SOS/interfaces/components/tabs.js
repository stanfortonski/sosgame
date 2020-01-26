SOS.interfaces.Tabs = function(name){
  this.tabName = name;
  this.tabs = $('<div class="tabs"><ul></ul></div>').tabs().removeClass('ui-widget-content ui-widget');
  this.buttons = this.tabs.find('ul');
  this.count = 0;

  let scroll = new PerfectScrollbar(this.buttons[0],{
    handlers: ['click-rail', 'drag-thumb', 'wheel', 'touch']
  });
}

SOS.interfaces.Tabs.prototype.addTab = function(buttonText, content){
  ++this.count;
  let id = this.tabName+'-'+this.count;
  this.buttons.append('<li><a href="#'+id+'">'+buttonText+'</a></li>');

  let div = $('<div id="'+id+'"></div>');
  div.append(content);
  this.tabs.append(div);
}

SOS.interfaces.Tabs.prototype.prepareAndGet = function(){
  return this.tabs.tabs('refresh').tabs({active: 0});
}

SOS.interfaces.Tabs.prototype.getButton = function(id){
  return this.buttons.find('a[href="#'+this.tabName+'-'+id+'"]');
}

SOS.interfaces.Tabs.prototype.getTab = function(id){
  return this.tabs.find('#'+this.tabName+'-'+id);
}
