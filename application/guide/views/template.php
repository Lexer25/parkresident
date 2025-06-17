<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<title><?php echo $title ?> | Kohana <?php echo 'User Guide'; ?></title>

<?php foreach ($styles as $style => $media) echo HTML::style($style, array('media' => $media), NULL, TRUE), "\n" ?>

<?php foreach ($scripts as $script) echo HTML::script($script, NULL, NULL, TRUE), "\n" ?>

</head>
<body>

<?php if (Kohana::$environment === Kohana::PRODUCTION): ?>
<script type="text/javascript">
//<![CDATA[
(function() {
	var links = document.getElementsByTagName('a');
	var query = '?';
	for(var i = 0; i < links.length; i++) {
	if(links[i].href.indexOf('#disqus_thread') >= 0) {
		query += 'url' + i + '=' + encodeURIComponent(links[i].href) + '&';
	}
	}
	document.write('<script charset="utf-8" type="text/javascript" src="http://disqus.com/forums/kohana/get_num_replies.js' + query + '"></' + 'script>');
})();
//]]>
</script>
<?php endif ?>
</body>
</html>
