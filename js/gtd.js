;(function($) {
	
	$(function(){
		//Disable form fields with the gtd-disabled class
		$('.gform_wrapper .gtd-disabled input').attr('readonly', 'readonly');
		
		$('a.gtd-remove-image').click(function(){
			var $this = $(this);
			var $parent = $this.parents('.gfield:first');
			
			$parent.find('.ginput_preview').hide();
			$parent.find('input').removeClass('gform_hidden');
			
			$parent.append($('<input name="gtd_delete_image" type="hidden" value="1" />'));
			return false;
		});
	});
	
})(jQuery);