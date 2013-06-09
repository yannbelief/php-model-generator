<?php
  /** PHP Model Generator is a command line tool aids to generate a domain model class according to the customized configuration file which user wrote.
* @link http://prose.io/#yannbelief/php-model-generator/
* @author CHEN Yen Ming https://github.com/yannbelief/
* @copyright 2013 CHEN Yen Ming
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
  
  
function genQuestionMark($count){
	if($count == 0) return "";
	$str = "?";
	for($i = 1; $i < $count; $i++)
		$str.=",?";
	return $str;
}

function genList($arr,$prefix="",$suffix="",$excludedValue = NULL) {
	$count = count($arr);
	$str="";
	if($count == 0) return "";
	for($i = 0; $i < $count; $i++) {
		if(strcmp($excludedValue,$arr[$i]) == 0) {
			continue;
			//echo "EXCLUDED ".$arr[$i];
		}
		if(strlen($str) != 0) $str .= ",";
		$str.=$prefix.trim($arr[$i]).$suffix;
	}
	return $str;
}

function split_inner_str($arr,$part = 0,$spliter = ",",$copyPrevExistEle = true) {
	$o = array();
	foreach($arr as $str) {
		$s = explode($spliter, $str);
		if(count($s) == 0) continue;
		if($part == 0)
			$o[] = trim($s[0]);
		else if (count($s) == 1)
			if($copyPrevExistEle)
				$o[] = trim($s[0]);
			else
				$o[] = "";
		else
			$o[] = trim($s[1]);
	}
	return $o;
}

function genHash($keys,$values){
	$o = array();
	for($i = 0;$i <count($keys);$i++) {
		$o[$keys[$i]] = $values[$i];
	}
	return $o;
}



function genMethodName($cmd, $select_attrs, $where_attrs){
	$name = $cmd;
	if(isset($select_attrs) && count($select_attrs) > 0)
		$name.="_".join("_and_",$select_attrs);
	if(isset($where_attrs) && count($where_attrs) > 0)
		$name.="_by_".join("_and_",$where_attrs);
	return $name;

}

function genArguments($where_attrs) {

	return genList($where_attrs,"$");

}

function isMatched($subject, $cmd){
	$pattern = "/^$cmd/";
	return preg_match($pattern, $subject);
}

function extractSelectArray($subject,&$out_select_attrs){
	$parts = trim_arr(explode("by",$subject));
	$front = $parts[0];
	//$front = str_replace("find all","",$front);
	$front = str_replace("find 1","",$front);
	$front = str_replace("find","",$front);
	$front = trim($front);

	$hasSelectedAttrs = ($front != "");

	if($hasSelectedAttrs) {
	
		$out_select_attrs = trim_arr(explode(",",$front));
	}

	return $hasSelectedAttrs;

}

function extractWhereArray($subject,&$out_where_attrs){
	$out_where_attrs = array();
	$pattern = "/by ([\w,\s]+)/";
	$isMatched = preg_match($pattern, $subject,$matches);
	
	if(isset($matches[1])) {
		$where_attr_str = $matches[1];
		$out_where_attrs = trim_arr(explode(",",$where_attr_str));
	}
	return $isMatched;
}

function trim_arr($arr) {
	$o = array();
	foreach($arr as $ele) {
		$o[] = trim($ele);
	}
	return $o;
}

function map($keys,$key2ValueHash) {
	$o = [];
	foreach($keys as $key) {
		if(isset($key2ValueHash[$key]))
			$o[] = $key2ValueHash[$key];
		else
			 throw new Exception("Mapping Error: Key $key is not found its value in list: ".print_r($key2ValueHash,true));
	}
	return $o;
}

function genSelectedAttrList($select_attrs) {
	if(count($select_attrs) == 0) return " * ";
	return " ".genList($select_attrs,"`","`");
}

function genWhereClause($where_attrs) {
	if(count($where_attrs) == 0) return "";
	return " WHERE ".genList($where_attrs,"`","` = ?");
}

function genInnerMethodCall($isFind1,$select_attrs) {
	$select_count = count($select_attrs);
	if($isFind1 && $select_count == 0) // select all attrs
		return "fetchOneObj";

	if($isFind1 == false && $select_count == 0) // select all attrs
		return "fetch";
	
	if($isFind1 && $select_count == 1) // select 1 attrs
		return "fetchOneVal";

	if($isFind1 == false && $select_count == 1) // select 1 attrs
		return "fetchOneColArr";

	if($isFind1 && $select_count > 0) // select some attrs
		return "fetchOneObj";

	if($isFind1 == false && $select_count > 0) // select some attrs
		return "fetch";
}

function genModelConversionCall($isFind1,$select_attrs) {
	$select_count = count($select_attrs);
	if($isFind1 && $select_count == 0) // select all attrs
		return "self::model";

	if($isFind1 == false && $select_count == 0) // select all attrs
		return "self::models";
	
	if($isFind1 && $select_count == 1) // select 1 attrs
		return "";

	if($isFind1 == false && $select_count == 1) // select 1 attrs
		return "";

	if($isFind1 && $select_count > 0) // select some attrs
		return "self::model";

	if($isFind1 == false && $select_count > 0) // select some attrs
		return "self::models";

}

?>
