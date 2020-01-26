SOS.Audio = function(rootFolder, playlist){
	var audio = new Audio;
	audio.obj = $(audio);
	audio.rootFolder = rootFolder || '';
	audio.playList = playlist || [];
	audio.current = -1;
	audio.canPlay = false;

	audio.setSrc = function(src){
		audio.obj.attr('src', audio.rootFolder+'/'+src);
	}

	audio.getSrc = function(){
		return audio.obj.attr('src').replace(audio.rootFolder+'/', '');
	}

	audio.play = function(isContinue){
		if (isContinue === undefined)
		 isContinue = true;

		if (audio.current == -1){
			audio.obj.on('ended', function(){
				audio.currentTime = 0;
				playRandom();
			});
			playRandom();
		}
		else if (typeof isContinue === 'string'){
			audio.setSrc(isContinue);
			playStrict();
		}
		else if (isContinue)
			playStrict();
		else playRandom();
	}

	var playRandom = function(){
		var old = audio.current;
		do {
			audio.current = Math.floor(Math.random() * (audio.playList.length));
		} while (old == audio.current);

		audio.setSrc(audio.playList[audio.current]);
		playStrict();
	}

	var playStrict = function(){
		if (SOS.Audio.canPlay){
			let promise = Audio.prototype.play.call(audio);
			if (promise){
    		promise.catch(function(error){console.error(error);});
			}
		}
	}

	return audio;
}
SOS.Audio.canPlay = true;
