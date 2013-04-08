<?php
require_once('includes/user.config.inc.php');
require_once('includes/utilities.php');
global $connection;

$test_id = (int)$_SESSION['test_id'];

if(isset($_SESSION['user']) && (int)$_SESSION['user']['id'] > 0 && $test_id > 0){
	$query = "SELECT tests.questions_count, users_tests.correct FROM users_tests, tests WHERE tests.id = $test_id AND users_tests.test_id = $test_id";
	if(!$data = mysql_query($query)){
		echo "Could not successfully run query ($query) from DB: " . mysql_error();
		exit;
	}
	
	$params = mysql_fetch_assoc($data);
}
else{
	header('Location: login.php');
	exit;
}

?>
<? require_once('includes/header.inc.php'); ?>
<div class="content">
Your Result is <?=$params['correct'] ?> out of <?=$params['questions_count'] ?> 
</div>
<? require_once('includes/footer.inc.php'); ?>