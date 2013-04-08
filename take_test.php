<?php
require_once('includes/user.config.inc.php');
require_once('includes/utilities.php');
global $connection;

$test_id = (int)$_GET['test'];

if(isset($_SESSION['user']) && (int)$_SESSION['user']['id'] > 0){
	$finish = $_POST['submit'] == 'Finish';
	$submit = $_POST['submit'] == 'Next' || $finish;
	
	if($test_id > 0 && !$submit && !$finish && !isset($_SESSION['test']) && !isset($_SESSION['question_ids'])){
		$query = "SELECT * FROM tests WHERE id = $test_id LIMIT 1";
		$testData = mysql_query($query, $connection);
		
		if (!$testData) {
		    echo "Could not successfully run query ($query) from DB: " . mysql_error();
		    exit;
		}
		
		if($test = mysql_fetch_assoc($testData)){
			$_SESSION['test'] = $test;
		}
		else{
			echo 'No Test Found';
			exit;
		}
		
		$query = "SELECT id FROM questions WHERE test_id = $test_id ORDER BY id ASC";
		$questionsData = mysql_query($query, $connection);
		if(!$questionsData) {
		    echo "Could not successfully run query ($query) from DB: " . mysql_error();
		    exit;
		}
		
		$_SESSION['question_ids'] = array();
		while($question_ids = mysql_fetch_assoc($questionsData)){
			$_SESSION['question_ids'][]  = $question_ids['id'];
		}
		
		$_SESSION['current_id'] = $_SESSION['question_ids'][0];
		$questionNumber = 0;
	}
	elseif($submit){
		$question_id = (int)$_POST['question_id'];
		$questionNumber = (int)$_POST['questionNumber'];
		$answer = $_POST["quest{$question_id}answer"];
		$questionNumber++;
		$_SESSION['current_id'] = $_SESSION['question_ids'][$questionNumber];
		
		$query = "SELECT answers FROM users_tests WHERE test_id = ".$_SESSION['test']['id']." AND user_id = ".$_SESSION['user']['id'];
		if(!$data = mysql_query($query)){
			echo "Could not successfully run query ($query) from DB: " . mysql_error();
		    exit;
		}
		
		$answers = array();
		
		if($question = mysql_fetch_assoc($data)){
			$answers = unserialize($question['answers']);
		}
		
		$answers[$question_id] = $answer;
		$answers = serialize($answers);
			
		if($questionNumber == 1){
			$query = "INSERT INTO users_tests VALUES({$_SESSION['user']['id']}, {$_SESSION['test']['id']}, NULL, '{$answers}', DEFAULT, DEFAULT)";
		}
		else{
			$query = "UPDATE users_tests SET answers = '$answers' WHERE user_id = {$_SESSION['user']['id']} AND test_id = {$_SESSION['test']['id']}";
		}
		
		if(!mysql_query($query)){
			echo "Could not successfully run query ($query) from DB: " . mysql_error();
		    exit;
		}
		
		if($finish){
			$query = "SELECT id, type, answers FROM questions WHERE test_id = {$_SESSION['test']['id']} ORDER BY id ASC";
			if(!$data = mysql_query($query)){
				echo "Could not successfully run query ($query) from DB: " . mysql_error();
				exit;
			}
			
			$userAnswers = unserialize($answers);
			
			$correct = 0;
			
			//calculate result
			while($correctAnswers = mysql_fetch_assoc($data)){
				switch($correctAnswers['type']){
					case 1:
						$answer = unserialize($correctAnswers['answers']);	
					break;
					default:
						$answer = $correctAnswers['answers'];
					break;
				}
				
				if($userAnswers[$correctAnswers['id']] == $answer){
					$correct++;
				}	
			}
			
			$query = "UPDATE users_tests SET correct = $correct, is_complete = 1 WHERE user_id = {$_SESSION['user']['id']} AND test_id = {$_SESSION['test']['id']}";
			if(!mysql_query($query)){
				echo "Could not successfully run query ($query) from DB: " . mysql_error();
		    	exit;	
			}
			$_SESSION['test_id'] = $_SESSION['test']['id'];
			unset($_SESSION['test'], $_SESSION['question_ids'], $_SESSION['current_id']);
			header('Location: result.php');
			exit;
		}
		
	}
	
	if((int)$_SESSION['current_id'] < 1){
		echo 'Invalid Question ID';
		exit;
	}
	
	$submit = 'Next';
	if(!isset($questionNumber)){
		$questionNumber = array_keys($_SESSION['question_ids'], $_SESSION['current_id']);
		$questionNumber = $questionNumber[0];
		$questionNumber;
	}
	
	if(($questionNumber + 1) == (int)$_SESSION['test']['questions_count']){
		$submit = 'Finish';
	}
	
	$query = "SELECT * FROM questions WHERE id=".(int)$_SESSION['current_id'];
	$questionData = mysql_query($query);
	if(!$questionData) {
	    echo "Could not successfully run query ($query) from DB: " . mysql_error();
	    exit;
	}
	
	$pageTitle = $_SESSION['test']['title'];
}
else{
	header('Location: login.php');
	exit;
}

