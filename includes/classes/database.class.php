<?php
if(!defined("LOAD")){ header('HTTP/1.0 404 Not Found');  die('Not Found'); }

class cmsDatabase extends cmsCore{

  private static $instance;

  public $db_link;

  public $db_link_important;

  // ============================================================================ //
  // ============================================================================ //

  protected function __construct(){
    $this->db_link   = static::initConnection();
    $this->db_link_important   = static::initConnectionImportant();
  }

  public function __destruct(){
		mysqli_close($this->db_link);
    mysqli_close($this->db_link_important);
	}

  /**
  * Load function from class self
  * @return bool
  */
  public static function getInstance() {
    if (self::$instance === null) {
      self::$instance = new self;
    }
    return self::$instance;
  }

  /**
  * Connection to the database not important
  * @return bool
  */
  protected static function initConnection(){
    include(cmsCore::getInstance()->config['config']);
    $db_link = new mysqli($CONF['host'], $CONF['user'], $CONF['pass'], $CONF['name']);
    if ($db_link->connect_errno) {
      echo "Failed to connect to MySQL: (" . $db_link->connect_errno . ") " . $db_link->connect_error;
    }
    $db_link->set_charset("utf8");
    return $db_link;
  }

  /**
  * Connection to the database is important
  * @return bool
  */
  protected static function initConnectionImportant(){
    include(cmsCore::getInstance()->config['config']);
    $db_link_important = new mysqli($CONF['import_host'], $CONF['import_user'], $CONF['import_pass'], $CONF['import_name']);
    if ($db_link_important->connect_errno) {
      echo "Failed to connect to MySQL: (" . $db_link_important->connect_errno . ") " . $db_link_important->connect_error;
    }
    $db_link_important->set_charset("utf8");
    return $db_link_important;
  }

  /**
  * Function for send query to Database
  * @param string $sql There is should be SQL query
  * @return array
  */
  public function query($sql){
    include(cmsCore::getInstance()->config['table']);

    $from = $this->getFrom($sql);

    if($mysql[$from] == 'import'){
      $result = mysqli_query($this->db_link_important, $sql);
    }

    if($mysql[$from] == 'non'){
      $result = mysqli_query($this->db_link, $sql);
    }

    $error = $this->error();
    if($error){
        die('<h3>DATABASE ERROR:</h3><pre>'.$sql.'</pre><p>'.$error.'</p>');
    }

    return $result;
  }

  /**
  * Function for get FROM from SQL query
  * @param string $tab
  * @return string
  */
  public function getFrom($sql){
    include($_SERVER['DOCUMENT_ROOT'].'/includes/classes/SQLParser/php-sql-parser.php');

    $parser = new PHPSQLParser();
    $parsed = $parser->parse($sql);

    if(isset($parsed['FROM'][0]['table']) && $parsed['FROM'][0]['table']!=NULL){
      return $parsed['FROM'][0]['table'];
    }

    if(isset($parsed['UPDATE'][0]['table']) && $parsed['UPDATE'][0]['table']!=NULL){
      return $parsed['UPDATE'][0]['table'];
    }

    if(isset($parsed['INSERT']['table']) && $parsed['INSERT']['table']!=NULL){
      return $parsed['INSERT']['table'];
    }

  }

  /**
  * Function for checking prefix of table in database
  * @param string $tab
  * @return boot
  */
  public function checkTable($tab){
    if(preg_match('/^btc_/im', $tab)){
      return $tab;
    }else{
      return false;
    }
  }

  /**
  * Function for insert data to Database
  * @param string $table
  * @param string $insert_array
  * @param string $ignore
  * @return int
  */
  public function insert($table, $insert_array, $ignore=false){

		$insert_array = $this->removeTheMissingCell($table, $insert_array);
		$set = '';

		foreach($insert_array as $field=>$value){
			$set .= "`{$field}` = '{$value}',";
		}

		$set = rtrim($set, ',');
    $i = $ignore ? 'IGNORE' : '';
		$this->query("INSERT {$i} INTO {$table} SET {$set}");

		if ($this->errno()) { return false; }
		return $this->get_last_id($table);
	}

