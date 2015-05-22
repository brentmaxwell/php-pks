<html>
	<head>
		<title>Search Results for '<?php echo $query;?>'</title>
		<link rel="stylesheet" type="text/css" href="/css/style.css"></link>
		<script type="text/javascript" src="/js/jquery.min.js"></script>
		<script type="text/javascript" src="/js/jquery.qrcode.min.js"></script>
	</head>
	<body>
		<h1>Search Results for '<?php echo $query;?>'</h1>
		<?php if (count($keys) > 0) {?>
		<table>
			<thead>
				<tr>
					<th>Type</th>
					<th>Size</th>
					<th>Key ID</th>
					<th>Created</th>
					<th>Expires</th>
					<th>Flags</th>
					<th>QR Code</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach($keys as $key){
				$rowcount = 1 + count($key->uids);
				switch($key->algorithm)
				{
					case 1: $algorithm = "R";break;
					case 16: $algorithm = "g";break;
					case 17: $algorithm = "D";break;
					case 18: $algorithm = "e";break;
					case 19: $algorithm = "E";break;
					default: $algorithm = '';break;
				}
				?>
				<tr class="pub" id="<?php echo $key->fingerprint;?>">
					<td>pub</td>
					<td><?php echo $key->length . $algorithm;?></td>
					<td><a href="<?php echo $url.'?op=get&search=0x'.$key->id;?>"><?php echo $key->id;?></a></td>
					<td><?php echo date('Y-m-d',$key->date_created);?></td>
					<td><?php echo (empty($key->date_expires) ? '&nbsp;':date('Y-m-d',$key->date_expires));?></td>
					<td><?php echo (empty($key->flags) ? '&nbsp;' : str_replace('-','',implode("",$key->flags)));?></td>
					<td rowspan="<?php echo $rowcount;?>">
						<span class="qrcode_link">View</span>
						<div class="qrcode">
						<script type="text/javascript">
							$('#<?php echo $key->fingerprint;?> .qrcode').qrcode({width:150,height:150,text:"openpgp4fpr:<?php echo $key->fingerprint;?>"});
							$('#<?php echo $key->fingerprint;?> .qrcode_link').on('click',function(){$('#<?php echo $key->fingerprint;?> .qrcode').toggle();});
							$('#<?php echo $key->fingerprint;?> .qrcode').on('click',function(){$(this).toggle();});
						</script>
				</tr>
			<?php
				foreach($key->uids as $uid){
				?>
				<tr class="uid">
					<td>uid</td>
					<td colspan="5">
						<?php echo htmlentities($uid->uid);?>
					</td>
				</tr>
				<?php }?>
			<?php }?>
			</tbody>
		</table>
		<?php }else{ ?>
		No results found.
		<?php } ?>
	</body>
</html>