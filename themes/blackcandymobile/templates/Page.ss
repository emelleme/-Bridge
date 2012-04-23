<!DOCTYPE html>
<html lang="en">
  <head>
		<% base_tag %>
		<title><% if MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
		$MetaTags(false)
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />
		<link rel="shortcut icon" href="/favicon.ico" />
		<% require themedCSS(bootstrap) %>
		<% require themedCSS(bootstrap-responsive) %>
		<% require themedCSS(timeframe) %>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="$ThemeDir/js/app.js"></script>
	</head>
	<body>
			<div class="navbar navbar-fixed-top">
			  <div class="navbar-inner">
				<div class="container">
				  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				    <span class="icon-bar"></span>
				    <span class="icon-bar"></span>
				    <span class="icon-bar"></span>
				  </a>
				  <a class="brand" href="./index.html">$SiteConfig.Title</a>
				  
				  <% include Navigation %>
				  
				</div>
			  </div>
			</div>
			
		<div id="main" class="container">
			<div id="Layout">
				$Layout
				$PageComments
			</div>

			<div id="Footer">
				<% include Footer %>
			</div>
		</div>
		
	</body>
</html>
