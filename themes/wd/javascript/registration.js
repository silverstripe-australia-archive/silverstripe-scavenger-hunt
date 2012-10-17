;(function($){
		   
	$(document).ready(function(){
		var $el = $("#registerFormBox");
		if ($el.length > 0){
			$messages = $(".message:visible", $el);
			var $btnOpen = $('<p/>').html('<a href="' + $el.attr('id') + '">Register</a>').addClass('actionLink registerActionLink');
			$btnOpen.find("a").click(function(){
				$('#' + $(this).attr('href')).show();
				return false;
			});
			var $btnClose = $('<p/>').html('<a href="' + $el.attr('id') + '">X Close</a>').addClass('actionLink registerActionLink');
			$btnClose.find("a").click(function(){
				if ($messages.length){
					$messages.remove();
				}
				$('#' + $(this).attr('href')).hide();
				return false;
			});
			$el.prepend($btnClose).before($btnOpen).addClass('dialog').find("h2").hide();
			if (!$messages.length){
				$el.hide();
			}
		}
	});
	
})(jQuery);