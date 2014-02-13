<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
?>

<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="ru"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>EVAL</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" href="css/eval.css">
	<link rel="stylesheet" href="js/libs/codemirror/codemirror.css">
	<script src="js/libs/modernizr-2.0.6.min.js"></script>
	<link rel="shortcut icon" href="/eval.ico">
</head>
<body style="background: <?=(isset($_POST['command'])) ? '#ddd' : 'white'?>">
  <a id="#top"></a>
  <div id="container">
    <header>
		<h1>Eval</h1>
    </header>
    <div id="main" role="main">
		<form method="post">
			<p>
				<textarea name="command" rows="10" cols="60" id="code" name="code"><?
				if (isset($_POST['command'])) {
					echo $_POST['command'];
				}
				?></textarea>
			</p>
			<p style="text-align: center">
				<button type="submit">Eval</button>
			</p>

			<?
			if (isset($_POST['command'])) {
				echo '<hr><pre id="result"><div id="result_shadow"></div>';

				$command = trim($_POST['command']);

				if (count(explode("\n", $command)) == 1) {
					eval('$c = '.$command.';');
					echo $c;
				}
				else {
					eval($command);
				}
				echo '</pre>';
			}
			?>
		</form>
    </div>
  </div> <!--! end of #container -->


	<!-- JavaScript at the bottom for fast page loading -->

	<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/js/libs/jquery-1.6.2.min.js"><\/script>')</script>

	<script src="/js/libs/codemirror/codemirror.js"></script>
	<script src="/js/libs/codemirror/util/formatting.js"></script>
	<script src="/js/libs/codemirror/util/foldcode.js"></script>
  	<script src="/js/libs/codemirror/util/searchcursor.js"></script>
	<script src="/js/libs/codemirror/util/match-highlighter.js"></script>
	<script src="/js/libs/codemirror/xml/xml.js"></script>
	<script src="/js/libs/codemirror/css/css.js"></script>
	<script src="/js/libs/codemirror/clike/clike.js"></script>
	<script src="/js/libs/codemirror/php/php.js"></script>

	<script>
	var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
		lineNumbers: true,
		matchBrackets: true,
		mode: "text/x-php",
		indentUnit: 4,
		indentWithTabs: true,
		enterMode: "keep",
		tabMode: "shift",
		onCursorActivity: function() {
			editor.setLineClass(hlLine, null, null);
			hlLine = editor.setLineClass(editor.getCursor().line, null, "activeline");
			editor.matchHighlight("CodeMirror-matchhighlight");
		}
	});
	var hlLine = editor.setLineClass(0, "activeline");
	</script>
	<!-- end scripts-->

</body>
</html>
