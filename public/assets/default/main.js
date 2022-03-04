// Peanuts
// License: GPL


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
				else if(els[i].attributes[attr_name].value == attr_value) out.push(els[i]);
			}
		};
		return out;
	}
	$.on = function(el, eventnames, cb){
		for (var i = eventnames.length - 1; i >= 0; i--) el.addEventListener(eventnames[i], cb);
	}
	$.off = function(el, eventnames, cb){
		for (var i = eventnames.length - 1; i >= 0; i--) el.removeEventListener(eventnames[i], cb);
	}
	$.once = function(el, eventnames, cb){
		var _cb = function(e){
			$.off(el, eventnames, cb);
			cb(e);
		}
		$.on(el, eventnames, _cb);
	}
	$.on_focus_blur = function(el, focus_cb, blur_cb){
		$.on(el, ['focus'], function(e){
			focus_cb(e);

			$.once(el, ['blur'], blur_cb);
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

			$.on(el, ['blur', 'focus'], function(e){
				el.style.minHeight = el.scrollHeight;
			});

			setInterval(function(){
				el.style.minHeight = el.scrollHeight;
			}, 4000);
		}
	}


	var quickpost = $.id('quickpost-editor');
	var quickpost_strlen = $.id('quickpost-strlen');
	if(quickpost){
		quickpost_strlen.innerText = quickpost.value.length;

		$.on(quickpost, ['keyup'], function(e){
			quickpost_strlen.innerText = quickpost.value.length;
		});
	}

	textarea_autoexpand_by_id('quickpost-editor');
	textarea_autoexpand_by_id('post-title-editor');
	textarea_autoexpand_by_id('post-body-editor');
	textarea_autoexpand_by_id('comment-body-editor');


	$.each($.els_with_attr('form', 'data-alert'), function(el){
		$.on(el, ['submit'], function(e){
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


	var form_post_find = $.id('form-post-find');
	if(form_post_find){
		$.on(form_post_find, ['submit'], function(e){
			var input = $.els_with_attr('input', 'placeholder', 'Find by post #id')[0];
			input.value = input.value.match(/\d+/)[0];
		});
	}

})($$());
