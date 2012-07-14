<?php
	if (isset($_GET['q'])) {
//		error_log('query set to ' . urldecode($_GET['q']));
		require_once('ztinyDB.php');
		$start = microtime(true);
		$ux = insertURL(urldecode(trim($_GET['q'])));
		echo json_encode($ux);
		$duration = sprintf("%d", (microtime(true) - $start) * pow(10,3));
		error_log('insert took ' . $duration . ' milli sec');
		exit(0);
	}
	$requestURI = trim($_SERVER['REQUEST_URI']);
	$baseURL = '/ztiny/';
//	error_log("received $requestURI");
	if ($requestURI != $baseURL) {
		$hash = preg_replace('/\/ztiny\//', '', $requestURI);
//		error_log("hash = $hash");
		require_once('ztinyDB.php');
		$result = lookup($hash);
		if (isset($result['url'])) {
//			error_log('redirecting ' . $hash . ' to ' . $result['url'] );
			header("Location: " . $result['url']);
			exit(0);
		}
		echo "<h3> No such URL </h3>";
		exit(0);
	}
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>SEG Tools: Shorten your URL</title>
    <script type="text/javascript" src="/jquery-1.7.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="ztiny.css" />
</head>
<body>
	<div id="main">
		<input type="text" name="url" />
		<a href='#'>Shorten</a>
	</div>
	<div id="footer"><a href="mailto:arbinish@gmail.com">Feedback</a></div>
    <script type="text/javascript" src="ztiny.js"></script>
</body>
</html>
<?php

function dumpVars()
{
	foreach ($_SERVER as $k => $v) {
		echo "$k => ", trim($v), "<br>";
	}
}
