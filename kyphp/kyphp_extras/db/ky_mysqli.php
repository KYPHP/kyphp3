<?php
// +----------------------------------------------------------------------
// ×î±ã½ÝµÄphp¿ò¼Ü
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.ky53.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.ky53.net )
// +----------------------------------------------------------------------

/**
 
 * @author   qq:974005652(zhx626@126.com) byÀÏÍçÍ¯
 * @version  2.0
 +------------------------------------------------------------------------------
 */
final class ky_mysqli {
	private $mysqli;
	
	public function __construct($hostname, $username, $password, $database,$charset='utf8') {
		$this->mysqli = new mysqli($hostname, $username, $password, $database);
		
		if ($this->mysqli->connect_error) {
      		trigger_error('Error: Could not make a database link (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
		}
		
		$this->mysqli->query("SET NAMES '{$charset}'");
		$this->mysqli->query("SET CHARACTER SET {$charset}");
		$this->mysqli->query("SET CHARACTER_SET_CONNECTION={$charset}");
		$this->mysqli->query("SET SQL_MODE = ''");
  	}
		
  	public function query($sql,$param=array()) {
		$data = array();
		if($param){
			$paramtype='';
			foreach($param as $pk=>$pv){
				if(is_int($pv)){
					$paramtype.='i';
				}
				else if(is_float($pv)){
					$paramtype.='d';
				}
				else if(is_string($pv)){
				
					$paramtype.='s';
				}else{
					$paramtype.='s';
				}
			
			}
			array_unshift($param,$paramtype);
			$stmt=$this->mysqli->prepare($sql);
			
			call_user_func_array(array($stmt, "bind_param"),$this->refValues($param));
			
			$stmt->execute();
			
			$data=$this->fetch($stmt);
		}else{
		
				$result = $this->mysqli->query($sql);		
			//if (is_resource($resource)) {
				$i = 0;
    	
				
				if($result){
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
					$data[$i] = $row;
    	
					$i++;
				}

				$result->close();
				}
				
				
			
		}
		if ($this->mysqli->errno) {
		
			trigger_error($this->mysqli->error.$sql);
			
		}
		$query = new stdClass();
		$query->row = isset($data[0]) ? $data[0] : array();
		$query->rows = $data;
		$query->num_rows =$data;
		
		unset($data);
				
				
		return $query;	
    		
		
  	}
	
	public function escape($value) {
		return $this->mysqli->real_escape_string($value);
	}
	
  	public function countAffected() {
    	return $this->mysqli->affected_rows;
  	}

  	public function getLastId() {
    	return $this->mysqli->insert_id;
  	}	
	function fetch($result)
	{    
		$array = array();
		
		if($result instanceof mysqli_stmt)
		{
			$result->store_result();
			
			$variables = array();
			$data = array();
			$meta = $result->result_metadata();
			
			while($field = $meta->fetch_field())
				$variables[] = &$data[$field->name]; // pass by reference
			
			call_user_func_array(array($result, 'bind_result'), $variables);
			
			$i=0;
			while($result->fetch())
			{
				$array[$i] = array();
				foreach($data as $k=>$v)
					$array[$i][$k] = $v;
				$i++;
				
				
			}
		}
		elseif($result instanceof mysqli_result)
		{
			while($row = $result->fetch_assoc())
				$array[] = $row;
		}
		
		return $array;
	}
	
	public function __destruct() {
		$this->mysqli->close();
	}
	public function exec($sql,$param=array()){
		if($param){
			$paramtype='';
			foreach($param as $pk=>$pv){
				if(is_int($pv)){
					$paramtype.='i';
				}
				else if(is_float($pv)){
					$paramtype.='d';
				}
				else if(is_string($pv)){
				
					$paramtype.='s';
				}else{
					$paramtype.='s';
				}
			
			}
			array_unshift($param,$paramtype);
			$stmt=$this->mysqli->prepare($sql);
			
			call_user_func_array(array($stmt, "bind_param"),$this->refValues($param));
			
			return $stmt->execute();
		}else{
			return $this->mysqli->query($sql);
		}
	
	}
	public function refValues($arr){ 
		if (version_compare(PHP_VERSION, '5.3.0') >= 0) { 
		$refs = array();
		foreach($arr as $key => $value) 
			$refs[$key] = &$arr[$key]; 
			return $refs;
		} 
		return $arr;
	}
}
?>