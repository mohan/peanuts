(function(){
	var $ = {};
	$.id = function(id) {
		return document.getElementById(id);
	}
	$.class = function(classname) {
		return document.getElementsByClassName(classname);
	}

	function textarea_autoexpand(el){
		var line_count = el.value.match(/\n/g) || [];
		el.style.minHeight = line_count.length * 32;
	}
	function textarea_autoexpand_by_id(id){
		var el = $.id(id);
		if(el){
			textarea_autoexpand(el);
			setInterval(function(){textarea_autoexpand(el)}, 4000);
		}
	}


	var quickpost_id = $.id('quickpost-editor');
	if(quickpost_id){
		quickpost_id.addEventListener('keyup', function(e){
			$.id('quickpost-strlen').innerHTML = quickpost_id.value.length + ' / 128';
		});
	}

	textarea_autoexpand_by_id('post-body-editor');
	textarea_autoexpand_by_id('comment-body-editor');
})();
