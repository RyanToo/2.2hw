<?php
 $dir_tests = __DIR__ . '/json_tests';
 $test_list = glob("$dir_tests/*.json");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Список загруженных тестов</title>
</head>
<body>
	<h1>Список загруженных на сервер тестов</h1>

	<ul>
		<?php
		foreach ($test_list as $key => $test) {
    		$test_info = json_decode(file_get_contents($test), 1);
    		$test_name = $test_info['test_name'];
    		echo "<li><a href=\"test.php?test_number=$key\">" . $test_name . "</a></li>";
		}
		?>
	</ul>
    <div style="margin-top: 20px">
        <a href="admin.php"><= Загрузить новый тест</a>
    </div>
</body>
</html>