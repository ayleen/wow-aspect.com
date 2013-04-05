<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Web Quest</title>
		<link rel="stylesheet" type="text/css" href="luizka.css">
		<script type="text/javascript" src="jquery-1.7.2.js"></script>		
		<script type="text/javascript" src="jquery-ui-1.8.18.custom.min.js"></script>		
	<body>		
		<?php						
			if(isset($_GET["page"]))
					$page_content = "pages/".$_GET["page"].".php";					
			else	
					$page_content = 'pages/default.php';
			include('masterpage.php');
		?>
	</body>
	
	<script>
		$('.sidebarPageSelector').click(function(){
			var options = {};	  
			var toLoad = $(this).attr('name');
			$('#content').effect('fade',options,500,loadContent);
			window.location.hash = $(this).attr('href').substr(0,$(this).attr('href').length-4);
			function loadContent() {
				$('#content').load(toLoad,'',showNewContent())
			}
			function showNewContent() {
				setTimeout(function() {
					$( "#content" ).removeAttr( "style" ).hide().fadeIn();
				}, 1500 );
			}
			return false;
			
		});
	</script>
</html>