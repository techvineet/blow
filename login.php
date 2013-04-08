<?php
require_once('includes/user.config.inc.php');
require_once('includes/utilities.php');

global $connection;
if(isset($_POST['submit']) &&  $_POST['submit'] == 'Login'){
	$username = smart_quote($_POST['username']);
	$password = md5($_POST['password']);
	$query = "SELECT * FROM users WHERE username= $username AND password = '$password' LIMIT 1";
	$user_row = mysql_query($query);
	if(!$user_row){
		echo "$query Failed ".mysql_error();
	}
	if($user = mysql_fetch_assoc($user_row)){
		session_start();
		$_SESSION['user'] = $user;
		header('Location: index.php');
		exit;
	}
	else{
		$errormsg = 'Invalid Login/Password';
	}
}
?>
<? require_once('includes/header.inc.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
	$("#loginForm").validate({
		errorLabelContainer: $("div.errormsg"),
		wrapper:"li"
	});
});
</script>

<div class="login" align="center">
<h3>Login Credentials</h3>
<div class="errormsg"><?=$errormsg ?></div>
<form id="loginForm" method="post" action="login.php">
	<table cellpadding="2" cellspacing="5" border="0">
	<tr>
	<td>Username<em>*</em> </td><td><input type="text" title="Please enter username(at least 5 characters)" class="required" minlength="5" maxlength="15" name="username" id="username" size="15" maxsize="15" /></td>
	</tr>
	<tr>
	<td>Password<em>*</em> </td><td><input type="password" class="required" name="password" minlength="5" maxlength="8" title="Please enter password (between 5 and 8 characters)"  size="15" maxsize="15" /></td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="center" colspan="2"><input type="submit" name="submit" value="Login" /></td>
	</tr>
	</table>
</form>
</div>
<?require_once('includes/footer.inc.php');  ?>