<?php
	class model {

		protected $pg_conn;
		static $args;

	    public function pg_connect(){

	    	if(!$this->pg_conn){
		    	$this->pg_conn = pg_connect("host=". HOST . " dbname=" . DATABASE_NAME . " user=" . DATABASE_USER . " password=" . DATABASE_PASS)
				or die("không thể kết nối tới database");
			}

	    }

	    public function get_conn(){
	    	return $this->pg_conn;
	    }

	    public function set_conn($conn){
	    	$this->pg_conn = $conn;
	    }
	    
	    public function pg_select_obj($item, $table = "", $con = ""){

	    	$table = trim($table);
	        $arr = [];
	        $i = 0;

	        if ($table) {

	        	$sql = "select $item from " . $table;

	        }else{

	        	$sql = "select " . $item;
	        }
	        

	        if ($con != ""){ 

	            $sql .= " where $con"; 
	        }

	        $result = $this->pg_query($sql);

	        if($result){

	            while ($myrow = pg_fetch_object($result)){
	            	
	               	$arr[$i] = (array)$myrow;
	                    
	                $i++;   
	            }

	            pg_free_result($result);

	            return $arr;

	        } else {

	            return false;
	        }

	    }

	    public function pg_select($item, $table = "", $con = ""){

	    	$table = trim($table);
	        $arr = [];
	        $i = 0;

	        if ($table) {

	        	$sql = "select $item from " . $table;

	        }else{

	        	$sql = "select " . $item;
	        }
	        

	        if ($con != ""){ 

	            $sql .= " where $con"; 
	        }

	        $result = $this->pg_query($sql);

	        if($result){

	            while ($myrow = pg_fetch_row($result)){
	            	
	                for ($j = 0; $j < count($myrow) ; $j++){

	                    $arr[$i][$j] = $myrow[$j];
	                }
	                    
	                $i++;   
	            }

	            pg_free_result($result);

	            return $arr;

	        } else {

	            return false;
	        }
	    }

	    public function pg_query($query){
	    	return pg_query($this->pg_conn,$query);
	    }
	}
?>