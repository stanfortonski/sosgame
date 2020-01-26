(function(){
	$('#delete-account').click(function(e){
		$.popupBox.content = 'Proszę czekać...';
		$.popupBox.canExit = false;
		$.popupBox.show();
	});
})();
