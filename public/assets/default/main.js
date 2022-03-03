// jQuery style
function $$(){
	if(this.$) return this.$;

	this.$ = {};
	$.id = function(id, el=false) {
		return (el || document).getElementById(id);
	}
	$.class = function(classname, el=false) {
		return (el || document).getElementsByClassName(classname);
	}
	$.tag = function(name, el=false) {
		return (el || document).getElementsByTagName(name);
	}
	$.els_with_attr = function(tagname, attr_name, attr_value=false, el=false) {
		var els = $.tag(tagname);
		var out = [];
		for (var i = els.length - 1; i >= 0; i--) {
			if(els[i].attributes[attr_name]) {
				if(attr_value === false) out.push(els[i]);
				else if(els[i].attributes[attr_name] == attr_value) out.push(els[i]);
			}
		};
		return out;
	}
	$.on = function(el, eventname, cb){
		el.addEventListener(eventname, cb);
	}
	$.once = function(el, eventname, cb){
		var _cb = function(e){
			el.removeEventListener(eventname, _cb);
			cb(e);
		}
		el.addEventListener(eventname, _cb);
	}
	$.on_focus_blur = function(el, focus_cb, blur_cb){
		$.on(el, 'focus', function(e){
			focus_cb(e);

			$.once(el, 'blur', blur_cb);
		});
	}
	$.each = function(arr, cb){
		for (var i = arr.length - 1; i >= 0; i--) {
			cb(arr[i]);
		};
	}

	return this.$;
}
// End jQuery style




(function($){
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

		$.on(quickpost, 'keydown', function(e){
			quickpost_strlen.innerText = quickpost.value.length + ' / 128';
		});
	}

	textarea_autoexpand_by_id('quickpost-editor');
	textarea_autoexpand_by_id('post-title-editor');
	textarea_autoexpand_by_id('post-body-editor');
	textarea_autoexpand_by_id('comment-body-editor');


	$.each($.els_with_attr('form', 'data-alert'), function(el){
		$.on(el, 'submit', function(e){
			if(!confirm(this.dataset.alert)) e.preventDefault();
		});
	});


	$.each($.class('input-text-toggle-clear'), function(el){
		var current_value = el.value;
		$.on_focus_blur(
			el,
			function(e){ el.value='' },
			function(e){ if(el.value == '') el.value = current_value; }
		);
	});

})($$());
