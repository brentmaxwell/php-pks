<?php
if(empty($pagedata)){
	$pagedata = array();
}
if(!array_key_exists('title',$pagedata)){
	$pagedata['title'] = 'theBrent.net Keyserver';
}
?>
<html>
	<head>
		<title><?php echo $pagedata['title'];?></title>
		<link rel="stylesheet" type="text/css" href="/css/style.css"></link>
	</head>
	<body>
		<h1><?php echo $pagedata['title'];?></h1>
		<?php if(array_key_exists('message',$pagedata)){
			echo '<p>'.$pagedata['message'].'</p>';
		}?>
		<form method="get" action="/lookup">
			<input type="hidden" name="op" value="index"/>
			Search: <input type="text" name="search"/>
			<input type="submit"/>
		</form>
		<form method="post" action="/add">
			<h2>Add key</h2>
			<textarea rows="10" cols="100" name="keytext"></textarea>
			<br/>
			<input type="submit" name="addkey"/>
		</form>
	</body>
</html>