<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/functions.php');
global $rel_dir;
?>
<!DOCTYPE html>
<html>
<head>
<script src="/components/infusions/portalauth/includes/scripts/jquery.form.js" type="text/javascript"></script>
<script src="/components/infusions/portalauth/includes/js/infusion.js" type="text/javascript"></script>
<script>
pa_pass_refresh_interval = setInterval(function(){pa_refreshActivityLog();}, 1000);
pa_pass_target_refresh_interval = setInterval(function(){pa_refreshTargetLog();}, 1000);

document.getElementById('pa_winPayloadToken').value = _csrfToken;
document.getElementById('pa_osxPayloadToken').value = _csrfToken;
document.getElementById('pa_androidPayloadToken').value = _csrfToken;
document.getElementById('pa_iosPayloadToken').value = _csrfToken;

document.getElementById('pa_downloadActivityLog').href = "/components/infusions/portalauth/functions.php?dlActivityLog&_csrfToken=" + _csrfToken;
document.getElementById('pa_downloadTargetLog').href = "/components/infusions/portalauth/functions.php?dlTargetLog&_csrfToken=" + _csrfToken;
document.getElementById('pa_downloadCompileScript_Windows').href = "/components/infusions/portalauth/functions.php?dlCompileScriptWin&_csrfToken=" + _csrfToken;
document.getElementById('pa_downloadCompileScript_OSX').href = "/components/infusions/portalauth/functions.php?dlCompileScriptOSX&_csrfToken=" + _csrfToken;

// Check if there is a current process running
checkServerStatus();

$(document).ready(function(){
	var options = {
		type: "POST",
		url: "/components/infusions/portalauth/functions.php",
		processData: false,
		contentType: false,
		cache: false,
		success: showSuccess
	};
	$('#pa_ImportWindowsPayloadForm,#pa_ImportOSXPayloadForm,#pa_ImportAndroidPayloadForm,#pa_ImportiOSPayloadForm').submit(function(){
		payIn = $(this).attr('payloadInput');
		if ($('#'+payIn).val() == "") {
			return false;
		}
		$(this).ajaxSubmit(options);
		$('#pa_uploadStatus').html("<div style='width: 50%; margin:0 auto;'><h2>Uploading File...</h2><progress></progress></div>");
		return false;
	});
});
function showSuccess(responseText, statusText, xhr, $form) {
	if (responseText == true) {
		$('#pa_uploadStatus').html("<div style='text-align: center'><h1>Payload Uploaded!</h1></div>");
		setTimeout(function(){$('#pa_uploadStatus').html("");}, 2000);
	} else {
		alert("Failed to upload the payload.");
		$('#pa_uploadStatus').html("");
	}
	$('#pa_importWindowsPayload,#pa_importOSXPayload,#pa_importAndroidPayload,#pa_importiOSPayload').val("");
	$('#pa_importWindowsPayload,#pa_importOSXPayload,#pa_importAndroidPayload,#pa_importiOSPayload').replaceWith($(this).clone(true));
}
function checkServerStatus() {
	$.post("/components/infusions/portalauth/functions.php",{getPID:""},function(d){
		if (d != false) {
			$('#pa_pass_current_status').html("Running <button onclick='stopServer()'>Stop</button>");
		} else {
			$('#pa_pass_current_status').html("Disabled <button onclick='startServer()'>Start</button>");
		}
	});
}
function stopServer() {
	$.post("/components/infusions/portalauth/functions.php",{stopServer:""},function(d){
		if (d == true) {
			$('#pa_pass_current_status').html("Disabled <button onclick='startServer()'>Start</button>");
		} else {
			alert("Server failed to stop!");
		}
	});
}
function startServer() {
	$.post("/components/infusions/portalauth/functions.php",{startServer:""},function(d){
		if (d == true) {
			$('#pa_pass_current_status').html("Running <button onclick='stopServer()'>Stop</button>");
		} else {
			alert("Server failed to start!");
		}
	});
}

