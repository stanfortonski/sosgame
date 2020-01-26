SOS.managementServer.onopen = function(){}
SOS.managementServer.onerror = function(){}

SOS.managementServer.onclose = function(){
	if (SOS.canClose)
		document.location.href = 'index';
}

SOS.managementServer.onmessage = function(msg){
	let query = JSON.parse(msg.data);
	console.log(msg.data);

	if (query.name === undefined) return;
	switch (query.name){
		case 'options-get':{
			SOS.Config = new SOS.Config(query.value);
		}
		break;

		case 'team': {
			$.each(query.value, function(i, value){
				SOS.teamNet.addIndex(value.index, value.size, value.path+'icon.jpg');
				let obj = SOS.teamNet.get(value.index),
					title = value.name+' '+value.lvl+' LVL';
				SOS.interfaces.Title.add(obj, title);

				obj.click(function(){
					if (SOS.MainInterface.boxes.heroStats.id != value.id){
						SOS.managementServer.query('stats-get', {id_stats: value.id_stats});
						SOS.MainInterface.boxes.heroStats.id = value.id;
						SOS.MainInterface.boxes.heroStats.setContent('Statystyki: '+title);
						SOS.MainInterface.boxes.heroEquipment.setContent('Rynsztunek: '+title);
						$('.heroStats .icon').attr('src', $(this).css('background-image').replace('url("', '').replace('")', ''));
					}
					else SOS.MainInterface.groups.hero.toggle();
				});
			});
			SOS.teamNet.setFocus();
		}
		break;

		case 'stats-get':{
			let content = $('.heroStats');
			$.each(query.value, function(key, value){
				content.find('.'+key).text(value);
			});
			SOS.MainInterface.boxes.heroStats.setContent(null, content);
			SOS.MainInterface.groups.hero.open();
		}
		break;
	}
}

SOS.managementServer.query = SOS.managementServer.extQuery;
