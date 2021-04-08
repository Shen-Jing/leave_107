<?php
	class Database
	{
		//mysql
		/*private $dbName = DB_NAME ; 
		private $dbHost = DB_HOST ;
		private $dbUsername = DB_USER;
		private $dbUserPassword = DB_PASS;*/

		//oracle
		private $tns;

		private $dbName; 
		private $dbHost;
		private $dbUsername;
		private $dbUserPassword;

		private $type;		
		private $dbh;
		private $error;
		private $stmt;

		public function __construct($DB_type,$DB_Name,$DB_Host,$DB_Username,$DB_UserPassword) {
			$this->dbName=$DB_Name;
			$this->dbHost=$DB_Host;
			$this->dbUsername=$DB_Username;
			$this->dbUserPassword=$DB_UserPassword;
			$this->type=$DB_type;

			if($this->type==1)
			{
				try{
						$this->tns="  
  						(DESCRIPTION =
    						(ADDRESS_LIST =
   	    						(ADDRESS = (PROTOCOL = TCP)(HOST =". $this->dbHost.")(PORT = 1521))
     						)
    						(CONNECT_DATA =
	       						(SERVICE_NAME = ".$this->dbName.")
    	 					)
   						);";//charset=AL32UTF8
     					$this->dbh = new PDO("oci:dbname=".$this->tns,$this->dbUsername,$this->dbUserPassword);
     					
 				}
 				catch(PDOException $e){
     				$this->error = $e->getMessage();
    				echo "connect fails  ".$this->error;
    				exit;
  				}
			}
			elseif ($this->type==2) 
			{
				// Set DSN
				$dsn = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName.';charset=utf8';

				// Set options 		   			          
				$options = array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);


				try {
    					$this->dbh = new PDO($dsn, $this->dbUsername, $this->dbUserPassword, $options);	
				}

				// Catch any errors
				catch (PDOException $e) {
    				$this->error = $e->getMessage();
    				echo "connect fails  ".$this->error;
    				exit;
				}
			}
			

		}
	
		public function query($sql){
    		$this->stmt = $this->dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    		//return $this->dbh->query($stmt);
		}

		public function bind($param, $value, $type = null){
 			if (is_null($type)) {
    	    	switch (true) {
    	        	case is_int($value):
    	        	    $type = PDO::PARAM_INT;
    	        	    break;
    	        	case is_bool($value):
    	        	    $type = PDO::PARAM_BOOL;
    	        	    break;
    	        	case is_null($value):
    	        	    $type = PDO::PARAM_NULL;
    	        	    break;
    	        	default:
    	        	    $type = PDO::PARAM_STR;
        		}
   			}
    		$this->stmt->bindValue($param, $value, $type);
		}

		/*public function insert(){

		}*/

		public function execute(){
    		return $this->stmt->execute();
		}

		public function resultset(){
    		$this->execute();
    		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		public function group($times){
			$this->execute();
			$groupset=array();
			for($i=0;$i<$times;$i++)
			{
				if($t=$this->stmt->fetch(PDO::FETCH_ASSOC))
					{
						$groupset[$i]=$t;
					}
				else
					break;
			}
			return $groupset;
		}

		public function single(){
			
			$this->execute();
			return $this->stmt->fetch(PDO::FETCH_ASSOC);
		}

		public function pick(){
			$this->execute();
			return $this->stmt->fetchAll(PDO::FETCH_ASSOC, PDO::FETCH_ORI_REL, 1);
		}
		public function rowcount(){
			return $this->stmt->rowCount();
		}

		public function lastinsertid(){
			return $this->dbh->LastInsertId();
		}

		public function begintransaction(){
	    	return $this->dbh->beginTransaction();
		}

		public function endtransaction(){
	    	return $this->dbh->commit();
		}

		public function canceltransaction(){
	    	return $this->dbh->rollBack();
		}

		public function debugdumpparams(){
	    	return $this->stmt->debugDumpParams();
		}

		public function disconnect()
		{
			return $this->dbh = null;
		}

	}
?>