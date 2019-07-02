<?php

class Mysql_connector {
	var $host, $user, $password;
	var $connection;
	var $query_result;
	var $error, $last_id;
	var $debug;
	var $W_last_query;
	
	function Mysql_connector($host, $user, $password) {
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->debug = FALSE;
		$this->errorlog = FALSE;
		$this->connect();
	}
	
	function connect() {
		$this->connection = mysql_connect($this->host, $this->user, $this->password);
		return $this->connection;
	}
	
	function close() {
		return mysql_close($this->connection);
	}
	
	function select_db($database) {
		return mysql_select_db($database, $this->connection);
	}
	
	function queryupdate($tabella, $arrayvalori, $where, $buff = 0) {
		$nuovoarray = array();
		foreach ($arrayvalori as $indice => $valore) {
			$nuovoarray[] = "`".$indice."` = '".addslashes($valore)."'";
		}
		if (is_array($where)) {
			$nuovoarraywhere = array();
			foreach ($where as $indice => $valore) {
				$nuovoarraywhere[] = "`".$indice."` = '".addslashes($valore)."'";
			}
			$nwhere = implode(" AND ", $nuovoarraywhere);
		}
		else {
			$nwhere = $where;
		}
		$query = "UPDATE ".$tabella." SET ".
			implode(", ", $nuovoarray)." WHERE ".$nwhere;
		$this->W_last_query = $query;
		return $this->query($query, $buff);
	}
	
	function queryinsert($tabella, $arrayvalori, $buff = 0) {
		$array1 = $array2 = array();
		foreach ($arrayvalori as $indice => $valore) {
			$array1[] = "`".$indice."`";
			$array2[] = "'".addslashes($valore)."'";
		}
		$query = "INSERT INTO ".$tabella." (".
			implode(", ", $array1).") VALUES (".
			implode(", ", $array2).")";
		$this->W_last_query = $query;
		return $this->query($query, $buff);
	}
	
	function queryreplace($tabella, $arrayvalori, $buff = 0) {
		$array1 = $array2 = array();
		foreach ($arrayvalori as $indice => $valore) {
			$array1[] = "`".$indice."`";
			$array2[] = "'".addslashes($valore)."'";
		}
		$query = "REPLACE INTO ".$tabella." (".
			implode(", ", $array1).") VALUES (".
			implode(", ", $array2).")";
		$this->W_last_query = $query;
		return $this->query($query, $buff);
	}
	
	function queryinsertodku($tabella, $arrayvalori, $campidatogliere = array(), $buff = 0) {
		$array1 = $array2 = array();
		$nuovoarray = array();
		foreach ($arrayvalori as $indice => $valore) {
			$array1[] = "`".$indice."`";
			$array2[] = "'".addslashes($valore)."'";
			if (!in_array($indice, $campidatogliere)) {
				$nuovoarray[] = "`".$indice."` = '".addslashes($valore)."'";
			}
		}
		$query = "INSERT INTO ".$tabella." (".
			implode(", ", $array1).") VALUES (".
			implode(", ", $array2).") ON DUPLICATE KEY UPDATE
			".implode(", ", $nuovoarray);
		$this->W_last_query = $query;
		return $this->query($query, $buff);
	}
	
	function query($query, $buff = 0) {
		$this->query_result[$buff] = mysql_query($query, $this->connection);
		$this->last_id = mysql_insert_id($this->connection);
		$temp = $this->query_result[$buff];
		if ($this->debug) {
			$stringa = strip_tags("<br /><strong>" . $buff . "</strong>\t-> <em>query</em>\t-> " . $query . "<br />");
//			error_log($stringa);
			if (!$temp) {
				echo $stringa."\n".$this->get_error();
			}
		}
		if ($this->errorlog) {
			error_log($buff." ".$query, 0);
		}
		return $temp;
	}
	
	function fetch($buff = 0) {
		return mysql_fetch_array($this->query_result[$buff]);
	}
	
	function fetch_a($buff = 0) {
		return mysql_fetch_assoc($this->query_result[$buff]);
	}
	
	function querynum($query, $buff = 0) {
		$this->query($query, $buff);
		return $this->num_rows($buff);
	}
	
	function queryfetch($query, $buff = 0) {
		$this->query($query, $buff);
		return $this->fetch($buff);
	}
	
	function get_error() {
		$this->error = "Error " . mysql_errno($this->connection) . " : " . 
			mysql_error($this->connection);
		return $this->error;
	}
	
	function num_rows($buff = 0) {
		return mysql_num_rows($this->query_result[$buff]);
	}
}

?>
