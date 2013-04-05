$(document).ready(function() {
						   
			var hash = window.location.hash.substr(1);
		var href = $('.sidebarPageSelector').each(function(){
			var href = $(this).attr('href');
			if(hash==href.substr(0,href.length-4)){
				var toLoad = hash+'.php #content';
				$('#content').load(toLoad)
			}											
		});

});