$('.pa_cloner_container').delegate('#pa_pass', 'keydown', function(e){
  var keyCode = e.keyCode || e.which;

  if (keyCode == 9) {
    e.preventDefault();
    var start = $(this).get(0).selectionStart;
    var end = $(this).get(0).selectionEnd;

    // set textarea value to: text before caret + tab + text after caret
    $(this).val($(this).val().substring(0, start)
                + "\t"
                + $(this).val().substring(end));

    // put caret at right position again
	$(this).get(0).selectionEnd = start + 1;
  }
});
$('.save_pa_pass').on("click",function(){
	var ta = $(this).attr('ta');
	var key = $(this).attr('id');
	var value = $("#"+ta).val();
	var dataToSend = {};
	dataToSend[key] = value;
	$.post("/components/infusions/portalauth/functions.php",dataToSend,function(d){
		if (d == true){
			alert("Save Successful!");
		} else {
			alert("Something went wrong.");
		}
	});
});
$('#restore_pa_pass').click(function(){
	var ta = $(this).attr('ta');
	var file = $(this).attr('fileName');
	$.post("/components/infusions/portalauth/functions.php", {restoreFile:file},function(d){
		if (d == false) {
			alert("Failed to retrieve file.");
			return;
		}
		$('#'+ta).val(d);
	});
});
$('#backup_pa_pass').click(function(){
	var data = $('#'+$(this).attr('ta')).val();
	var file = $(this).attr('fileName');
	$.post("/components/infusions/portalauth/functions.php", {backupFile:file, backupData:data},function(d){
		if (d == false) {
			alert("Failed to back up the file.");
			return;
		}
		alert("Back up successful!");
	});
});
var currentTab = false;
var managerIsDisplayed = currentMgrTab = false;
$('#pa_displayPASS, #pa_displayNetClient').on("click",function(){
	if (currentTab == this) {
		return;
	} else {
		currentTab = this;
	}
	$('#pa_displayPASS, #pa_displayNetClient').css('background-color', 'black');
	$(this).css('background-color','white');
	$('#pa_PASSContainer, #pa_NetClientContainer').css('display','none');
	if ($(this).html() == "PASS") {
		pa_pass_refresh_interval = setInterval(function(){pa_refreshActivityLog();}, 1000);
		pa_pass_target_refresh_interval = setInterval(function(){pa_refreshTargetLog();}, 1000);
		$('#pa_PASSContainer').fadeIn("slow");
	} else if ($(this).html() == "NetClient") {
		if (pa_pass_refresh_interval) {
			clearInterval(pa_pass_refresh_interval);
		}
		if (pa_pass_target_refresh_interval) {
			clearInterval(pa_pass_target_refresh_interval);
		}
		$('#pa_NetClientContainer').fadeIn("slow");
	}
});
$('#pa_changeNetClientCodeDivWin,#pa_changeNetClientCodeDivOSX').on('click',function(){
	if ($('#'+$(this).attr('childDiv')).is(':visible')) {
		$('#'+$(this).attr('childDiv')).slideUp("slow");
		$(this).html("&#9650; Show NetClient Code");
	} else {
		$('#'+$(this).attr('childDiv')).slideDown("slow");
		$(this).html("&#9660; Hide NetClient Code");
	}
});
$('#pa_changePASSCodeDiv').on('click',function(){
	if ($('#pa_PASSCode').is(':visible')) {
		$('#pa_PASSCode').slideUp("slow");
		$('#pa_changePASSCodeDiv').html("&#9650; Show Server Code");
	} else {
		$('#pa_PASSCode').slideDown("slow");
		$('#pa_changePASSCodeDiv').html("&#9660; Hide Server Code");
	}
});
$('#pa_CfgUploadLimitLink').on('click',function(){
	$('#pa_CfgSpan').html("<progress></progress>");
	$.post("/components/infusions/portalauth/functions.php",{cfgUploadLimit:""},function(d){
		$('#pa_CfgSpan').html("");
		if (d == false){
			alert("Failed to configure upload limit!  Please check the error logs for more information.");
			return;
		}
		alert("Upload limit configured successfully!\nPlease wait a few seconds before uploading for the configuration to be reloaded.");
	});
});
</script>
</head>
<body>
<div class="pa_cloner_container">

	<div class="pa_container">
		<button class="pa_small_button" id="pa_displayPASS" style="background-color: white">PASS</button>
		<button class="pa_small_button" id="pa_displayNetClient">NetClient</button>
	</div>

	<div id="pa_PASSContainer" style="display:block">
		<table class="pa_inject_table">
		<tr><td align="left">
			<h3 class="pa_h3" style="display:inline">Server Status: </h3><span id="pa_pass_current_status" style="color: white">Loading...</span><br />
		</td></tr>
		</table>
	
		<br /><br />
		
		<div style="width: 70%; margin: 0 auto;"><a href="#" id="pa_changePASSCodeDiv">&#9650; Show Server Code</a></div>
		<br />
		<div id="pa_PASSCode" style="display: none">
			<table class="pa_inject_table">
			<tr><td align="left" colspan="2">
			<h2 class="pa_h2">Portal Auth Shell Server (PASS) v1.0<help id='portalauth:pa_pass'></help></h2>
			</td></tr><tr><td align="left" colspan="2">
			<textarea id="pa_pass" style="height: 500px;" wrap='off' spellcheck='false'><? echo file_get_contents(__ROOT__ . "/includes/pass/pass.py"); ?></textarea>
			</td></tr>
			<tr><td align="left" valign="top">
			<h3><a href="#" id="restore_pa_pass" fileName="pa_pass" ta="pa_pass">Restore PASS</a> | <a href="#" id="backup_pa_pass" fileName="pa_pass" ta="pa_pass">Back up PASS</a></h3>
			</td><td align="right">
			<button class="pa_button save_pa_pass" id="save_pa_pass" ta="pa_pass">Save</button>
			</td></tr>
			</table>
		</div>
	
		<table class="pa_inject_table">
		<tr><td align="left" colspan="2">
		<h2 class="pa_h2">Activity Log</h2>
		</td></tr><tr><td align="left" colspan="2">
		<textarea id="pa_pass_activitylog" readonly><? echo file_get_contents(__ROOT__ . "/includes/pass/pass.log"); ?></textarea>
		</td></tr>
		<tr><td align="right">
			<a href="#" id="pa_downloadActivityLog">Download Log</a> | <a href="#" id="pa_clear_activity_log">Clear Log</a>
		</td></tr>
		</table>
		
		<table class="pa_inject_table">
		<tr><td align="left" colspan="2">
		<h2 class="pa_h2">Available Targets</h2>
		</td></tr><tr><td align="left" colspan="2">
		<textarea id="pa_pass_targetlog" readonly><? echo file_get_contents(__ROOT__ . "/includes/pass/targets.log"); ?></textarea>
		</td></tr>
		<tr><td align="right">
			<a href="#" id="pa_downloadTargetLog">Download Log</a> | <a href="#" id="pa_clear_target_log">Clear Log</a>
		</td></tr>
		</table>
		<br />
	</div>
	
	<div id="pa_NetClientContainer" style="display: none">
		<br /><br />
		<span id="pa_uploadStatus"></span>
		<table class="pa_inject_table" style="margin-top: 20px; margin-bottom: 30px;">
			<tr><td align="center">
				<h2 class="pa_h2">Windows<help id='portalauth:pa_payloadhelp'></help></h2>
			</td><td align="center">
				<h2 class="pa_h2">OS X</h2>
			</td><td align="center">
				<h2 class="pa_h2">Android</h2>
			</td><td align="center">
				<h2 class="pa_h2">iOS</h2>
			</td></tr>
			<tr><td align="center">
				<form id="pa_ImportWindowsPayloadForm" payloadInput="pa_importWindowsPayload">
					<input type="file" name="importWindowsPayload" id="pa_importWindowsPayload" accept="application/x-compressed" />
					<input type="hidden" name="_csrfToken" id="pa_winPayloadToken" value="" /><br /><br />
					<button class="pa_button" id="pa_uploadWindowsPayload" type="submit">Upload Payload</button>
				</form>
			</td><td align="center">
				<form id="pa_ImportOSXPayloadForm" payloadInput="pa_importOSXPayload">
					<input type="file" name="importOSXPayload" id="pa_importOSXPayload" accept="application/x-compressed" />
					<input type="hidden" name="_csrfToken" id="pa_osxPayloadToken" value="" /><br /><br />
					<button class="pa_button" id="pa_uploadOSXPayload" type="submit">Upload Payload</button>
				</form>
			</td><td align="center">
				<form id="pa_ImportAndroidPayloadForm" payloadInput="pa_importAndroidPayload">
					<input type="file" name="importAndroidPayload" id="pa_importAndroidPayload" accept="application/x-compressed" />
					<input type="hidden" name="_csrfToken" id="pa_androidPayloadToken" value="" /><br /><br />
					<button class="pa_button" id="pa_uploadAndroidPayload" type="submit">Upload Payload</button>
				</form>
			</td><td align="center">
				<form id="pa_ImportiOSPayloadForm" payloadInput="pa_importiOSPayload">
					<input type="file" name="importiOSPayload" id="pa_importiOSPayload" accept="application/x-compressed" />
					<input type="hidden" name="_csrfToken" id="pa_iosPayloadToken" value="" /><br /><br />
					<button class="pa_button" id="pa_uploadiOSPayload" type="submit">Upload Payload</button>
				</form>
			</td></tr>
			<tr><td colspan="3"></td>
			<td>
				<span id="pa_CfgSpan"></span>
			</td></tr>
			<tr><td colspan="4" align="right">
				<a href="#" id="pa_CfgUploadLimitLink">Configure Upload Limit</a><h2 style="display:inline"><help id='portalauth:pa_cfgUploadLimit'></help></h2>
			</td></tr>
		</table>

		<div style="width: 70%; margin: 0 auto;"><a href="#" id="pa_changeNetClientCodeDivWin" childDiv="pa_NetClientCode_Windows">&#9650; Show NetClient Code</a> (Windows) <a href="#" id='pa_downloadCompileScript_Windows'>Download Compile Script</a></div>
		<br />
		<div id="pa_NetClientCode_Windows" style="display: none">
			<table class="pa_inject_table">
			<tr><td align="left" colspan="2">
			<h2 class="pa_h2">Network Client (Windows)<help id='portalauth:pa_NetworkClient_Windows'></help></h2>
			</td></tr><tr><td align="left" colspan="2">
			<textarea style="height: 500px;" wrap='off' readonly><? echo file_get_contents(__ROOT__ . "/includes/pass/NetworkClient_Windows.py"); ?></textarea>
			</td></tr>
			</table>
		</div>
		<br />
		
		<div style="width: 70%; margin: 0 auto;"><a href="#" id="pa_changeNetClientCodeDivOSX" childDiv="pa_NetClientCode_OSX">&#9650; Show NetClient Code</a> (OS X)&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id='pa_downloadCompileScript_OSX'>Download Compile Script</a></div>
		<br />
		<div id="pa_NetClientCode_OSX" style="display: none">
			<table class="pa_inject_table">
			<tr><td align="left" colspan="2">
			<h2 class="pa_h2">Network Client (OS X)<help id='portalauth:pa_NetworkClient_OSX'></help></h2>
			</td></tr><tr><td align="left" colspan="2">
			<textarea style="height: 500px;" wrap='off' readonly><? echo file_get_contents(__ROOT__ . "/includes/pass/NetworkClient_OSX.py"); ?></textarea>
			</td></tr>
			</table>
		</div>
		<br />
	</div>
	
</div>

</body>
</html>