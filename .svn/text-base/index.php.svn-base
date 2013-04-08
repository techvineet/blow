<?php
require_once('includes/user.config.inc.php');
require_once('includes/utilities.php');
global $connection;

if(isset($_SESSION['user']) && (int)$_SESSION['user']['id'] > 0){
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
<h3>User Section</h3>
<span><?=$message ?></span>
<? if(mysql_num_rows($tests) > 0): ?>
<p>Tests Available</p>
<table border="0" cellpadding="5" cellspacing="2" width="50%">
<tr align="left">
	<th width="40%">Title</th>
	<th>&nbsp;</th>
</tr>
<?while ($test_row = mysql_fetch_assoc($tests)): ?>
	<tr><td><?=$test_row['title'] ?></td><td><a href="take_test.php?test=<?=$test_row['id'] ?>">Take Test<a></td></tr>
<?endwhile; ?>
</table>
<? endif; ?>
</div>
<? require_once('includes/footer.inc.php'); ?>