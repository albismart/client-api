<?php

if(!function_exists('base_path')) { header("HTTP/1.1 404 Not found"); exit(); }

Class OMAPI_Driver {
	
	protected $hostname, $port, $key, $omshellPath;
	protected $process, $descriptorSpec, $pipes;
	protected $connected = false;
	
	public function __construct() {
		$this->hostname = config("omapi.hostname");
		$this->port = config("omapi.port");
		$this->key = config("omapi.key");
		$this->omshellPath = config("omapi.omshellPath");
		$this->descriptorSpec = array(array("pipe", "r"), array("pipe", "w"), array("file", "/tmp/eo.txt", "a") );
		$this->connect();
	}

	public function status() {
		return $this->read($this->pipes);
	}

	protected function connect() {
		$this->process = proc_open($this->omshellPath, $this->descriptorSpec, $this->pipes);
		if(is_resource($this->process)) {
			$this->write("port " . $this->port);
			$this->write("key omapi_key " . $this->key);
			$this->write("server " . $this->hostname);
			$this->write("connect");
			$connected = $this->read($this->pipes);
			if(substr($connected,0,4) == "obj:") { 
				$this->connected = true;
			}
		} else {
			var_dump("Missing omshell service");
		}
	}

	protected function disconnect() {
		fclose($this->pipes[0]);
		fclose($this->pipes[1]);
		return proc_close($this->process);
	}

	protected function read($pipes, $end = '> ', $length = 1024) {
		$result = "";
		stream_set_blocking($pipes[1], FALSE);
		while(!feof($pipes[1])) {
			$buffer = fgets($pipes[1], $length);
			$result .= $buffer;
			if(substr_count($buffer, $end) > 0) {
				$pipes[1] = "";
				break;
			}
		}
		return $result;
	}

	protected function write($command, $read = true) {
		if($read) { $this->read($this->pipes); }
		fwrite($this->pipes[0], $command . "\n");
		return $command . "\n";
	}

}

?>