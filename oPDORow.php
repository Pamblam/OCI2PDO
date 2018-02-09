<?php

/**
 * Emulates the PDORow class used by PDO::FETCH_LAZY
 */
class oPDORow{
	public $queryString = "";
	public function __construct($qs){
		$this->queryString = $qs;
	}
}
