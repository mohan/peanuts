(function(){
	var $ = {};
	$.id = function(id) {
		return document.getElementById(id);
	}
	$.class = function(classname) {
		return document.getElementsByClassName(classname);
	}

	function textarea_autoexpand_by_id(id){
		var el = $.id(id);
		if(el){
			el.style.minHeight = el.scrollHeight;

			setInterval(function(){
				el.style.minHeight = el.scrollHeight;
			}, 4000);
		}
	}


	var quickpost = $.id('quickpost-editor');
	var quickpost_strlen = $.id('quickpost-strlen');
	if(quickpost){
		if(quickpost.innerHTML.length > 0){
			quickpost_strlen.innerText = quickpost.value.length + ' / 128';
		}

		quickpost.addEventListener('keydown', function(e){
			quickpost_strlen.innerText = quickpost.value.length + ' / 128';
		});
	}

	textarea_autoexpand_by_id('quickpost-editor');
	textarea_autoexpand_by_id('post-title-editor');
	textarea_autoexpand_by_id('post-body-editor');
	textarea_autoexpand_by_id('comment-body-editor');
})();