?>
<? require_once('includes/header.inc.php'); ?>
<div class="content">
<p>Question <?=$questionNumber+1 ?> Of <?=$_SESSION['test']['questions_count'] ?></p>
<form action="take_test.php" method="post">
<? if($questionRow = mysql_fetch_assoc($questionData)): ?>
<input type="hidden" name="question_id" value="<?=$questionRow['id'] ?>" />
<input type="hidden" name="questionNumber" value="<?=$questionNumber ?>" />
<?
	switch($questionRow['type']){
	case 2:
		$questionOptions = unserialize($questionRow['options']);
		$optionsCount = count($questionOptions); 
?>
	<div class="questionTitle">
		<?=$questionRow['title'] ?>
    </div>
    <div class="questionOptionLeft">
    <?  for($i=0; $i < $optionsCount; $i += 2): ?>
    	 <p class="option"><input type="radio" name="quest<?=$questionRow['id'] ?>answer" value="<?=$i ?>" /><?=$questionOptions[$i] ?></p>
    <? endfor; ?>
    </div>
	<div class="questionOptionRight">
		<?  for($i=1; $i < $optionsCount; $i += 2): ?>
    	 <p class="option"><input type="radio" name="quest<?=$questionRow['id'] ?>answer" value="<?=$i ?>" /><?=$questionOptions[$i] ?></p>
    	<? endfor; ?>
	</div>
	<div style="clear:both"></div>
<?
	break;
	
	case 1:
		$questionOptions = unserialize($questionRow['options']);
		$optionsCount = count($questionOptions); 
?>
	<div class="questionTitle">
		<?=$questionRow['title'] ?>
    </div>
    <div class="questionOptionLeft" style="float:left;">
    <?  for($i=0; $i < $optionsCount; $i += 2): ?>
    	 <p class="option"><?=$questionOptions[$i] ?><input type="checkbox" name="quest<?=$questionRow['id'] ?>answer[]" value="<?=$i ?>" /></p>
    <? endfor; ?>
    </div>
	<div class="questionOptionRight" style="float:left; margin-left:15px">
		<?  for($i=1; $i < $optionsCount; $i += 2): ?>
    	 <p class="option"><?=$questionOptions[$i] ?><input type="checkbox" name="quest<?=$questionRow['id'] ?>answer[]" value="<?=$i ?>" /></p>
    	<? endfor; ?>
	</div>
	<div style="clear:both"></div>
    <? break; 
	
    case 3:
    $question = $questionRow['id'];
?>
	<div class="questionTitle">
		<?=str_replace('____',"<input class='blank' type='text' name='quest{$questionRow['id']}answer' />", $questionRow['title']); ?>
    </div>
<?
    break;
	}
	endif; ?>

    <input type="submit" name="submit" value="<?=$submit ?>" />
</form>
</div>
<? require_once('includes/footer.inc.php'); ?>


