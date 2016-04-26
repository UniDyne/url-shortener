<?php

define('READ_ONLY', true);
define('READ_WRITE', false);

class lilURL {
	// constructor
	function lilURL($read_only) {
		// open mysql connection
		if($read_only){
			$this->dbcon = new mysqli(MYSQL_READ_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
		} else {
			$this->dbcon = new mysqli(MYSQL_WRITE_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
		}
		
		if($this->dbcon->connect_error) die('Could not connect to database');
	}

	// return the id for a given url (or -1 if the url doesn't exist)
	function get_id($url) {
		$q = 'SELECT id FROM '.URL_TABLE.' WHERE (url="'.$url.'")';
		$result = $this->dbcon->query($q);
		
		if ($result->num_rows) {
			$row = $result->fetch_array();
			return base_convert($row['id'], 10, 36);
		} else {
			return -1;
		}
	}
	
	// return the url for a given id (or -1 if the id doesn't exist)
	function get_url($id) {
		$q = 'SELECT url FROM '.URL_TABLE.' WHERE (id = '.base_convert($id, 36, 10).')';
		$result = $this->dbcon->query($q);
		
		if ($result->num_rows) {
			$row = $result->fetch_array();
			return $row['url'];
		} else {
			return -1;
		}
	}
	
	// add a url to the database
	function add_url($url) {
		// check to see if the url's already in there
		$id = $this->get_id($url);
		
		// if it is, return true
		if ( $id != -1 ) {
			return true;
		} else {// otherwise, put it in
			$q = 'INSERT INTO '.URL_TABLE.' (url) VALUES ("'.$url.'")';
			$this->dbcon->query($q);
			
			return base_convert($this->dbcon->insert_id, 10, 36);
		}
	}
}

?>

