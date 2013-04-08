<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title><?= $pageTitle ? $pageTitle : 'Online Test' ?></title>

<!-- BEGIN STYLES SECTION -->
<link href="css/application.css" media="screen" rel="stylesheet" type="text/css" />
<style>
</style>
<!-- END STYLES SECTION -->

<script type="text/javascript" src="<?=$userHostName ?>/js/common/jquery/jquery.js"></script>
<script type="text/javascript" src="<?=$userHostName ?>/js/common/jquery/ui/ui.core.js"></script>
<script type="text/javascript" src="<?=$userHostName ?>/js/common/jquery/validate/jquery.validate.js"></script>

<body >
<div id="super_container">
	<div id="header">
		<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
		<td width="70%" rowspan="2">
			<img src="images/logo4.jpg" border="0" />
		</td>
		</tr>
		<?if(isset($_SESSION['admin_user']) && (int)$_SESSION['admin_user']['id'] > 0): ?>
		<tr style="vertical-align: top;">
		<td align="right" width="25%" >
			Welcome: <b><?=$_SESSION['admin_user']['first_name'] ?> <?=$_SESSION['admin_user']['last_name'] ?></b> |
		</td>
		<td align="right" width="5%">
			<a href="logout.php" >Logout</a>			
		</td>
		</tr>
		<?endif; ?>
		</table>
	</div>
<!-- Header ENDS -->

