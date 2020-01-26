(function(){
	let message = Cookies.get('message');
	if (message !== undefined){
		$.popupBox.content = decodeURIComponent(message).replace(/\+/g, ' ');
		$.popupBox.show();
	}
	Cookies.remove('message');
	Cookies.remove('message', {domain: '.sosgame.online'});
})();
