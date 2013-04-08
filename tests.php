<?php
require_once('config.inc.php');	
$query = "SELECT * FROM tests";
$tests = mysql_query($query);
?>

<html>
<head>
<title>Test</title>
<!-- <link href="application.css" media="screen" rel="stylesheet" type="text/css" /> -->
<style>
p.option{
    margin-top:5px;
    margin-bottom:5px;
}

.question{
    min-height:85px;
}

hr{
    width:75%;
}

.questionTitle{
    margin-bottom:10px;
}
</style>
<body>
<h3>Tests Available</h3>
<?while ($test_row = mysql_fetch_assoc($tests)): ?>
	<p><a href="take_test.php?test=<?=$test_row['id'] ?>"><?=$test_row['title'] ?><a></p>
<?endwhile; ?>
</body>
</html>