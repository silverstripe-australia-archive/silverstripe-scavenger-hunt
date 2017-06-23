<!DOCTYPE html>

<html lang="en">
	<head>
		<% base_tag %>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>The Great SilverStripe Scavenger Hunt @ Web Directions 2012 | Symbiote</title>
		<% require themedCSS(layout) %>
		<% require themedCSS(typography) %>
		<% require themedCSS(forms) %>
	</head>

	<body class="$URLSegment">
	
		<div id="wrapper-outer">
			
			<img src="$ThemeDir/images/bg_main.jpg" class="bgImage" />
			
			<div id="wrapper-inner">
				<div id="header">
					<p id="logo"><a href="home"><img src="$ThemeDir/images/symbiote_create-the-web.png" /></a></p>
					<% include MainNavigation %>
				</div>
				
				<div id="wrapper-content">
					<div class="right-content">
						$Layout
					</div>
				</div>
			</div>
			
		</div>
		
	</body>

</html>