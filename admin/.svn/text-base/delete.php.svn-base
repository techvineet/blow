<?php
require_once('includes/config.inc.php');

global $connection;
if(isset($_SESSION['admin_user']) && (int)$_SESSION['admin_user']['id'] > 0){
	if(isset($_GET['test']) && (int)$_GET['test'] > 0){
		$query = 'DELETE t.*, q.*
				FROM tests as t, questions as q
				WHERE t.id = q.test_id AND t.id='.(int)$_GET['test'];
		if(!mysql_query($query)){
			echo 'Error deleting test '.mysql_error();
		}
		
		$_SESSION['message'] = 'Test successfully deleted!';
		header('location:index.php');
	}
}
else{
	header('Location: login.php');
	exit;
}

header('index.php'); exit;
?>