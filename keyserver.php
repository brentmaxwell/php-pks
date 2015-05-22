<?php
class hksKeyServer {
	private $config;
	
	public function __construct(){
		$this->config = parse_ini_file('pks.ini');
		$this->config['tmp'] = $this->config['gpg_home']."/tmp";
	}
	
	public function get($query,$mr = false){
		$pgp_result = $this->gpg_exec("--export $query", $output);
		if (!$pgp_result && count($output) > 0) {
			if($mr){
				header("Content-Type: application/pgp-keys");
				header("Content-Disposition: attachment; filename=$id.asc");
			}else{
				header("Content-Type: text/plain");	
			}
			foreach ($output as $output_line) {
				$key .= $output_line . "\n";
			}
			echo $key;
		}
		else {
			header("HTTP/1.0 404 Not Found");
			echo 'Key not found';
		}
	}
	
	public function index($query,$mr = false){
		$keys = $this->parse_keys($query);
		if($mr){
			if(count($keys) > 0){
				$output = 'info:1:'.count($keys) . "\n";
				foreach($keys as $key){
					$output .= "pub:" . $key->fingerprint . ":" . $key->algorithm . ":" . $key->length . ":" .$key->date_created .":". $key->date_expires .":" . str_replace('-','',implode("",$key->flags)) ."\n";
					foreach($key->uids as $uid){
						$output .= "uid:" . $uid->uid . ":" . $uid->date_created . ":" . $uid->date_expires . ":" . str_replace('-','',implode("",$key->flags)) . "\n";
					}
				}
				header('Content-type: text/plain');
				echo $output;
			}
			else{
				header("HTTP/1.0 404 Not Found");
			}
		}
		else{
			$url = explode('?',$_SERVER['REQUEST_URI'])[0];
			header("Content-Type: text/html");
			include('results.php');
		}
	}

	public function add($key){
		if ($this->config['max_keysize'] == -1 || strlen($_POST['keytext']) <= $this->config['max_keysize']) {
			$tmp = fopen($this->config['tmp'],"w");
			if ($tmp) {
				fwrite($tmp,$_POST['keytext']);
				fclose($tmp);
				$pgp_result = $this->gpg_exec("--import < ".$this->config['tmp'], $output);
				if ($pgp_result) {
					header("HTTP/1.0 500 Internal Server Error");
					return false;
				}
				$tmp = fopen($this->config['tmp'],"w");
				fwrite($tmp,'');
				fclose($tmp);
				return true;
			}
			else {
				header("HTTP/1.0 500 Internal Server Error");
				return false;
			}
		}
		else {
			header("HTTP/1.0 403 Forbidden");
			return false;
		}
	}
	
	private function gpg_exec($command,&$output){
		$options = "--homedir " . $this->config['gpg_home'];
		foreach($this->config['options'] as $option)
		{
			$options .= " --$option"; 
		}
		$command = $this->config['gpg_command'] . " $options $command";
		$command_line = "$command 2>>" . $this->config['gpg_home'] . "/" . $this->config['gpg_log']; 
		exec($command_line,$output,$result);
		return $result;
	}
	
	private function parse_keys($query){
		$pgp_result = $this->gpg_exec(" --keyid-format 0xLONG --with-colons --list-public-keys --fingerprint --list-keys $query", $output);
		$keys = array();
		$current_key = 0;
		foreach ($output as $index_line) {
			$parts = explode(":",$index_line);
			switch($parts[0]){
				case "pub":
					$current_key = count($keys);
					$keys[$current_key] = new pgpKey();
					$keys[$current_key]->length = $parts[2];
					$keys[$current_key]->algorithm = $parts[3];
					$keys[$current_key]->id = $parts[4];
					$keys[$current_key]->date_created = $parts[5];
					$keys[$current_key]->date_expires = $parts[6];
					$keys[$current_key]->flags = str_split($parts[1]);
					break;
				case "fpr":
					$keys[$current_key]->fingerprint = $parts[9];
					break;
				case "uid":
					$uid = new uid();
					$uid->uid = $parts[9];
					$uid->date_created = $parts[5];
					$uid->date_expires = $parts[6];
					$uid->flags = str_split($parts[1]);
					$keys[$current_key]->uids[] = $uid;
					break;
				case "sub":
					$subkey = new subKey();
					$subkey->length = $parts[2];
					$subkey->algorithm = $parts[3];
					$subkey->id = $parts[4];
					$subkey->date_created = $parts[5];
					$subkey->date_expires = $parts[6];
					$keys[$current_key]->subkeys[] = $subkey;
					break;
				case "sig":
					break;
			}
		}
		return $keys;
	}
}