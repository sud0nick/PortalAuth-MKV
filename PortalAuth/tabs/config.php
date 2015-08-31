<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/functions.php');
global $rel_dir;
?>
<html>
<head>
<script type="text/javascript">
function updateConfigData() {
	var updateSite=$('#testSite').val();
	var updateExpectedData=$('#dataExpected').val();
	var updateTags=$('#tags').val();
	var updateArchive=$('#p_archive').val();
	var updateMACCollection="";
	if ($('#mac_collection').is(':checked')) {updateMACCollection="checked"}
	$.post("/components/infusions/portalauth/functions.php", {updateConfig:"",testSite:updateSite,dataExpected:updateExpectedData,tags:updateTags,p_archive:updateArchive,mac_collection:updateMACCollection},function(rtn){
		if (rtn == true) {
			alert('Configuration Saved');
			refresh_small("portalauth");
		} else {
			alert('Failed to save configuration');
		}
	});
}
$('.pa_save_config_button').on("click",function(){
	updateConfigData();
});
$('#uninstallDepends').on("click",function(){
	var res = confirm("Are you sure you want to uninstall all dependencies?\nYou should only do this if you are uninstalling PortalAuth.");
	if (res == true) {
		$.post("components/infusions/portalauth/functions.php", {uninstallDepends:""},function(data){
			if (data == true){
				$('#uninstallDepends').html("");
				alert("All dependencies have been uninstalled");
				refresh_small("portalauth");
				hide_large_tile();
			} else {
				$('#failed_uninstall').html("Failed to uninstall but you can do it manually if you run these commands<br /><br />rm -rf /usr/lib/python2.7/site-packages/bs4<br />rm -rf /usr/lib/python2.7/site-packages/beautifulsoup4-4.3.2-py2.7.egg-info<br />rm -rf /usr/lib/python2.7/site-packages/requests<br />rm -rf /usr/lib/python2.7/site-packages/requests-2.5.1-py2.7.egg-info");
			}
		});
	}
});
$('#defaultTestSite').on('click',function(){
	$('#testSite').val("https://infotomb.com/6qn72.txt");
	$('#dataExpected').val("No Captive Portal");
});
</script>
</head>
<body>
<div class="pa_config_div">
	<table class="pa_config_table">
	<tr class="pa_config_row"><td>
	<h2 class="pa_h2">Test Website:<help id='portalauth:testwebsite'></help></h2><input class="pa_config_field" type="text" id="testSite" value="<?echo $configs['testSite'];?>"/><br /><br />
	<button id="defaultTestSite">Use InfoTomb Server</a>
	</td></tr>
	<tr class="pa_config_row"><td>
	<h2 class="pa_h2">Expected Data:<help id='portalauth:expecteddata'></help></h2><input class="pa_config_field" type="text" id="dataExpected" value="<?echo $configs['dataExpected'];?>"/>
	</td></tr>
	<tr class="pa_config_row"><td>
	<h2 class="pa_h2">Element Tags:<help id='portalauth:tags'></help></h2><input class="pa_config_field" type="text" id="tags" value="<?echo $configs['tags'];?>"/>
	</td></tr>
	<tr class="pa_config_row"><td>
	<h2 class="pa_h2">Portal Archive<help id='portalauth:p_archive'></help></h2><input class="pa_config_field" type="text" id="p_archive" value="<?echo $configs['p_archive'];?>"/>
	</td></tr>
	<tr class="pa_config_row"><td>
	<h2 class="pa_h2">Client MAC Collection<help id='portalauth:mac_collection'></help></h2>
	<label><input type="checkbox" style="display:inline" id="mac_collection" <?echo $configs['mac_collection'];?> >
	<h2 style="display:inline">Collect MACs upon auth failure</h2></label>
	</td></tr>
	<table>

<div style="margin-top: 20px; width: 100%; text-align: center;">
	<button class="pa_button pa_save_config_button">Save Config</button>

	<div class="pa_config_div" style="margin-top: 50px;">
		<a href="#" id="uninstallDepends">Uninstall Dependencies</a>
		<p id="failed_uninstall" style="text-align: left; color: #FF0000"></p>
	</div>
</div>
</div>

</body>
</html>