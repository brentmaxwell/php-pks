<?php
require_once('pgpkey.php');
require_once('keyserver.php');

$keyserver = new hksKeyServer();

if (ereg("/lookup\?(.*)",$_SERVER['REQUEST_URI'],$regs)) {
	parse_str($regs[1],$vars);
	if ($vars['op'] == 'get') {
		$query = $vars['search'];
		$keyserver->get($query,$vars['options']=='mr');
	}
	else if ($vars['op'] == 'index') {
		$query = $vars['search'];
		$keyserver->index($query,$vars['options']=='mr');
	}
	else if ($vars['op'] == 'vindex') {
		$query = $vars['search'];
		$keyserver->vindex($query);
	}
	else {
		header("HTTP/1.0 501 Not Implemented");
	}
}

else if (ereg("/key/(.*)",$_SERVER['REQUEST_URI'],$regs)) {
	$keyid = $regs[1];
	$keyserver->get($keyid);
}
else if (ereg("/add",$_SERVER['REQUEST_URI'])) {
	$result = $keyserver->add($_POST['keytext']);
	if($_REQUEST['addkey'] == 'Submit'){
		$pagedata = array(
			'message' => 'Key successfully submitted.'
		);
		include('form.php');
	}
}
else {
	include('form.php');
}

?>