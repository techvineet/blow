<?php
require_once('includes/config.inc.php');
require_once('includes/utilities.php');
global $connection;

if(isset($_SESSION['admin_user']) && (int)$_SESSION['admin_user']['id'] > 0){
	$query = "SELECT * FROM tests";
	$tests = mysql_query($query);
	$message = $_SESSION['message'];
	unset($_SESSION['message']);
}
else{
	header('Location: login.php');
	exit;
}
?>
<? require_once('includes/header.inc.php'); ?>
<div class="content">
<h3>Admin Section</h3>
<span><?=$message ?></span>
<p><a href="create_test.php">Create Test</a></p>
<? if(mysql_num_rows($tests) > 0): ?>
<p>Tests Available</p>
<table border="0" cellpadding="" cellspacing="" width="50%">
<tr align="left">
	<th width="40%">Title</th>
	<th widht="20%">Passed</th>
	<th width="20%">Failed</th>
	<th width="20%">&nbsp;</th>
</tr>
<?while ($test_row = mysql_fetch_assoc($tests)): ?>
	<tr align="left"><td><a href="take_test.php?test=<?=$test_row['id'] ?>"><?=$test_row['title'] ?><a></td><td>50%</td><td>50%</td><td><a href="delete.php?test=<?=$test_row['id']?>">Delete</a></td></tr>
<?endwhile; ?>
</table>
<? endif; ?>
</div>
<? require_once('includes/footer.inc.php'); ?>