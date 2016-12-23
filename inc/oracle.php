<?php
class ORACLE{
	var $username='';
	var $password='';
	var $servername='';
	var $rp = '';
	var $stmt='';
	var $rows='';
	var $results='';
	var $str='';
	function ORACLE($username='',$password='',$servername=''){
		$this->servername=$servername;
		$this->username=$username;
		$this->password=$password;
		if(!empty($servername) && !empty($username)){
			$this->connect();
		}
	}
	function connect(){
		$this->rp=oci_connect($this->username, $this->password, $this->servername)or die("資料庫連接失敗");
	}

	#######################################################
	# 2015.10.21 Rewrite and test finish by Ting-Rui Chen #
	#######################################################
	function query($Qstr){
		if ($Qstr) {
			$this -> str = $Qstr;
			//change encoding before query cuz
			$Qstr = mb_convert_encoding($Qstr, "BIG5", "UTF-8");
			$stmt=oci_parse($this->rp, $Qstr); // parse sql to oracle statement

			if (!@oci_execute($stmt,OCI_COMMIT_ON_SUCCESS)){ // execute and auto commit to database
					//error occur and print error and sql
					$oci_err=@OCIError($stmt);
					foreach ($oci_err as $key => $value) {
						mb_convert_encoding($oci_err, "BIG5", "UTF-8");
					}
					//$oci_err = mb_convert_encoding($oci_err, "BIG5", "UTF-8");
					return $oci_err; //add by boblee!
					//echo "<p>資料處理失敗，錯誤訊息: " . $oci_err[message] . "<br>錯誤請求指令" . $this -> str ."</p><hr>";
			}
			else {
				return $stmt;
			}
		}
	}

	function query_trsac($Qstr){
		if ($Qstr) {
			$this ->str = $Qstr;
			//change encoding before query cuz
			$Qstr = mb_convert_encoding($Qstr, "BIG5", "UTF-8");
			$stmt=oci_parse($this->rp, $Qstr); // parse sql to oracle statement

			if (!@oci_execute($stmt,OCI_NO_AUTO_COMMIT)){ // execute and auto commit to database
					oci_rollback($this ->rp);
					//error occur and print error and sql
					$oci_err=@oci_error($stmt);
					foreach ($oci_err as $key => $value) {
						$oci_err[$key] = iconv(mb_detect_encoding($oci_err['message']), "UTF-8",$value);
					}

					// foreach ($oci_err as $key => $value) {
					// 	$oci_err[$key] = mb_convert_encoding($value, "UTF-8",mb_detect_encoding($oci_err['message']));
					// }

					//iconv("UTF-8","BIG5",$oci_err['message']);
					//mb_convert_encoding($oci_err['message'],"BIG5", "UTF-8");
					//$oci_err['message'] = iconv(mb_detect_encoding($oci_err['message']),"UTF-8",$oci_err['message']);
					//return mb_detect_encoding($oci_err['message']);
					//$oci_err['message'] = mb_convert_encoding($oci_err['message'],"UTF-8", mb_detect_encoding($oci_err['message']));
					return $oci_err; //add by boblee!
					//echo "<p>資料處理失敗，錯誤訊息: " . $oci_err[message] . "<br>錯誤請求指令" . $this -> str ."</p><hr>";
			}
			else {
				return $stmt;
			}
		}
	}

	function create_savepoint($SPname){
		if ($SPname) {
			$this ->str = 'SAVEPOINT '.$SPname;
			//change encoding before query cuz
			//$SPname = mb_convert_encoding($SPname, "BIG5", "UTF-8");
			$stmt = oci_parse($this->rp, $this ->str); // parse sql to oracle statement

			if (!@oci_execute($stmt,OCI_NO_AUTO_COMMIT)){ // execute and auto commit to database
					//error occur and print error and sql
					$oci_err=@OCIError($stmt);
					return $oci_err; //add by boblee!
					//echo "<p>資料處理失敗，錯誤訊息: " . $oci_err[message] . "<br>錯誤請求指令" . $this -> str ."</p><hr>";
			}
			else {
				return $stmt;
			}
		}
	}

	function rb_to_savepoint($SPname){
		if ($SPname){
				$this ->str = 'ROLLBACK TO SAVEPOINT '.$SPname;
				//change encoding before query cuz
				//$SPname = mb_convert_encoding($SPname, "BIG5", "UTF-8");
				$stmt = oci_parse($this->rp, $this ->str);
				if (!@oci_execute($stmt,OCI_NO_AUTO_COMMIT)){ // execute and auto commit to database
						//error occur and print error and sql
						$oci_err=@OCIError($stmt);
						return $oci_err; //add by boblee!
						//echo "<p>資料處理失敗，錯誤訊息: " . $oci_err[message] . "<br>錯誤請求指令" . $this -> str ."</p><hr>";
				}
				else {
					return $stmt;
				}
		}
	}

	function end_trsac(){
		$committed=oci_commit($this->rp);
		return $committed;
	}

	#######################################################
	# 2015.10.21 Rewrite and test finish by Ting-Rui Chen #
	#######################################################
	function fetch_array($resource){
			$this->rows = oci_fetch_all($resource, $this->results);
			#轉換編碼
			for ($nCOL=1; $nCOL <= oci_num_fields($resource); $nCOL++) { // For each col. to do change encoding
					$Field_Name = oci_field_name($resource, $nCOL); // Get current field name
					for ($nROW=0; $nROW < sizeof($this->results[$Field_Name]); $nROW++) { // For each row to do change encoding
							$this->results[$Field_Name][$nROW] = mb_convert_encoding($this->results[$Field_Name][$nROW], "UTF-8", "BIG5");
					}
			}
			return $this->results;
	}

