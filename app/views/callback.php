<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard</title>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.4.2/pure-min.css">
    <link rel="stylesheet" href="css/side-menu.css">
</head>
<body>
	
	<script src="http://code.jquery.com/jquery.js"></script>

	<script>
	(function() {
		console.log(document.location.hash.substring(1));
		$.ajax({
			type: "POST",
			url: "/oauth",
			data: document.location.hash.substring(1),
			success: function() {
				// window.close();
			}
		});
	})();
	</script>

	<h1>Callback View</h1>
</body>