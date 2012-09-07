$.fn.placeholder = function(){
	return this.each(function(){
		var text_input = $(this);
		var text_input_default = text_input.val();
		text_input.focus(function(){
			var this_input = $(this);
			if(this_input.val() == text_input_default){
				this_input.val('');
			}  
		})
		text_input.blur(function(){
			var this_input = $(this);
			if(this_input.val() == ''){
				this_input.val(text_input_default);
			}  
		})
	})
}