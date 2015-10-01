<?php
namespace pineapple;
$pineapple = new Pineapple(__FILE__);
require $pineapple->directory . "/functions.php";
global $rel_dir;

if (!$pineapple->online()) {
	echo "<div style='text-align: right'><a href='#' class='refresh' onclick='refresh_small(\"portalauth\");'></a></div>";
	echo "<p style='text-align: center; color: #FF0000;'>Pineapple must be online to use PortalAuth</p>";
} else {
?>
<html>
<head>
<style>@import url('<?php echo $rel_dir; ?>/includes/css/infusion.css')</style>
<script src="/components/infusions/portalauth/includes/js/infusion.js" type="text/javascript"></script>

<script type="text/javascript">
var portalStatus;
window.onload = setTimeout(requestUpdate, 10);
function requestUpdate() {
	$('#statusDiv').html("<span style='color: #FFFFFF;'>Checking for captive portal...</span><br /><progress></progress>");
	$.post("/components/infusions/portalauth/functions.php",{updateStatus:""},function(data){updateCallback(data)});
}
function updateCallback(data){
	portalStatus=data;
	if (data == true) {
		$('#statusDiv').html("<? echo $foundStatus; ?>");
		$('#auto_auth_container,.pa_maclogdiv').css("display", "block");
	} else if (data == false) {
		$('#statusDiv').html("<? echo $notFoundStatus; ?>");
		$('#auto_auth_container,.pa_maclogdiv').css("display", "none");
	}
}
$('.pa_auto_auth_button').on("click",function(){
	$('#statusDiv').html("<span style='color: #FFFFFF;'>Attempting to authenticate...</span><br /><progress></progress>");
	$.post("/components/infusions/portalauth/functions.php", {autoAuthenticate:""},function(data){
		if (data == "Complete") {
			// Check for captive portal
			requestUpdate();
			if (portalStatus == true) {
				$.post("/components/infusions/portalauth/functions.php",{allow_mac_collection:""},function(data){
					if (data == true) {
						$('#statusDiv').html("<span style='color: #FFFFFF;'>Collecting client MACs...</span><br /><progress></progress>");
						$.post("/components/infusions/portalauth/functions.php",{scanNetwork:""},function(data){
							var macs=data.replace(new RegExp(';','g'), '<br />');
							$('#macStealerBox').html("<br />"+macs+"<br /><br />");
							$.post("/components/infusions/portalauth/functions.php",{saveCapturedMACs:macs});
							displayMACStealer();
							updateCallback(portalStatus);
						});
					}
				});
			}
		} else {
			// Script failed to execute properly
			$('#statusDiv').html("<span style='color: #FF0000;'>Script failed to execute<br /></span>");
		}
	});
});
$('.pa_clone_button').on("click",function(){
	$('#pa_cloner_injection_set_options').html("");
	$.post("/components/infusions/portalauth/functions.php",{getInjectionSets:""},function(data){
		var dirs = data.split(";");
		var opts = "<option value=''>Select...</option>";
		for (var x = 0; x < dirs.length; x++) {
			opts += "<option value='" + dirs[x] + "'>" + dirs[x] + "</option>";
		}
		$('#pa_cloner_injection_set_options').html(opts);
	});
	displayMsg();
});
$('#pa_openDependsConfirm').on("click",function(){
	$('#pa_dependsConfirmDiv,#overlay-back').fadeIn('slow');
});
$('#pa_confirmInstallDepends').on('click',function(){
	$('#pa_dependsConfirmDiv,#overlay-back').fadeOut('slow');
	$('#progress').html("<progress></progress><br /><br /><p>This will take a few minutes.<br />The tile will refresh automatically.</p>");
	$.post("/components/infusions/portalauth/functions.php",{installDepends:""},function(data){
		if (data != true) {
			alert("Failed to install dependencies.  Please check the error log for details.");
		}
		refresh_small("portalauth");
	});
});
$('#configurePA').on("click",function(){
	$('#config_progress').html("<progress></progress><br /><br />The tile will refresh automatically.</p>");
	$.post("/components/infusions/portalauth/functions.php",{configurePA:""},function(data){
		if (data != true) {
			alert("Configuration failed.  Please check the error log for details.");
		}
		refresh_small("portalauth");
	});
});
$('.pa_name_portal_button').on("click",function(){
	var injectionSet = $('#pa_cloner_injection_set_options').val();
	if (($('#injectjs').is(':checked') || $('#injectcss').is(':checked') || $('#injecthtml').is(':checked')) && injectionSet == "") {
		alert("An Injection Set must be selected to include injections.  Either select one or uncheck all injections.");
		return;
	}
	if ($('#portalname').val().length < 1) {return;}
	$.post("/components/infusions/portalauth/functions.php",{checkPortalName:$('#portalname').val()},function(data){
		if (data == true) {
			var $res = confirm("'" + $('#portalname').val() + "' already exists.  Are you sure you want to overwrite this portal?");
			if ($res == false) {
				return;
			}
		}
		// Create the new captive portal
		$('#clone_progress').html("<progress></progress>");
		$('#clone_message').html("<p>This will take a few minutes...</p>");
		var _opts=[$('#striplinks'),$('#injectjs'),$('#stripjs'),$('#injectcss'),$('#stripcss'),$('#injecthtml'),$('#stripforms')];
		var clonerOpts="";
		_opts.forEach(function(entry) {
			var sel = entry.selector;
			if ($(sel).is(":checked")) {
				clonerOpts += sel.replace("#", "") + ";"
			}
		});
		clonerOpts = clonerOpts.slice(0,-1);
		$.post("/components/infusions/portalauth/functions.php",{clonePortal:$('#portalname').val(),options:clonerOpts,module:injectionSet},function(data){
			if (data == true) {
				if ($('#activatePortal').is(':checked')) {
					$.post("/components/infusions/portalauth/functions.php",{activatePortal:$('#portalname').val(), module:injectionSet});
				}
				// Captive portal created
				alert("Portal cloned successfully!");
			} else {
				// Failed to clone
				alert("An error occurred.  Please check the error log for details.");
			}
			$("#msgBox, #overlay-back").fadeOut("slow");
			$('#clone_progress,#clone_message').html("");
		});
	});
});
$('#viewMACLog').on("click",function(){
	$.post("/components/infusions/portalauth/functions.php",{getCapturedMACs:""},function(data){
		if (data == "") {data = "<h2>No MACs have been collected yet!</h2>"}
		$('#macStealerBox').html("<br />"+data+"<br /><br />");
		displayMACStealer();
	});
});
function displayMsg(){
	$(function(){
			$("#portalname").val("");
			$("#injectjs,#injectcss,#injecthtml").attr("checked",true);
			$("#msgBox").css("opacity", "1");
			$("#msgBox,#overlay-back").fadeIn("slow");
		});
}
$('#openTServerConfig').on("click",function(){
	$('#tserverdiv,#overlay-back').fadeIn("slow");
});
$('#defaultSite').on("click",function(){
	if ($(this).is(":checked")) {
		$('#testSite').val("http://www.puffycode.com/cptest.html");
		$('#dataExpected').val("No Captive Portal");
	} else {
		$('#testSite').val("");
		$('#dataExpected').val("");
	}
});
$('#saveTServerConfig').on("click",function(){
	var ctestSite = $('#testSite').val();
	var cdataExpected = $('#dataExpected').val();
	if (testSite == "" || dataExpected == "") {
		return;
	} else {
		$.post("/components/infusions/portalauth/functions.php",{configureTServer:"",testSite:ctestSite,dataExpected:cdataExpected},function(data){
			if (data == true) {
				$("#tserverdiv,#overlay-back").fadeOut("slow");
				refresh_small("portalauth");
			}
		});
	}
});
function displayMACStealer(){
	$(function(){
		$("#macStealerBox").css("opacity","1");
		$("#macStealerBox,#overlay-back").fadeIn("slow");
	});
}
$('#overlay-back').on("click",function(){
		$("#msgBox,#macStealerBox,#tserverdiv,#pa_dependsConfirmDiv,#overlay-back").fadeOut("slow");
});
</script>
</head>
<body>

<?php
if (!dependsInstalled()) {
?>
	<span id="progress"><a href="#" style="font-size: 18px;" id="pa_openDependsConfirm">Install Dependencies</a></span>
<?php
}
if (!jQueryExists()) {
	echo "<script>alert('Failed to copy jQuery!  Please place a copy of jquery.min.js in your /www/nodogsplash/ directory!');</script>";
}
if (!tserverConfigured()) {
?>
	<br />
	<a href="#" style="font-size: 18px;" id="openTServerConfig">Configure test server</a>
<?php
}
if (dependsInstalled() && jQueryExists() && tserverConfigured()) {
?>
<div class="pa_top">
	<div style="text-align: right">
		<a href="#" class="refresh" onclick="refresh_small('portalauth');"></a>
	</div>
	<table class="pa_small_tile_table">
	<tr class="pa_small_tile_tr">
	<td align="left" class="pa_small_tile_td">
		<div id="statusDiv"></div>
	</td></tr>
	</table>
</div>

<div class="pa_container">
	<div id="auto_auth_container">
		<button class="pa_button pa_clone_button">Clone Portal</button>
		<button class="pa_button pa_auto_auth_button">Auto Authenticate</button>
	</div>
	<br />
	<div class="pa_maclogdiv">
		<a href="#" id="viewMACLog">View captured MACs</a>
	</div>
</div>

<?php
}
?>

<div id="overlay-back"></div>
<div id="msgBox" class="main">
	<div id="clone_progress" style="width: 100%"></div>
	<div id="clone_message" style="text-align:center;margin-top: 10px;"></div>
    <table class="pa_cloner_table" border="0">
        <tr><td colspan="2">
            <h1 class="pa_h1">Name This Portal<help id='portalauth:portalname'></help></h1><input type="text" id="portalname" class="pa_config_field"/>
        </td></tr>
		<tr><td colspan="2">
			<h2 class="pa_h2" style="display: inline">Injection Set </h2><select class="pa_moduleSelect" id="pa_cloner_injection_set_options"></select>
		</td></tr>
		<tr><td>
			<label><h2 class="pa_h2" style="display: inline;"><input type="checkbox" id="striplinks">Strip Links</h2></label><h2 class="pa_h2" style="display:inline"><help id='portalauth:striplinks'></help></h2>
		</td><td>
			<label><h2 class="pa_h2" style="display: inline;"><input type="checkbox" id="injectjs" checked>Inject JS</h2></label>
		</td></tr>
		<tr><td>
			<label><h2 class="pa_h2" style="display: inline;"><input type="checkbox" id="stripjs">Strip Inline JS</h2></label><h2 class="pa_h2" style="display:inline"><help id='portalauth:stripjs'></help></h2>
		</td><td>
			<label><h2 class="pa_h2" style="display: inline;"><input type="checkbox" id="injectcss" checked>Inject CSS</h2></label>
		</td></tr>
		<tr><td>
			<label><h2 class="pa_h2" style="display: inline;"><input type="checkbox" id="stripcss">Strip Inline CSS</h2></label><h2 class="pa_h2" style="display:inline"><help id='portalauth:stripcss'></help></h2>
		</td><td>
			<label><h2 class="pa_h2" style="display: inline;"><input type="checkbox" id="injecthtml" checked>Inject HTML</h2></label>
		</td></tr>
		<tr><td>
			<label><h2 class="pa_h2" style="display: inline;"><input type="checkbox" id="stripforms">Strip Forms</h2></label><h2 class="pa_h2" style="display:inline"><help id='portalauth:stripforms'></help></h2>
		</td><td>
			<label><h2 class="pa_h2" style="display: inline;"><input type="checkbox" id="activatePortal">Activate Portal</h2></label><h2 class="pa_h2" style="display:inline"><help id='portalauth:activateportal'></help></h2>
		</td></tr>
		<tr><td colspan="2" style="text-align:center">
            <button class="pa_button pa_name_portal_button">Start Cloning</button>
        </td></tr>
    </table>
</div>

<div id="macStealerBox" class="main" style="text-align:center">
</div>

<div id="tserverdiv" class="main">
	<h3 style="padding: 5px 10px 5px 10px;">In order to detect captive portals a request needs to be sent out to a server and the data retrieved must be equal to the data expected.  You may set this up with your own server or use sud0nick's.  Please note that every check for a captive portal will send a request to the test server.  These options can be changed at any time from the Config menu.</h3>
	<div class="pa_config_div">
		<table class="pa_config_table">
		<tr class="pa_config_row"><td>
			<h2 class="pa_h2">Test Website:<help id='portalauth:testwebsite'></help></h2><input class="pa_config_field" type="text" id="testSite" value=""/>
		</td></tr>
		<tr class="pa_config_row"><td>
			<h2 class="pa_h2">Expected Data:<help id='portalauth:expecteddata'></help></h2><input class="pa_config_field" type="text" id="dataExpected" value=""/>
		</td></tr>
		<tr class="pa_config_row"><td>
			<label><input type="checkbox" id="defaultSite">Use sud0nick's server</label>
		</td></tr>
		<tr class="pa_config_row"><td>
			<button class="pa_button" id="saveTServerConfig">Save</button>
		</td></tr>
		</table>
	</div>
</div>

<div id="pa_dependsConfirmDiv" class="main">
	<br />
	<h3 style="padding: 5px 10px 5px 10px;">The required dependencies will be downloaded from PuffyCode.com.  The MD5 checksum of each file will be verified after the download completes and before unpackaging the archives.  By clicking 'Install' you understand that these dependencies do not originate from Hak5's servers.</h3>
	<h3 style="padding: 5px 10px 5px 10px;">Due to the minimal storage capacity on the Pineapple the dependencies must be installed on the SD card.  To do this the <span style="color: #00FF00">/usr/lib/python2.7/site-packages/</span> directory will be moved to <span style="color:#00FF00">/sd/depends/</span> and a symbolic link will be created for it if it hasn't already been done.</h3>
	<div class="pa_config_div">
		<table class="pa_config_table">
		<tr class="pa_config_row"><td style="padding-top: 20px">
			<button class="pa_button" id="pa_confirmInstallDepends">Install</button>
		</td></tr>
		</table>
	</div>
</div>
</body>
</html>
<? } ?>
