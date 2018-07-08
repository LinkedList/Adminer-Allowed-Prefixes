<?php

/** 
* Show only tables with certain prefixes
*
* @author Martin Macko, https://bitbucket.org/linkedlist/
* @license http://http://opensource.org/licenses/MIT, The MIT License (MIT)
*/
class AdminerAllowedPrefixes {
	/** @access protected */
	var $prefixes;
	
	/** 
	* Set allowed prefixes
	* @param array array($server_name => array($database_name => array($prefix))) e.g. array("mysql.server.com" => array("table_1" => array("prefix_1, prefix_2")))
	* @param string
	*/
	function AdminerAllowedPrefixes($prefixes) {
		$server = Adminer::credentials()[0];
		$database = Adminer::database();
		$this->prefixes = $prefixes[$server][$database];
	}

	/** 
	* Prints table list in menu
	* @param array result of table_status('', true)
	* @return null
	*/
	function tablesPrint($tables) {
		if($this->prefixes === null) {
			//didn't find record for this server and database - use default function
			return null;
		}
		echo "<ul id='tables'>" . script("mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});");
		foreach ($tables as $table => $status) {
			$name = $this->tableName($status);
			if($this->strposa($table, $this->prefixes)) {
				echo '<li><a href="' . h(ME) . 'select=' . urlencode($table) . '"' . bold($_GET["select"] == $table || $_GET["edit"] == $table, "select") . ">" . lang('select') . "</a> ";
				echo (support("table") || support("indexes")
					? '<a href="' . h(ME) . 'table=' . urlencode($table) . '"'
						. bold(in_array($table, array($_GET["table"], $_GET["create"], $_GET["indexes"], $_GET["foreign"], $_GET["trigger"])), (is_view($status) ? "view" : "structure"))
						. " title='" . lang('Show structure') . "'>$name</a>"
					: "<span>$name</span>"
				) . "\n";
			}
		}
		echo "</ul>\n";
		//use this instead of the original function
		return true;
	}

	/** Table caption used in navigation and headings
	* @param array result of SHOW TABLE STATUS
	* @return string HTML code, "" to ignore table
	*/
	function tableName($tableStatus) {
		return h($tableStatus["Name"]);
	}

	/** 
	* strpos with array as needle
	* @param string the string to search in
	* @param array array of searched strings
	* @return bool true if at least one string from needle was found in haystack as a prefix
	*/
	function strposa($haystack, $needle, $offset=0) {
		if(!is_array($needle)) $needle = array($needle);
			foreach($needle as $query) {
				if(strpos($haystack, $query, $offset) === 0) return true; // stop on first true result
			}
		return false;
	}
}
