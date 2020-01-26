(function(){
	var button = $('#edit-player'),
		href = button.attr('href');
		
	function getGeneralThumbnail(){
		let select = $('#choose-general select'),
			thumbnail = $('#generalThumbnail'),
			generalId = JSON.parse(select.val()).general;
			button.attr('href', href+'?id='+generalId);

		$.get('ajax/getGeneralThumbnail.php', {'general-id': generalId}, function(response){
			if (response.length != ""){
				thumbnail.css('opacity', 0).html(response).delay(100).animate({'opacity': 1}, 400);
			}
		});
	}

	getGeneralThumbnail();
	$(document).on('change', '#choose-general select', getGeneralThumbnail);
})();
