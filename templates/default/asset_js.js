(function(){
	var $ = {};
	$.id = function(id) {
		return document.getElementById(id);
	}
	$.class = function(classname) {
		return document.getElementsByClassName(classname);
	}

	var quickpost_id = $.id('quickpost');
	if($.id('posts') && quickpost_id){
		quickpost_id.addEventListener('keyup', function(e){
			$.id('quickpost-strlen').innerHTML = quickpost_id.value.length + ' / 128';
		});
	}

	var newpost_body_id = $.id('post-body');
	function newpost_body_autoexpand(){
		var line_count = newpost_body_id.value.match(/\n/g) || [];
		newpost_body_id.style.minHeight = line_count.length * 30.5;
	}
	if($.id('new-post') && newpost_body_id){
		newpost_body_autoexpand();
		setInterval(newpost_body_autoexpand, 4000);
	}
})();
