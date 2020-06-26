<?php return;?>
<?php
//修复有solution但是没有source_code的提交
//增加一行空代码，防止一直在编译的状况
require_once('../include/db_info.inc.php');

$sql = <<<SQL
	SELECT solution_id FROM solution 
	WHERE solution_id IN (SELECT solution_id FROM solution) 
	AND solution_id NOT IN (SELECT solution_id FROM source_code);
SQL;

	$res = $mysqli->query($sql) or die($mysqli->error);
	while ($row = $res->fetch_object()) {
		$solution_id = $row->solution_id;
		$sql = <<<SQL
			INSERT INTO source_code 
			VALUES (
				$solution_id,
				""
			)
SQL;
		$mysqli->query($sql) or die($mysqli->error);
	}

echo "DONE!";
?>