	function fetch_row($resource){
		return oci_fetch_row($resource);
	}
	function free_result($resource){
		return oci_free_statement($resource);
	}
	function num_rows($resource){
		return $this->$rows;
	}

	function next_id($table)
	{
		$Q = "SELECT MAX(ID) FROM ".$table;
		$id = $this->query_array($Q);
		return ($id['MAX(ID)'][0] == '') ? 0 : ++$id['MAX(ID)'][0];
	}

	#######################################################
	# 2015.11.16 Rewrite and test finish by Ting-Rui Chen #
	#######################################################
	function insert_id(){
		$pos = strpos($this->str, "INTO");
		$pos2 = strpos($this->str, "(");
		if($pos === false)
		{
			$pos = strpos($this->str, "into");
			if($pos === false){echo "<p>錯誤！！</p>";}
			else{
				$pos += 5;
				$len = $pos2 - $pos;
				$name = substr($str, $pos, $len);
			}
		}
		else
		{
			$pos += 5;
			$len = $pos2 - $pos;
			$name = substr($str, $pos, $len);
		}

		if($name != "")
		{
			$Q = "SELECT MAX(ID) FROM ".$name;
			$id = $this->query_array($Q);
			return ++$id['MAX(ID)'][0];
		}
		else
		{
			echo "<p>錯誤！！</p>";
		}
	}

	function query_first_row($Qstr){
		return $this->fetch_row($this->query($Qstr));
	}
	
	#####################################
	# 2016.11.05 Write and test finishd #
	#####################################
	
	/*
	*	this function "query_array" has been rewrited
	*	which can optionally returns JSON type array used for dataTable, jqGrid, ...etc.
	*
	*	@param  string $Qstr	  : SQL statement
	*	@param  bool   $isRowMode : An optional parameter decide OCI_FETCH_MODE
	*	@return array of data
	*
	*	usage:
	*	Default. Column Mode:
	*	$data = $db -> query_array($sql);
	*	
	*	For dataTable, jqGrid. Row Mode:
	*	$aaData['rows'] = $db -> query_array($sql, true);
	*/
	function query_array($Qstr, $isRowMode = false){
		$resource = $this->query($Qstr);
		
		if(!$isRowMode)
			return $this->fetch_array($resource);
		
		$this->rows = oci_fetch_all($resource, $this->results,
									0, -1, //skip, maxrows(-1 = all rows)
									OCI_ASSOC + OCI_FETCHSTATEMENT_BY_ROW);
		//Convert Encoding
		for($i = 0 ; $i < sizeof($this->results) ; $i++){
			array_walk(
				$this->results[$i],
				function(&$val){
					$val = mb_convert_encoding($val, "UTF-8", "BIG5");
				}
			);
		}
		
		return $this->results;
	}
	
	function set_rowid(&$rsc, $col_id_name = ""){
		if($col_id_name === ""){
			for($i = 0 ; $i < sizeof($rsc) ; $i++){
				$rsc[$i]['DT_RowId'] = $i + 1;
			}
		}
		else{
			$col_id_name = strtoupper($col_id_name);
			for($i = 0 ; $i < sizeof($rsc) ; $i++){
				$rsc[$i]['DT_RowId'] = $rsc[$i][$col_id_name];
				unset($rsc[$i][$col_id_name]);
			}
		}
	}

	
	/*
	*	this function "fetch_cell" is used to fetch a datum.
	*
	*	@param  string $Qstr    : SQL statement
	*	@param  bool   $colName : An optional parameter can be used to fetch a specific column.
	*	@return mixed $ret
	*
	*	usage:
	*	$val = $db -> fetch_cell($sql);
	*	OR
	*	$val = $db -> fetch_cell($sql [,string $Field_Name]);
	*/
	function fetch_cell($Qstr, $colName = ""){
		$ret = $colName === ""  ? oci_fetch_row($this->query($Qstr))[0]
								: oci_fetch_assoc($this->query($Qstr))[strtoupper($colName)];
		return iconv("BIG5", "UTF-8", $ret);
	}
	
	########################################
	# 2016.11.17 Rewrite and test finished #
	########################################
	
	/*
	*	this function "query_by_param", a Parameterized Query version, is derivatived from "query".
	*	it's designed to avoid SQL Injection
	*
	*	@param  string $Qstr	  	: SQL statement
	*	@param  bool   $data 		: An associative array of data
	*	@return the error information as an associative array
	*
	*
	*	usage:
	*	$sql = "INSERT INTO table_name (col_name01, col_name02, ...) VALUES(:param01, :param02, ...)" //parameter name with colon
	*	$data = array (
	*		"param01" => val01,
	*		"param02" => val02, ...
	*		// key_name without colon
	*	);
	*
	*	$err_msg = $db -> query_by_param($sql, $data);
	*/
	function query_by_param($Qstr, $data){
		if ($Qstr) {
			$this -> str = $Qstr;
			
			$stmt=oci_parse($this->rp, $Qstr); // parse sql to oracle statement
			
			foreach($data as $key => $val){
				oci_bind_by_name(
					$stmt, 
					$key[0] === ":" ? $key : ":".$key, // parameter
					mb_convert_encoding($val, "UTF-8", "BIG5")); //value
			}

			if (!@oci_execute($stmt,OCI_COMMIT_ON_SUCCESS)){ // execute and auto commit to database
					//error occur and print error and sql
					$oci_err=@oci_error($stmt);
					return $oci_err; //add by boblee!
					//echo "<p>資料處理失敗，錯誤訊息: " . $oci_err[message] . "<br>錯誤請求指令" . $this -> str ."</p><hr>";
			}
			else {
				return $stmt;
			}
		}
	}
}
?>
