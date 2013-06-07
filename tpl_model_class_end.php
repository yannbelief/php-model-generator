	static function models($rows) {
		$objs = array();
		foreach($rows as $row) {
			$o = new <?=$Tbl?>();
<? for($i = 0; $i < count($attrs); $i++) { ?>
			if(isset($row-><?=$fields[$i]?>))
				$o-><?=$attrs[$i]?> = $row-><?=$fields[$i]?>;
<? } ?>
			$objs[] = $o;			
		}		
		return $objs;
	}

	static function model($row) {
		$o = new <?=$Tbl?>();
<? for($i = 0; $i < count($attrs); $i++) { ?>
		if(isset($row-><?=$fields[$i]?>))
			$o-><?=$attrs[$i]?> = $row-><?=$fields[$i]?>;
<? } ?>
		return $o;
	}
}
<?php echo "?>"; ?>
