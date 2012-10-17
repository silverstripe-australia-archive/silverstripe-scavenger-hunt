;(function($){
		   
	$(document).ready(function(){
		var reglog = $('#reglog')
			logreg = $('#logreg').hide(),
			log = $('#login-form').hide(),
			reg = $('#register-form');

		reglog.click(function(){
			log.show();
			reg.hide();
			reglog.hide();
			logreg.show();
		});

		logreg.click(function(){
			log.hide();
			reg.show();
			reglog.show();
			logreg.hide();
		});

	});
	
})(jQuery);