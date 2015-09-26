<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/functions.php');
?>
<!DOCTYPE html>
<head>
<script src="/components/infusions/portalauth/includes/js/infusion.js" type="text/javascript"></script>
<script>
$('.retrieveLog').on("click",function(){
	var log = $(this).html();
	$.post("/components/infusions/portalauth/functions.php",{retrieveLog:log},function(data){
		if (data == "") {
			popup("Log is empty");
		} else {
			popup(data);
		}
	});
});
$('.deleteLog').on("click",function(){
	var log = $(this);
	var logname = $(this).attr("id");
	$.post("/components/infusions/portalauth/functions.php",{deleteLog:logname},function(data){
		if (data == true) {
			$('span[id="'+logname+'"]').remove();
		}
	});
});
</script>
</head>
<body>
<?
$logdir = __ROOT__.'/includes/logs';
$logs = scandir($logdir);
if (count($logs) <= 2) {
	echo "No logs available";
} else {
	foreach (scandir($logdir) as $log) {
		if ($log == "." || $log == "..") continue;
		echo "<span id='" . $log . "'><a href='#' class='retrieveLog'>" . $log . "</a><a href='#' style='padding-left: 5em' class='deleteLog' id='" . $log . "'>delete</a><hr /></span>";
	}
}
?>
</body>
</html>