<?
/** PHP Model Generator is a command line tool aids to generate a domain model class according to the customized configuration file which user wrote.
* @link https://github.com/yannbelief/php-model-generator
* @author CHEN Yen Ming https://github.com/yannbelief/
* @copyright 2013 CHEN Yen Ming
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
  
error_reporting(E_ALL);
ini_set('display_errors', True);


if(isset($argv[1]) == false) {
	die("Please supply a path to config file in argument\n");
}

$config_file = $argv[1];

require($config_file);

//----Check config file

if(isset($table) == false) {
	die('Please declare $table variable');
}

if(isset($columns) == false) {
	die('Please declare $columns variable');
}

if(isset($methods) == false) {
	$methods = "";
}


require("util.php");

//----Parse table definition

$Tbl = split_inner_str([$table],0)[0];
$tbl = split_inner_str([$table],1)[0];


//----Parse columns definition

$arr0 = explode("\n",$columns);

$fn0 = split_inner_str($arr0,0,"@");
$defaultVals = split_inner_str($arr0,1,"@",false);


$attrs = split_inner_str($fn0,0);
$fields = split_inner_str($fn0,1);

$attr2fields = genHash($attrs, $fields);


//----Parse methods definition

$arr0 = explode("\n",$methods);

//----Render class starting enclosure

require("tpl_model_class_start.php");

//----Render user-specific functions

foreach($arr0 as $subject) {
	if(isMatched($subject, "find")) {
		$where_attrs = array();
		$select_attrs = array();
		$isFind1 = isMatched($subject, "find 1");

		$cmd = $isFind1?"find_1":"find";

		extractWhereArray($subject,$where_attrs);
		extractSelectArray($subject,$select_attrs);
		/*
		if(extractWhereArray($subject,$where_attrs))
			print_r($where_attrs);
		if(extractSelectArray($subject,$select_attrs))
			print_r($select_attrs);
		*/
		$methodName = genMethodName($cmd, $select_attrs, $where_attrs);
		$argumentList = genArguments($where_attrs);
		$selectList = genSelectedAttrList(map($select_attrs, $attr2fields));
		$whereList = genWhereClause(map($where_attrs, $attr2fields));
		$dbMethodCall = genInnerMethodCall($isFind1, $select_attrs);
		$ModelConversionMethodCall = genModelConversionCall($isFind1, $select_attrs);
?>
	static function <?=$methodName?>(<?=$argumentList?>) {
		$sql = "SELECT <?=$selectList?> FROM `<?=$tbl?>` <?=$whereList?>";
		return <?=$ModelConversionMethodCall?>(DB::instance()-><?=$dbMethodCall?>($sql,[<?=$argumentList?>]));
	}
<?


		//echo "GEN METHOD: ".genMethodName($cmd,$select_attrs,$where_attrs)."<br/>\n";
		//echo "GEN ARGUEMENTS:".genArguments($select_attrs)."<br/>\n";
	}
}

//---Render class ending enclosure

require("tpl_model_class_end.php");

?>

