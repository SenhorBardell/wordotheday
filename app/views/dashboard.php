<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard</title>
	<link rel="stylesheet" href="/css/pure-min.css">
	<link rel="stylesheet" href="/css/side-menu.css">
</head>
<body>
	<div id="layout">

	</div>

	<script src="/js/lib/jquery-1.11.1.min.js"></script>
	<script src="/js/lib/jquery.cookie.js"></script>
	<script src="/js/lib/underscore-min.js"></script>
	<script src="/js/lib/backbone-min.js"></script>

	<?php #<script src="script.min.js"></script> ?>

	<script src="/backbone.js"></script>



	<script>

		new App.Router;
		Backbone.history.start({pushState: true});

		var openLinkInTab = false;

		// Only need this for pushState enabled browsers
		if (Backbone.history && Backbone.history._hasPushState) {

			$(document).keydown(function(event) {
				if (event.ctrlKey || event.keyCode === 91) {
				openLinkInTab = true;
				}
			});

			$(document).keyup(function(event) {
				openLinkInTab = false;
			});

			// Use delegation to avoid initial DOM selection and allow all matching elements to bubble
			$(document).delegate("#menu a, .content a", "click", function(evt) {
				// Get the anchor href and protcol
				var href = $(this).attr("href");
				var protocol = this.protocol + "//";

				// Ensure the protocol is not part of URL, meaning its relative.
				// Stop the event bubbling to ensure the link will not cause a page refresh.
				if (!openLinkInTab && href.slice(protocol.length) !== protocol) {
					evt.preventDefault();

					// Note by using Backbone.history.navigate, router events will not be
					// triggered.  If this is a problem, change this to navigate on your
					// router.
					Backbone.history.navigate(href, true);

				}
			});

			$(document).delegate('#menuLink', 'click', function(e) {
				e.preventDefault();

				$('#layout').toggleClass('active');
				$('#menu').toggleClass('active');
				$('#menuLink').toggleClass('active');
			});

			$(document).delegate('.delete', 'click', function(e) {
				e.preventDefault();
			});

		}

	</script>

</body>
</html>