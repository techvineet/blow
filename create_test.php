<?php
require_once('config.inc.php');

if($_POST['submit'] == 'Add Test'){
	global $connection;
	
	//@TODO: Server Side Validation 
	$testTitle = smart_quote($_POST['testTitle']);
	$query = "INSERT INTO tests VALUES(DEFAULT, $testTitle, '')";
	if(!mysql_query($query, $connection)){
		echo 'Error saving in tests table '.mysql_error();
		exit;
	}
	$testId = mysql_insert_id($connection);
	
	$idCount = count($_POST['id']);

	$values = array();
	for($j=0; $j < $idCount; $j++){
		
		$i = $_POST['id'][$j];
		$questTitle = smart_quote($_POST['quest'.$i.'title']);
		$questionType = $_POST['questionType'][$j];
		
		switch($questionType){
			case 1:
				$questOptions = array($_POST['quest'.$i.'Option1'], $_POST['quest'.$i.'Option2']);
				
				foreach((array)$_POST['quest'.$i.'optionsId'] as $option){
					$questOptions[] = $_POST['quest'.$i.'Option'.$option];
				}
				
				$serOptions = smart_quote(serialize($questOptions));
				$correctOption = smart_quote(serialize($_POST['quest'.$i.'Correct']));
				$values[] = "(DEFAULT, $testId, $questTitle, '', $questionType, $serOptions, $correctOption)";
			break;
			
			case 2:
				$questOptions = array($_POST['quest'.$i.'Option1'], $_POST['quest'.$i.'Option2']);
				
				foreach((array)$_POST['quest'.$i.'optionsId'] as $option){
					$questOptions[] = $_POST['quest'.$i.'Option'.$option];
				}
				
				$serOptions = smart_quote(serialize($questOptions));
				$correctOption = smart_quote($_POST['quest'.$i.'Correct']);
				$values[] = "(DEFAULT, $testId, $questTitle, '', $questionType, $serOptions, $correctOption)";
			break;
			
			case 3:
				$correctOption = smart_quote($_POST['quest'.$i.'Correct']);
				$values[] = "(DEFAULT, $testId, $questTitle, '', $questionType, NULL, $correctOption)";
			break;
		}
	}
	
	$values = implode(',', $values);
	$query = "INSERT INTO questions VALUES $values";
	if(!mysql_query($query, $connection)){
		echo('Error saving in questions'. mysql_error());
	}
}

echo 'Operation Successful';

?>