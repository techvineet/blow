<?php 
require_once('includes/config.inc.php');
require_once('includes/utilities.php');
global $connection;
//print_r($_POST); exit;
if(isset($_SESSION['admin_user']) && (int)$_SESSION['admin_user']['id'] > 0){
	if($_POST['submit'] == 'Add Test' && (int)$_POST['testWeight'] > 0){
		$testTitle = smart_quote($_POST['testTitle']);
		$questionsCount = (int)$_POST['testWeight'];
		
		$query = "INSERT INTO tests VALUES(DEFAULT, $testTitle, '', $questionsCount, DEFAULT)";
		
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
		
		$_SESSION['message'] = 'Test Successfully Added!';
		header('Location: index.php');
		exit;
	}
}
else{
	header('Location: login.php');
	exit;
}
?>
<? require_once('includes/header.inc.php'); ?>
<script type="text/javascript" src="<?=$userHostName ?>/js/common/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="<?=$userHostName ?>/js/common/jquery/ui/ui.sortable.js"></script>
<script type="text/javascript" src="<?=$userHostName ?>/js/common/jquery/checkboxes.js"></script>
<script type="text/javascript" src="js/questions_ui.js"></script>
<div class="content">
<a href="index.php">Go Back</a>
	<h3 class="title">Create Test</h3> 
	<div class="main">
	<div class="questToolbar">
		<div class="question" id="1">
			Q1
		</div>
		<div class="question" id="2">
			Q2
		</div>
		<div class="question" id="3">
			Q3
		</div>
		<div class="question" id="q4">
			Q4
		</div>
		<div class="question" id="q5">
			Q5
		</div>
	</div>
	<form id="questForm" method="post">
	<div class="testCanvasMain" align="center">
		<div class="canvasHeading" align="left">
			<div style="float:right;">Number of Question(s) Added: <span id="questionCount" style="font-weight: bold;"></span><input type="hidden" id="testWeight" name="testWeight" value="0" /></div>
			Test Title: <input type="text" id="testTitle" maxLength="100" size="50" name="testTitle" />
		</div>
		<div id="testCanvas" align="left">
			Drag and Drop Questions Here !!!
		</div>
		<div><input type="submit" id="sbtButton" name="submit" value="Add Test" /></div>
	</div>
	<div style="clear:both;"></div>
	</form>
	</div>
</div>
<?
require_once('includes/footer.inc.php');
?>