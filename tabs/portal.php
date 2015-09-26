<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/functions.php');
global $configs;
$noportal = "<h1 style='color: #34D134;text-align:center;'>No Captive Portal Detected</h1>";
?>
<html>
<head>
<script src="/components/infusions/portalauth/includes/js/infusion.js" type="text/javascript"></script>
<script type="text/javascript">
window.onload = setTimeout(requestUpdate, 10);
function requestUpdate() {
	$('#iframeLoading').show();
	$.post("/components/infusions/portalauth/functions.php", {updateStatus:""},function(data){
		$('#iframeLoading').hide();
		if (data == true) {
			$('iframe').attr("src", "<? echo $configs['testSite']; ?>");
			$('iframe').load(function(){
				$('iframe').show();
			});
		} else if (data == false) {
			$('#iframeDiv').html("<? echo $noportal; ?>");
		}
		$('#iframeDiv').show();
	});
	// For some reason when the large tile is loaded and closed it doesn't allow
	// the "Check for Portal" button function.  Refreshing the small tile restores
	// functionality.
	refresh_small("portalauth");
}
</script>
</head>
<body>
<div id="iframeDiv"><iframe height='100%' width='100%'></iframe></div>
<div id="iframeLoading"><h1 style='color: #34D134;text-align:center;'>Searching for Captive Portal...</h1><br /><progress style="width: 50%"></progress></div>
</body>
</html>