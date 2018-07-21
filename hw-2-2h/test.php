<?php 
		$dir_tests = __DIR__ . '/json_tests';
		$test_list = glob("$dir_tests/*.json");
		$info_text = '';
		$info_text_style = '';
		$user_answers = [];
		$errorSum = 0;
		$test_number = $_GET['test_number'];
		krsort($test_list);
		$num_last_test = key($test_list);

		function submit_unset($var)
		{
			if ($var == 'Проверка пройенного теста') {
				return false;
			}
			return true;
		}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Блок теста</title>
	<style>
		form {
			display: inline-block;
		}
	</style>
</head>
<body>
	<?php
	if (isset($test_number) && ($test_number != NULL) && ($test_number <= $num_last_test)) {
		$testArray = json_decode(file_get_contents($test_list[$test_number]), 1);
		$test_title = $testArray['test_name'];
		$test_questions = $testArray['questions'];
	?>	
		<h1><?=$test_title?></h1>

		<form method="POST" action="">
<?php
			foreach ($test_questions as $key_question => $question) {
				$question_title = $question['question_title'];
				$input_type = $question['input_type'];
				$question_variants = $question['variants'];
				$test_right_answers = $question['answers'];
?>
			<fieldset style="<?=$fieldset_style?>">
				<legend><?=$question_title?></legend>
<?php
				foreach ($question_variants as $key_variant => $variant) {
?>
				<label>
					<input name="<?=$key_question?>[]" value="<?=$variant?>" type="<?=$input_type?>">		
				<?=$variant?>
				</label>
	<?php
				}
	?>
			</fieldset>
	<?php 
				if (isset($_POST['check_test'])) {
					if (isset($_POST[$key_question])) {
						$user_answers = array_filter($_POST, 'submit_unset');
						$errorCount = count(array_diff($test_right_answers, $user_answers[$key_question])) + count(array_diff($user_answers[$key_question], $test_right_answers));
						if ($errorCount == 0) {
							$errorCount = false;
						}	
					}
					else {
						$errorCount = count($test_right_answers);
					}
					$errorSum = (is_numeric($errorCount)) ? $errorSum + $errorCount : $errorSum;

					if (!isset($_POST[$key_question]) && $errorSum > 0) {
						$info_text = 'тест пройден, допущено ошибок: ' . $errorSum . ' шт.';
						$info_text_style = 'color: red';
					}
					if (isset($_POST['check_test']) && $errorSum == 0) {
						$info_text = 'тест пройден без ошибок';
						$info_text_style = 'color: green;';
					}
					elseif (isset($_POST['check_test']) && isset($_POST[$key_question]) && $errorSum > 0) {
						$info_text = 'тест пройден, допущено ошибок: ' . $errorSum . ' шт.';
						$info_text_style = 'color: red';
					}					
				}
				elseif (!isset($_POST['check_test']) && $errorSum == 0) {
					$info_text = 'для получения результата теста нужно ответить на вопросы';
					$info_text_style = '';
				}		
			}			
	?>
			<div style="margin-top: 20px">
				<input name="check_test" type="submit" value="Проверить тест">
			</div>
			<p style="<?=$info_text_style?>"><b>Результат теста:</b> <?=$info_text?></p>
		</form>
		<div style="margin-top: 20px;">
			<a href="list.php"><= Вернуться к выбору тестов</a>
		</div>
	<?php
	}
	else {
		echo '<p style="font-size: 20px; color: red;">Тест не найден или указан некорректный номер теста</p>';
		echo '<p><a href="list.php"><= Вернуться к списку тестов</a></p>';
	}
	?>	
</body>
</html>