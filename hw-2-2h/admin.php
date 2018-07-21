
<?php
$info_style = '';
$info_text = '';
$dir_tests = __DIR__ . '/json_tests';
$test_list = glob("$dir_tests/*.json");
$test_count = count($test_list);
$test_correct_keys = ['test_name', 'questions'];

if (isset($_FILES['test_file']) && !empty($_FILES['test_file']['name'])) {
	$file = $_FILES['test_file'];
	$file_name = ($test_count + 1) . '.json';
	$test_array = json_decode(file_get_contents($file['tmp_name']), 1);
	$test_array_keys = array_keys($test_array);

	if ($file['type'] !== 'application/json') {
		$info_style = 'color: red';
		$info_text = 'Файл не был загружен, так как другой формат';
	}
	elseif ($file['size'] > 2900000) {
		$info_style = 'color: red';
		$info_text = 'Файл не загружен, причиной являеться размер так как он больше >2.9';
	}
	elseif ($test_array_keys !== $test_correct_keys) {
		$info_style = 'color: red';
		$info_text = 'Файл не загружен, т.к. имеет не правильную структуру';
	}
	elseif ($file['error'] !== UPLOAD_ERR_OK) {
		$info_style = 'color: red';
		$info_text = 'Произошла ошибка загрузки файла, попробуйте еще раз';
	}
	elseif (move_uploaded_file($file['tmp_name'], __DIR__ . "/json_tests/$file_name")) {
		$info_style = 'color: green';
		$info_text = 'Файл успешно загружен';
	}
}	
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Загрузки тестов</title>

	<style>
		form {
			display: inline-block;
		}
	</style>
</head>
<body>
	<h1>Загрузки тестов</h1>
	<p>Файл теста для загрузки в формате JSON: <a href="./json_example/test2.json" download="">Предлгаемый тест программой</a></p>
	<form method="POST" action="" enctype="multipart/form-data">
		<fieldset>
			<legend>Форма загрузки файлов</legend>
			   <br />
			<label>
				Файл:
				<input name="test_file" type="file">
			</label>
			<div style="margin-top: 50px">
				<input name="load_file" type="submit" value="Загрузить тест">
			</div>
			
			<p style="<?=$info_style?>"><?=$info_text?></p>
		</fieldset>
	</form>
	<div style="margin-top: 40px">
		<a href="list.php">Cписок тестов/нажми чтобы отобразить список</a>
	</div>
</body>
</html>
