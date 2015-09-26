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
document.getElementById("pa_token").value = _csrfToken;
$(document).ready(function(){
	var options = {
		type: "POST",
		url: "/components/infusions/portalauth/functions.php",
		processData: false,
		contentType: false,
		cache: false,
		success: showSuccess
	};
	$('#pa_ImportInjectForm').submit(function(){
		if ($('#pa_importFile').val() == "") {
			alert("No file selected");
			return false;
		}
		$(this).ajaxSubmit(options);
		return false;
	});
});
function showSuccess(responseText, statusText, xhr, $form) {
	alert(responseText);
	$('#pa_importFile').val("");
	$('#pa_importFile').replaceWith($(this).clone(true));
}
$('.save_injectjs,.save_injectcss,.save_injecthtml,.save_auth_php').on("click",function(){
	var ta = $(this).attr('ta');
	var key = $(this).attr('id');
	var value = $("#"+ta).val();
	var dataToSend = {};
	dataToSend[key] = value;
	dataToSend['module'] = $('#pa_injectionSelection').val();
	$.post("/components/infusions/portalauth/functions.php",dataToSend,function(data){
		if (data == true){
			alert("Save Successful!");
		} else {
			alert("Something went wrong.");
		}
	});
});
$('.pa_cloner_container').delegate('#injectJS,#injectCSS,#injectHTML,#authPHP', 'keydown', function(e) {
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
$('#restoreInjectJS,#restoreInjectCSS,#restoreInjectHTML,#restoreAuthPHP').click(function(){
	var ta = $(this).attr('ta');
	var file = $(this).attr('fileName');
	var modName = $('#pa_injectionSelection').val();
	$.post("/components/infusions/portalauth/functions.php", {restoreFile:file, module:modName},function(data){
		if (data == false) {
			alert("Failed to retrieve file.");
			return;
		}
		$('#'+ta).val(data);
	});
});
$('#backupInjectJS,#backupInjectCSS,#backupInjectHTML,#backupAuthPHP').click(function(){
	var modName = $('#pa_injectionSelection').val();
	if (modName == "") {return;}
	var data = $('#'+$(this).attr('ta')).val();
	var file = $(this).attr('fileName');
	$.post("/components/infusions/portalauth/functions.php", {backupFile:file, module:modName, backupData:data},function(data){
		if (data == false) {
			alert("Failed to back up the file.");
			return;
		}
		alert("Back up successful!");
	});
});
var currentTab = false;
var managerIsDisplayed = currentMgrTab = false;
$('#pa_displayInjectsManager, #pa_displayInjectsEditor').on("click",function(){
	if (currentTab == this) {
		return;
	} else {
		currentTab = this;
	}
	$('#pa_displayInjectsManager, #pa_displayInjectsEditor').css('background-color', 'black');
	$(this).css('background-color','white');
	$('.pa_injects_manager, #pa_InjectsEditorContainer').css('display','none');
	if ($(this).html() == "Manage Injections") {
		$('.pa_injects_manager').fadeIn("slow");
	} else if ($(this).html() == "Edit Injections") {
		// Refresh Injection Set selection list
		$('#pa_injectionSelection').html("");
		$('#injectJS,#injectCSS,#injectHTML,#authPHP').val("");
		$.post("/components/infusions/portalauth/functions.php",{getInjectionSets:""},function(data){
			var dirs = data.split(";");
			var opts = "<option value=''>Select...</option>";
			for (var x = 0; x < dirs.length; x++) {
				opts += "<option value='" + dirs[x] + "'>" + dirs[x] + "</option>";
			}
			$('#pa_injectionSelection').html(opts);
		});
		// Display the editor window
		$('#pa_InjectsEditorContainer').fadeIn("slow");
	}
});
$('#pa_newInjectSet,#pa_importInjectSet,#pa_exportInjectSet,#pa_deleteInjectSet,#pa_downloadInjectSet').on('click',function(){
	if (currentMgrTab == this) {
		return;
	} else {
		currentMgrTab = this;
	}
	$('#pa_newInjectSet,#pa_importInjectSet,#pa_exportInjectSet,#pa_deleteInjectSet,#pa_downloadInjectSet').css('background-color', 'black');
	$(this).css('background-color', 'white');
	$('#pa_newInjectionDiv,#pa_importInjectionDiv,#pa_exportInjectionDiv,#pa_deleteInjectionDiv,#pa_downloadInjectionDiv').css('display','none');
	if ($(this).html() == "New Set") {
		$('#pa_newInjectionSetName').val("");
		$('#pa_newInjectionDiv').fadeIn("slow");
	} else if ($(this).html() == "Import Set") {
		$('#pa_importInjectionDiv').fadeIn("slow");
	} else if ($(this).html() == "Export Set") {
		// Refresh the export injection selection list
		$('#pa_exportSelection').html("");
		$.post("/components/infusions/portalauth/functions.php",{getInjectionSets:""},function(data){
			var dirs = data.split(";");
			var opts = "<option value=''>Select...</option>";
			for (var x = 0; x < dirs.length; x++) {
				opts += "<option value='" + dirs[x] + "'>" + dirs[x] + "</option>";
			}
			$('#pa_exportSelection').html(opts);
		});
		$('#pa_exportInjectionDiv').fadeIn("slow");
	} else if ($(this).html() == "Delete Set") {
		// Load list of Injection Sets
		$('#pa_deleteInjectionDiv').html("");
		$.post("/components/infusions/portalauth/functions.php",{getInjectionSets:""},function(data){
			var dirs = data.split(";");
			var opts = ""
			for (var x = 0; x < dirs.length; x++) {
				opts += "<span id='" + dirs[x] + "'><a href='#' id='" + dirs[x] + "' class='pa_deleteInjectionSet'>Delete</a> ::: <h2 class='pa_h2' style='display: inline'>" + dirs[x] + "</h2><hr /></span>";
			}
			$('#pa_deleteInjectionDiv').html(opts);
		});
		$('#pa_deleteInjectionDiv').fadeIn("slow");
	} else if ($(this).html() == "Download Set") {
		$('#pa_downloadInjectionDiv').fadeIn("slow");
	}
});
$('#pa_createInjectionSet').on("click",function(){
	var injectionName = $('#pa_newInjectionSetName').val();
	if (injectionName.length > 0) {
		$.post("/components/infusions/portalauth/functions.php",{createInjectionSet:injectionName},function(data){
			if (data == true) {
				alert("Injection set " + injectionName + " has been created!");
			} else if (data == false) {
				alert("Failed to create injection set.  Please check the error log for details");
			} else {
				alert(data);
			}
		});
	}
});
$('#pa_deleteInjectionDiv').on('click','.pa_deleteInjectionSet',function(){
	if (!confirm("Delete injection set '" + $(this).attr('id') + "'?")) {
		return;
	}
	var elem = $(this).attr('id');
	$.post("/components/infusions/portalauth/functions.php",{deleteInjectionSet:elem},function(data){
		if (data == true) {
			$('span[id="'+elem+'"]').remove();
		} else {
			alert("Failed to delete injection set");
		}
	});
});
// Stupid AJAX requests...I had this in a loop with a lot less code but due to the nature of AJAX requests
// they wouldn't execute sequentially so I had to make new requests after each one returned
$('#pa_injectionSelection').change(function(){
	var selection = $(this).val();
	if (selection == "") {
		$('#injectJS,#injectCSS,#injectHTML,#authPHP').val("");
		return;
	}
	$.post("/components/infusions/portalauth/functions.php",{getInjectionFile:"injectJS.txt", module:selection},function(data){
		if (data != false){
			$('#injectJS').val(data);
			$.post("/components/infusions/portalauth/functions.php",{getInjectionFile:"injectCSS.txt", module:selection},function(data){
				if (data != false) {
					$('#injectCSS').val(data);
					$.post("/components/infusions/portalauth/functions.php",{getInjectionFile:"injectHTML.txt", module:selection},function(data){
						if (data != false){
							$('#injectHTML').val(data);
							$.post("/components/infusions/portalauth/functions.php",{getInjectionFile:"auth.php", module:selection},function(data){
								if (data != false){
									$('#authPHP').val(data);
								} else {
									$('#authPHP').val("File could not be found...");
								}
							});
						} else {
							$('#injectHTML').val("File could not be found...");
						}
					});
				} else {
					$('#injectCSS').val("File could not be found...");
				}
			});
		} else {
			$('#injectJS').val("File could not be found...");
		}
	});
});
$('#pa_exportInjectSetButton').on("click",function(){
	var fileName = $('#pa_exportSelection').val();
	if (fileName == "") {return;}
	$.post("/components/infusions/portalauth/functions.php",{exportInjectionSet:fileName},function(data){
		if (data == true) {
			popup("<div style='text-align: center'><a href='/components/infusions/portalauth/functions.php?downloadInjectionSet="+fileName+"&_csrfToken="+ _csrfToken +"'>Download "+fileName+".tar.gz</a></div>");
		} else {
			alert("Failed to export injection set.  Please check the error log for details.");
		}
	});
});
$('#activateAuthPHP').on("click",function(){
	var injectSet = $('#pa_injectionSelection').val();
	if (injectSet == "") {return;}
	$.post("/components/infusions/portalauth/functions.php",{activateAuthPHP:"",module:injectSet},function(data){
		if (data == true) {
			alert("Auth PHP activated!");
		} else {
			alert("Failed to activate!");
		}
	});
});
$('#pa_downloadInjectionDiv').on("click",".pa_externalInjectionSet",function(){
	var html = $('#pa_availableInjectionSets').html();
	$.post("/components/infusions/portalauth/functions.php",{downloadExternalInjectionSet:$(this).attr('url')},function(data){
		if (data == true) {
			alert("Download Successful");
		} else {
			alert("Download Failed");
		}
		$('#pa_availableInjectionSets').html(html);
	});
	$('#pa_availableInjectionSets').html("<h3>Downloading...</h3><progress></progress><br /><br />");
});
function refreshAvailableInjectionSets() {
	$.post("/components/infusions/portalauth/functions.php",{fetchAvailableInjectionSets:""},function(data){
		$('#pa_availableInjectionSets').html(data);
	});
	$('#pa_availableInjectionSets').html("<h3>Loading Available Injection Sets...</h3><progress></progress><br /><br />");
}
</script>
</head>
<body>
<div class="pa_cloner_container">

<h1 class="pa_h1 text_center">Injections & Credential Logging</h1>

<div class="pa_container">
	<button class="pa_small_button" id="pa_displayInjectsManager" style="background-color: white">Manage Injections</button>
	<button class="pa_small_button" id="pa_displayInjectsEditor">Edit Injections</button>
</div>
<hr />
<div class="pa_container pa_injects_manager" style="display: block">
	<button class="pa_small_button" id="pa_downloadInjectSet">Download Set</button>
	<button class="pa_small_button" id="pa_newInjectSet">New Set</button>
	<button class="pa_small_button" id="pa_importInjectSet">Import Set</button>
	<button class="pa_small_button" id="pa_exportInjectSet">Export Set</button>
	<button class="pa_small_button" id="pa_deleteInjectSet">Delete Set</button>

	<div id="pa_downloadInjectionDiv" class="pa_managerDiv" style="text-align: center">
		<div id="pa_availableInjectionSets"></div>
		<button class="pa_small_button" onClick="refreshAvailableInjectionSets();">Refresh List</button>
	</div>
	<div id="pa_newInjectionDiv" class="pa_managerDiv" style="text-align: center">
		<table class="pa_config_table" style="width: 100%">
			<tr><td>
			<h2 class="pa_h2">New Injection Set</h2>
			</td></tr><tr><td>
			<input type="text" class="pa_config_field" id="pa_newInjectionSetName" placeholder="Injection Set Name"/>
			</td></tr><tr><td>
			<button class="pa_small_button" id="pa_createInjectionSet">Create</button>
			</td></tr>
		</table>
	</div>
	<div id="pa_importInjectionDiv" class="pa_managerDiv" style="text-align: center">
			<h2 class="pa_h2">Import Injection Set</h2>
		<form id="pa_ImportInjectForm">
			<input type="file" name="importFile" id="pa_importFile" accept="application/x-compressed" />
			<input type="hidden" name="_csrfToken" id="pa_token" value="" /><br /><br />
			<button class="pa_button pa_small_button" type="submit" id="pa_importInjectionSetButton">Import</button>
		</form>
	</div>
	<div id="pa_exportInjectionDiv" class="pa_managerDiv" style="text-align: center">
		<h2 class="pa_h2">Select An Injection Set to Export</h2>
		<select class="pa_moduleSelect" id="pa_exportSelection"></select>
		<br /><br />
		<button class="pa_button pa_small_button" id="pa_exportInjectSetButton">Export</button>
	</div>
	<div id="pa_deleteInjectionDiv" class="pa_managerDiv">
	</div>
</div>

<div id="pa_InjectsEditorContainer" style="display: none">
	<div class="pa_container">
		<h2 class="pa_h2">Current Injection Set</h2>
		<select class="pa_moduleSelect" id="pa_injectionSelection">
		</select>
	</div>
	
	<table class="pa_inject_table">
	<tr><td align="left" colspan="2">
	<h2 class="pa_h2">InjectJS<help id='portalauth:injectjs'></help></h2>
	</td></tr><tr><td align="left" colspan="2">
	<textarea id="injectJS" wrap="off" spellcheck='false'></textarea>
	</td></tr>
	<tr><td align="left" valign="top">
	<h3><a href="#" id="restoreInjectJS" fileName="injectjs" ta="injectJS">Restore InjectJS</a> | <a href="#" id="backupInjectJS" fileName="injectjs" ta="injectJS">Back up InjectJS</a></h3>
	</td><td align="right">
	<button class="pa_button save_injectjs" id="saveInjectJS" ta="injectJS">Save JS</button><br /><br />
	</td></tr>
	</table>

	<table class="pa_inject_table">
	<tr><td align="left" colspan="2">
	<h2 class="pa_h2">InjectCSS<help id='portalauth:injectcss'></help></h2>
	</td></tr><tr><td align="left" colspan="2">
	<textarea id="injectCSS" wrap="off" spellcheck='false'></textarea>
	</td></tr>
	<tr><td align="left" valign="top">
	<h3><a href="#" id="restoreInjectCSS" fileName="injectcss" ta="injectCSS">Restore InjectCSS</a> | <a href="#" id="backupInjectCSS" fileName="injectcss" ta="injectCSS">Back up InjectCSS</a></h3>
	</td><td align="right">
	<button class="pa_button save_injectcss" id="saveInjectCSS" ta="injectCSS">Save CSS</button><br /><br />
	</td></tr>
	</table>

	<table class="pa_inject_table">
	<tr><td align="left" colspan="2">
	<h2 class="pa_h2">InjectHTML<help id='portalauth:injecthtml'></help></h2>
	</td></tr><tr><td align="left" colspan="2">
	<textarea id="injectHTML" wrap="off" spellcheck='false'></textarea>
	</td></tr><tr><td align="left" valign="top">
	<h3><a href="#" id="restoreInjectHTML" fileName="injecthtml" ta="injectHTML">Restore InjectHTML</a> | <a href="#" id="backupInjectHTML" fileName="injecthtml" ta="injectHTML">Back up InjectHTML</a></h3>
	</td><td align="right">
	<button class="pa_button save_injecthtml" id="saveInjectHTML" ta="injectHTML">Save HTML</button>
	</td></tr>
	</table>

	<table class="pa_inject_table">
	<tr><td align="left" colspan="2">
	<h2 class="pa_h2">auth.php<help id='portalauth:auth.php'></help></h2>
	</td></tr><tr><td align="left" colspan="2">
	<textarea id="authPHP" wrap="off" spellcheck='false'></textarea>
	</td></tr><tr><td align="left" valign="top">
	<h3><a href="#" id="restoreAuthPHP" fileName="authphp" ta="authPHP">Restore auth.php</a> | <a href="#" id="backupAuthPHP" fileName="authphp" ta="authPHP">Back up auth.php</a> | <a href="#" id="activateAuthPHP">Activate Now</a></h3>
	</td><td align="right">
	<button class="pa_button save_auth_php" id="saveAuthPHP" ta="authPHP">Save auth.php</button>
	</td></tr>
	</table>
	<br />
</div>
</div>

</body>
</html>