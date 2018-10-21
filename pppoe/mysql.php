<?php

if(!function_exists('base_path')) { exit(); }

Class MySQL_Driver {

	protected $db, $method, $table, $operation, $columns, $condition, $order;

	public function __construct() {
		$host = config("database.host");
		$port = config("database.port");
		$dbname = config("database.name");
		$username = config("database.username");
		$password = config("database.password");
		$this->db = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4", $username, $password);
	}

	public function create($table) {
		$this->table = $table;
		$this->operation("INSERT");
		return $this;
	}

	public function get($table, $id = null) {
		$this->table = $table;
		$this->operation();
		if($id) {
			$this->where("id", $id); 
			return $this->query();
		}
		return $this;
	}

	public function update($table) {
		$this->table = $table;
		$this->operation("UPDATE");
		return $this;
	}

	public function remove($table) {
		$this->table = $table;
		$this->operation("DELETE");
		return $this;
	}

	public function values($values) {
		$operation = substr($this->operation, 0, 6);
		if($operation=="INSERT") {
			$columns = array_keys($values);
			if(is_string($columns[0])) { $this->columns($columns); }
			$values = implode(",", $values);
			$this->operation .= " VALUES({$values}) ";
		}
		if($operation=="UPDATE") {
			$this->operation .= " SET ";
			foreach($values as $column => $value) {
				$this->operation .= "{$column} = '{$value}',";
			}
			$this->operation = rtrim($this->operation, ",");
		}

		return $this;
	}

	public function columns($columns = "*") {
		$this->columns = (is_array($columns)) ? implode(",", $columns) : $columns;
		return $this;
	}

	public function select($columns = "*") {
		$this->columns($columns);
		return $this;
	}

	public function where($column, $value, $operator = "=") {
		if(substr($this->operation, 0, 6)=="INSERT") { return $this; }
		$condition = ($this->condition) ? " AND " : " WHERE ";
		$this->condition .= $condition . "{$column} {$operator} '{$value}' ";
		return $this;
	}
	
	public function first() {
		if(substr($this->operation, 0, 6)!="SELECT") { return false; }
		$this->order = " ORDER BY id DESC LIMIT 1";
		return $this->query();
	}

	public function latest($limit = 10) {
		if(substr($this->operation, 0, 6)!="SELECT") { return false; }
		$this->order = " ORDER BY id DESC LIMIT " . $limit;
		return $this->query();
	}

	public function all() {
		if(substr($this->operation, 0, 6)!="SELECT") { return false; }
		return $this->query();
	}

	public function save() {
		if(substr($this->operation, 0, 6)=="SELECT") { return false; }
		return $this->query();
	}

	public function now() {
		return $this->query();
	}

	protected function operation($operation = "SELECT") {
		$this->method = ($operation=="SELECT") ? "query" : "exec";
		if($operation=="SELECT") { $operation .= ($this->columns=="*" ? "*" : "(" . $this->columns .")") . " FROM " . $this->table; }
		if($operation=="INSERT") { $operation .= " INTO " . $this->table . ($this->columns=="*" ? "" : "(" . $this->columns .")"); }
		if($operation=="UPDATE") { $operation .= $this->table; }
		if($operation=="DELETE") { $operation .= " FROM " . $this->table; }
		$this->operation = $operation;
	}

	protected function query($query) {
		return $this->db->{$this->method}( $this->operation . $this->condition . $this->order );
	}

	public function lastID() {
		return $this->db->lastInsertId();
	}

}

?>