  /**
  * Function for update data in Database
  * @param string $table
  * @param string $update_array
  * @param string $id
  * @return boot
  */
  public function update($table, $update_array, $id){
    if(isset($update_array['id'])){
      unset($update_array['id']);
    }
    // id or where
    if(is_numeric($id)){
      $where = "id = '{$id}' LIMIT 1";
    } else {
      $where = $id;
    }

		// remove unnecessary cells from the array
		$update_array = $this->removeTheMissingCell($table, $update_array);

		$set = '';
		// create a request to insert into the database
		foreach($update_array as $field=>$value){
			$set .= "`{$field}` = '{$value}',";
		}
		// remove the last comma
		$set = rtrim($set, ',');

		$this->query("UPDATE {$table} SET {$set} WHERE $where");
		if ($this->errno()) { return false; }
		return true;
	}

  /**
  * Function for delete data from database
  * @param string $table
  * @param string $where
  * @param string $limit
  * @return boot
  */
  public function delete($table, $where='', $limit=0) {

		$sql = "DELETE FROM {$table} WHERE {$where}";

		if ($limit) { $sql .= " LIMIT {$limit}"; }

		$this->query($sql, true);

		if ($this->errno()){ return false; }

		return true;

	}

  /**
  * Removes cells from the array that are not in the destination table.
  * @param string $table
  * @param string $array
  * @return int
  */
  public function removeTheMissingCell($table, $array){

		$result = $this->query("SHOW COLUMNS FROM `{$table}`");
		$list = array();
        while($data = $this->fetch_assoc($result)){
            $list[$data['Field']] = '';
        }
		// убираем ненужные ячейки массива
		foreach($array as $k=>$v){
		   if (!isset($list[$k])) { unset($array[$k]); }
		}

		if(!$array || !is_array($array)) { return array(); }

		return $array;

	}

  /**
  * Get the last id from database
  * @param string $table
  * @return int
  */
  public function get_last_id($table=''){
    if(!$table){
      return (int)mysqli_insert_id($this->db_link);
    }
    $result = $this->query("SELECT LAST_INSERT_ID() as lastid FROM $table LIMIT 1");

    if($this->num_rows($result)){
      $data = $this->fetch_assoc($result);
      return $data['lastid'];
    } else {
      return 0;
    }
  }

  /**
  * Function for counting rows in the table
  * @param string $table
  * @param string $where
  * @param string $limit
  * @return int
  */
  public function rows_count($table, $where, $limit=0){

    $sql = "SELECT 1 FROM $table WHERE $where";
    if ($limit) { $sql .= " LIMIT ".(int)$limit; }
    $result = $this->query($sql);

    return $this->num_rows($result);
  	}

  /**
  * Get data the one field from table, limit 1
  * @param string $table
  * @param string $where
  * @param string $field
  * @return array
  */
  public function get_field($table, $where, $field){
    $sql    = "SELECT $field as getfield FROM $table WHERE $where LIMIT 1";
    $result = $this->query($sql);

    if ($this->num_rows($result)){
      $data = $this->fetch_assoc($result);
      return $data['getfield'];
    } else {
      return false;
    }
  }

  /**
  * Get data all fields from table, limit 1
  * @param string $table
  * @param string $where
  * @param string $field
  * @param string $order
  * @return array
  */
  public function get_fields($table, $where, $fields='*', $order='id ASC'){
    $sql    = "SELECT $fields FROM $table WHERE $where ORDER BY $order LIMIT 1";
    $result = $this->query($sql);

    if ($this->num_rows($result)){
      $data = $this->fetch_assoc($result);
      return $data;
    } else {
      return false;
    }
  }

  /**
  * Select all table, unlimit
  * @param string $table
  * @param string $where
  * @param string $field
  * @return array
  */
  public function get_table($table, $where='', $fields='*'){

  		$list = array();

  		$sql = "SELECT $fields FROM $table";
  		if ($where) { $sql .= ' WHERE '.$where; }
  		$result = $this->query($sql);

  		if ($this->num_rows($result)){
  			while($data = $this->fetch_assoc($result)){
  				$list[] = $data;
  			}
  			return $list;
  		} else {
  			return false;
  		}

  	}

  // ============================================================================ //
  // ============================================================================ //
    public function num_rows($result){
    	return mysqli_num_rows($result);
    }
  // ============================================================================ //
  // ============================================================================ //
    public function fetch_assoc($result){
    	return mysqli_fetch_assoc($result);
    }
  // ============================================================================ //
  // ============================================================================ //
    public function fetch_row($result){
    	return mysqli_fetch_row($result);
    }

  // ============================================================================ //
  // ============================================================================ //
  	public function errno() {
  		return mysqli_errno($this->db_link);
  	}
  // ============================================================================ //
  // ============================================================================ //
  	public function error() {
  		return mysqli_error($this->db_link);
  	}

}

?>
