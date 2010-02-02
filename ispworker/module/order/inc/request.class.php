<?php

class Request {

	var $server, $port, $timeout, $id = false;

	function Request($server, $port, $timeout = 15) {
		$this->Constructor($server, $port, $timeout);
	}

	function Constructor($server, $port, $timeout = 15) {
		$this->server  = $server;
		$this->port    = $port;
		$this->timeout = $timeout;
	}

	function connect() {
		$this->id = @fsockopen($this->server, $this->port, $dummy1, $dummy2, $this->timeout);
		if (!$this->id) {
			trigger_error('Could not connect to host '.$this->server.' at port '.$this->port.'.');
			return false;
		}

		return true;
	}

	function close() {
		fclose($this->id);
	}

	function write($data) {
		fwrite($this->id, $data);
	}

	function read($bytes = 1024) {
		return fread($this->id, $bytes);
	}

	function readln($bytes = 1024) {
		return fgets($this->id, $bytes);
	}

	function eof() {
		return feof($this->id);
	}

	function readAll() {
		$buffer = '';
		while (!$this->eof()) {
			$buffer .= $this->read();
		}
		return $buffer;
	}

	function doRequest($data_to_send) {
		if (!$this->connect()) {
			return false;
		}

		$this->write($data_to_send);
		$result = $this->readAll();
		$this->close();

		return $result;
	}

}

?>
