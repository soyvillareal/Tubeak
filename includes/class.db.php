<?php
class db {

    protected $connection;
	protected $query;
    protected $query_closed = true;
    protected $show_errors = true;
    protected $return_status = false;
	public $query_count = 0;
	public $totalPages = 0;

	public function __construct($conn) {
		$this->connection = $conn;
	}

    public function query($query) {
        if (!$this->query_closed) {
            $this->query->close();
        }
        
		if ($this->query = $this->connection->prepare($query)) {
			$this->return_status = true;

            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
				$types = '';
                $args_ref = array();
                if(preg_match('/LIMIT \? OFFSET \?/i', $query)){
                	$rows = $this->connection->query(str_replace('*', 'COUNT(*)', explode('LIMIT', $query)[0]));
                	$rows = $rows->fetch_array()[0];
                	$this->totalPages = ceil($rows / $args[0]);
                	if($args[1] != 'reverse'){
                		$args[1] = ($args[1]-1) * $args[0];
                	} else {
                		$page = $rows - $args[0];
                		if ($page < 1) {
					        $page = 0;
					    }
					    $args[1] = $page;
                	}
                }
                foreach ($args as $k => &$arg) {
					if (is_array($args[$k])) {
						foreach ($args[$k] as $j => &$a) {
							$types .= $this->_gettype($args[$k][$j]);
							$args_ref[] = &$a;
						}
					} else {
	                	$types .= $this->_gettype($args[$k]);
	                    $args_ref[] = &$arg;
					}
                }
				array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            if($this->query->execute() === false){
            	$this->return_status = false;
            }
           	if ($this->query->errno) {
           		$this->return_status = false;
           	}
            $this->query_closed = false;
			$this->query_count++;
        } else {
        	$this->return_status = false;
        }
		return $this;
    }


	public function fetchAll($returnOnly = true) {
	    $params = array();
        $row = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $value) {
            	if($returnOnly == false){
            		$result[] = $value;
            	} else {
            		$r[$key] = $value;
            	}
            }
            if($returnOnly == true) $result[] = $r;
        }
        $this->query->close();
        $this->query_closed = true;
        return $result;
	}

	public function fetchArray() {
	    $params = array();
        $row = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
		while ($this->query->fetch()) {
			foreach ($row as $key => $val) {
				if(count($row) == 1){
					$result = $val;
				} else {
					$result[$key] = $val;
				}
			}
		}
        $this->query->close();
        $this->query_closed = true;
		return $result;
	}

	public function close() {
		return $this->connection->close();
	}

    public function numRows() {
		$this->query->store_result();
		return $this->query->num_rows;
	}

	public function affectedRows() {
		return $this->query->affected_rows;
	}

	public function returnStatus() {
		return $this->return_status;
	}

	public function returnConnection(){
		return $this->connection;
	}

	public function insertId() {
		return $this->connection->insert_id;
	}

    public function error($error) {
        if ($this->show_errors) {
            exit($error);
        }
    }

	private function _gettype($var) {
	    if (is_string($var)) return 's';
	    if (is_float($var)) return 'd';
	    if (is_int($var)) return 'i';
	    return 'b';
	}

}