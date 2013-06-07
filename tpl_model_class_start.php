<?php echo "<?php \n"; ?>
class <?=$Tbl?> {
<?php for($i = 0; $i <count($attrs) ;$i++) {
 ?>
	var $<?=$attrs[$i]?><?if($defaultVals[$i]!="") echo " = ".$defaultVals[$i];?>;
<?}?>

	static function insert(<?=$Tbl?> $o) {
		$sql = "INSERT INTO `<?=$tbl?>` (<?=genList($fields,"`","`")?>) VALUES (<?=genQuestionMark(count($attrs))?>);";
		$o->id = DB::instance()->insert($sql,array(<?=genList($attrs,'$o->')?>));
		return $o->id;
	}

	static function update(<?=$Tbl?> $o) {
		$sql = "UPDATE `<?=$tbl?>` SET <?=genList($fields,"`","` = ?","id")?> WHERE `id` = ?";
		return DB::instance()->execute($sql, array(<?=genList($attrs,'$o->',"","id")?>,$o->id));
	}

