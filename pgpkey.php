<?php

class pgpKey{
	public $id;
	public $length;
	public $algorithm;
	public $fingerprint;
	public $uids = array();
	public $date_created;
	public $date_expires;
	public $flags = array();
	public $subkeys = array();
}

class uid{
	public $uid;
	public $date_created;
	public $date_expires;
	public $flags = array();
}

class subKey{
	public $id;
	public $length;
	public $algorithm;
	public $fingerprint;
	public $date_created;
	public $date_expires;
}