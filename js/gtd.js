;(function($) {
	
	//Disable form fields with the gtd-disabled class
	$(function(){
		$('.gform_wrapper .gtd-disabled input').attr('readonly', 'readonly');
	});
	
})(jQuery);