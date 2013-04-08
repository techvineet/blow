<?php
require_once('includes/user.config.inc.php');
require_once('includes/utilities.php');
global $connection;

	$query = "SELECT id, type, answers FROM questions WHERE test_id = {$_SESSION['test']['id']} ORDER BY id ASC";
	if(!$data = mysql_query($query)){
		echo "Could not successfully run query ($query) from DB: " . mysql_error();
		exit;
	}
	
	$userAnswers = unserialize('a:4:{i:11;a:3:{i:0;s:1:"1";i:1;s:1:"3";i:2;s:1:"2";}i:12;a:1:{i:0;s:1:"0";}i:13;s:1:"1";i:14;s:8:"Pakistan";}');
	
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
	
	echo "correct answers are $correct";
?>