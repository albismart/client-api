<?php

if(!function_exists('base_path')) { exit(); }

Class MySQL_Driver {

	protected $db, $method, $table, $operation, $columns, $condition, $order, $group, $limit;

	public function __construct() {
		$host = config("database.host");
		$port = config("database.port");
		$dbname = config("database.name");
		$username = config("database.username");
		$password = config("database.password");
		$this->db = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4", $username, $password);
	}

	public function create($table) {
		$this->table($table);
		$this->operation("INSERT");
		return $this;
	}

	public function get($table, $id = null, $column = "id") {
		$this->table($table);
		$this->operation("SELECT");
		return ($id==null) ? $this : $this->where($column, $id)->first($column);
	}

	public function update($table) {
		$this->table($table);
		$this->operation("UPDATE");
		return $this;
	}

	public function remove($table) {
		$this->table($table);
		$this->operation("DELETE");
		return $this;
	}

	public function values($values) {
		$operation = substr($this->operation, 0, 6);
		if($operation=="INSERT") {
			$columns = array_keys($values);
			if(is_string($columns[0])) { $this->columns($columns); }
			$values = implode("','", $values);
			$this->operation .= " VALUES('{$values}') ";
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

	/* Alias and columns method */
	public function select($columns = "*") {  return $this->columns($columns); }
	public function columns($columns = "*") {
		$this->columns = (is_array($columns)) ? implode(",", $columns) : $columns;
		$this->operation = str_replace("*", $this->columns, $this->operation);
		return $this;
	}

	public function where($column, $value, $operator = "=") {
		if(substr($this->operation, 0, 6)=="INSERT") { return $this; }
		$condition = ($this->condition) ? " AND " : " WHERE ";
		$this->condition .= $condition . "{$column} {$operator} '{$value}' ";
		return $this;
	}

	public function isNull($column, $isNull = true) {
		if(substr($this->operation, 0, 6)=="INSERT") { return $this; }
		$isNull = ($isNull) ? " IS NULL " : " IS NOT NULL ";
		$condition = ($this->condition) ? " AND " : " WHERE ";
		$this->condition .= $condition . " {$column} {$isNull} ";
		return $this;
	}

	public function fetch() {
		$result = $this->query();
		if($result) { $result = $result->fetch(PDO::FETCH_OBJ); }
		return ($result!=false) ? $result : null;
	}

	public function first($column = "id", $order = "DESC") {
		if(substr($this->operation, 0, 6)!="SELECT") { return false; }
		$this->order($column, $order);
		$this->limit(1);
		$result = $this->query();
		if($result) { $result = $result->fetch(PDO::FETCH_OBJ); }
		return ($result!=false) ? $result : null;
	}

	public function latest($limit = 10, $column = "id", $order = "DESC") {
		if(substr($this->operation, 0, 6)!="SELECT") { return false; }
		$this->order($column, $order);
		$this->limit($limit);
		return $this->all();
	}

	/* Alias and columns method */
	public function fetchAll() { return $this->all(); }
	public function all() {
		if(substr($this->operation, 0, 6)!="DELETE") { return $this->query(); }
		if(substr($this->operation, 0, 6)!="SELECT") { return false; }
		$result = $this->query();
		if($result) { $result = $result->fetchAll(PDO::FETCH_OBJ); }
		return ($result!=false) ? $result : null;
	}

	public function save() {
		if(substr($this->operation, 0, 6)=="SELECT") { return false; }
		return $this->query();
	}

	public function now() {
		return $this->query();
	}

	public function innerJoin($statement) {
		if($statement) { $this->operation .= "INNER JOIN " . $statement; }
		return $this;
	}

	public function order($column = "id", $order = "DESC") {
		if($column!="") { $this->order = " ORDER BY {$column} {$order} "; }
		return $this;
	}

	public function group($column = "id") {
		if($column!="") { $this->group = " GROUP BY {$column} "; }
		return $this;
	}

	public function limit($limit = 1) {
		if($limit!=0) { $this->limit = " LIMIT {$limit} "; }
		return $this;
	}

	protected function table($table) {
		$this->table = $table;
		$this->columns = "*";
		$this->condition = "";
		$this->order = "";
		$this->group = "";
		$this->limit = "";
	}

	protected function operation($operation = "SELECT") {
		$this->method = ($operation=="SELECT") ? "query" : "exec";
		if($operation=="SELECT") { $operation .= " " . ($this->columns=="*" ? "*" : "(" . $this->columns .")") . " FROM " . $this->table; }
		if($operation=="INSERT") { $operation .= " INTO " . $this->table . " (" . $this->columns .") "; }
		if($operation=="UPDATE") { $operation .= " " .$this->table; }
		if($operation=="DELETE") { $operation .= " FROM " . $this->table; }
		$this->operation = $operation . " ";
	}

	protected function query() {
		$query = $this->db->{$this->method}( $this->operation . $this->condition . $this->order . $this->group . $this->limit );
		return ($query!=false) ? $query : null;
	}

}

?>