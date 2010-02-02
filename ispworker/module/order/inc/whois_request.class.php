<?php

class Whois_Request extends Request {

	function Whois_Request($server) {
		$this->Constructor($server, 43);
	}

	function write($domain) {
		parent::write($domain."\r\n");
	}

}

?>