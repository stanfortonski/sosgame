(function($){
	var htmlBG, isHide = true;

	$.popupBox = function(){
		this.box = $('<div class="popup-box"><div class="content"></div><button type="button" class="close">x</button></div>');
		this.boxClass = 'default';
		this.duration = 400;
		this.dimming = true;
		this.dimmingScale = 0.2;
		this.closeButton = true;
		this.canExit = true;
		this.content = '';

		this.box.hide();
		$('html').append(this.box);
	}

	$.popupBox.prototype.init = null;
	(function(){
		function initCloseButton(){
			if (!this.canExit || !this.closeButton)
				this.box.find('.close').hide();
			else this.box.find('.close').show();
		}

		function makeDim(){
			if (this.dimming){
				$('html').css('cssText', 'background: black !important');
				$('body').css('opacity', this.dimmingScale);
			}
		}

		function hide(e){
			if (this.canExit && !isHide){
				e.preventDefault();
				e.stopPropagation();
				this.hide();
			}
		}

		$.popupBox.prototype.init = function(){
			htmlBG = $('html').css('background-color');

			$(window).on('scroll.popup', function(e){
				window.scrollTo(0, 0);
				e.preventDefault();
			});
			$('body').on('click.popup', hide.bind(this));
			this.box.find('.close').on('click.popup', hide.bind(this));

			initCloseButton.call(this);
			makeDim.call(this);

			this.box.removeClass(this.oldBoxClass).addClass(this.boxClass);
			this.oldBoxClass = this.boxClass;
		}
	})();

	$.popupBox.prototype.deinit = function(){
		$(window).off('.popup');
		$('body').css('opacity', 1);
		$('html').css('background', htmlBG);
	}

	$.popupBox.prototype.show =	function(){
		if (isHide){
			isHide = false;
			this.init();
			this.box.find('.content').html(this.content);
			this.box.fadeIn(this.duration);
		}
		else{
			this.reload();
		}
	}

	$.popupBox.prototype.hide = function(){
		if (!isHide){
			isHide = true;
			this.deinit();
			this.box.fadeOut(this.duration);
		}
	}

	$.popupBox.prototype.reload = function(){
		this.box.find('.content').html(this.content).hide().fadeIn(this.duration/2);
	}

	$.popupBox = new $.popupBox;
})(jQuery);
