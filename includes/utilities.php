<?php
function smart_quote($value, $addSlashes = true){
	if($addSlahes && !get_magic_quotes_gpc()) {
		return "'addslashes($value);'";
	} 
	return "'$value'";
}
?>