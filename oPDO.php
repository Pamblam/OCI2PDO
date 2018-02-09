<?php

define("OPDO_PATH", realpath(dirname(__FILE__)));
require OPDO_PATH."/oPDOStatement.php";
require OPDO_PATH."/oPDORow.php";

/**
 * A wrapper for OCI8 that looks, feels and smells like standard PDO
 */
class oPDO{
	
	/**
	 * PDO constants as defined here:
	 * http://php.net/manual/en/pdo.constants.php
	 */
	const PARAM_BOOL = 5;
	const PARAM_NULL = 0;
	const PARAM_INT = 1;
	const PARAM_STR = 2;
	const PARAM_LOB = 3;
	const PARAM_STMT = 4;
	const PARAM_INPUT_OUTPUT = -2147483648;
	const FETCH_LAZY = 1;
	const FETCH_ASSOC = 2;
	const FETCH_NAMED = 11;
	const FETCH_NUM = 3;
	const FETCH_BOTH = 4;
	const FETCH_OBJ = 5;
	const FETCH_BOUND = 6;
	const FETCH_COLUMN = 7;
	const FETCH_CLASS = 8;
	const FETCH_INTO = 9;
	const FETCH_FUNC = 10;
	const FETCH_GROUP = 65536;
	const FETCH_UNIQUE = 196608;
	const FETCH_KEY_PAIR = 12;
	const FETCH_CLASSTYPE = 262144;
	const FETCH_SERIALIZE = 524288;
	const FETCH_PROPS_LATE = 1048576;
	const ATTR_AUTOCOMMIT = 0;
	const ATTR_PREFETCH = 1;
	const ATTR_TIMEOUT = 2;
	const ATTR_ERRMODE = 3;
	const ATTR_SERVER_VERSION = 4;
	const ATTR_CLIENT_VERSION = 5;
	const ATTR_SERVER_INFO = 6;
	const ATTR_CONNECTION_STATUS = 7;
	const ATTR_CASE = 8;
	const ATTR_CURSOR_NAME = 9;
	const ATTR_CURSOR = 10;
	const ATTR_DRIVER_NAME = 16;
	const ATTR_ORACLE_NULLS = 11;
	const ATTR_PERSISTENT = 12;
	const ATTR_STATEMENT_CLASS = 13;
	const ATTR_FETCH_CATALOG_NAMES = 15;
	const ATTR_FETCH_TABLE_NAMES = 14;
	const ATTR_STRINGIFY_FETCHES = 17;
	const ATTR_MAX_COLUMN_LEN = 18;
	const ATTR_DEFAULT_FETCH_MODE = 19;
	const ATTR_EMULATE_PREPARES = 20;
	const ERRMODE_SILENT = 0;
	const ERRMODE_WARNING = 1;
	const ERRMODE_EXCEPTION = 2;
	const CASE_NATURAL = 0;
	const CASE_LOWER = 2;
	const CASE_UPPER = 1;
	const NULL_NATURAL = 0;
	const NULL_EMPTY_STRING = 1;
	const NULL_TO_STRING = 2;
	const FETCH_ORI_NEXT = 0;
	const FETCH_ORI_PRIOR = 1;
	const FETCH_ORI_FIRST = 2;
	const FETCH_ORI_LAST = 3;
	const FETCH_ORI_ABS = 4;
	const FETCH_ORI_REL = 5;
	const CURSOR_FWDONLY = 0;
	const CURSOR_SCROLL = 1;
	const ERR_NONE = 00000;
	const PARAM_EVT_ALLOC = 0;
	const PARAM_EVT_FREE = 1;
	const PARAM_EVT_EXEC_PRE = 2;
	const PARAM_EVT_EXEC_POST = 3;
	const PARAM_EVT_FETCH_PRE = 4;
	const PARAM_EVT_FETCH_POST = 5;
	const PARAM_EVT_NORMALIZE = 6;

	/**
	 * Private OCI connection
	 * @var OCI Connection identifier 
	 */
	private $ociConnection;
	
	/**
	 * Unlike PDO, DSN may only be a standard Oracle DSN, no URI or aliases allowed
	 * Also, $user and $pass are not optional like in PDO
	 * @param String $dsn - The full Data Source Name, ie: oci:dbname=[//]host_name[:port][/service_name]
	 * @param String $user - The database user name
	 * @param String $pass - The database user password
	 */
	public function __construct($dsn, $user, $pass){
		if(strpos($dsn, "oci:dbname=") !== 0) $this->error();
		$dsn = explode("=", $dsn);
		$dsn = $dsn[1];
		$this->ociConnection = oci_connect($user, $pass, $dsn);
		if(!$this->ociConnection) $this->error();
	}
	
	/**
	 * Query the DB
	 * Does not currently support FETCH_MODE parameters
	 * @param String $statement - The SQL to execute
	 */
	public function query($statement){
		$stmt = new oPDOStatement($this->ociConnection, $statement);
		$stmt->execute();
		return $stmt;
	}
	
	public function prepare($stmt){
		$stmt = new oPDOStatement($this->ociConnection, $statement);
		return $stmt;
	}
	
	/**
	 * Convenience method to throw an OCI related error
	 */
	private function error(){
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
}