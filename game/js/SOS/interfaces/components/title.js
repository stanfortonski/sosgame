SOS.interfaces.Title = {
	init: function(){},
	add: function(){},
	remove: function(){},
	getCursorPosition: function(){},
};

(function(){
	if (!SOS.isMobile){
		var followed =  $('<div id="followed"></div>').appendTo($('body')), elements = $('[data-title]');

		SOS.interfaces.Title.init = function(){
			elements.off('.followed');
			elements = $('[data-title]');

			elements.on('mouseover.followed', function(e){
				$(document).on('mousemove', SOS.interfaces.Title.getCursorPosition);

				let title = $(this).data('title');
				followed.html(title).show();
				e.stopPropagation();
			});

			elements.on('mouseleave.followed', function(){
				followed.hide();
				$(document).off('mousemove');
			});
		}

		SOS.interfaces.Title.add = function(jqObj, text){
			if (jqObj.data('title') === undefined)
				jqObj.attr('data-title', text).data('title', text);
			else jqObj.data('title', text);

			this.init();
		}

		SOS.interfaces.Title.remove = function(jqObj){
			this.init();
			jqObj.removeData('title');
		}
	}
})();
