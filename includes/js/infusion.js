// Payload tab functions and timers
var pa_pass_refresh_interval, pa_pass_target_refresh_interval;
if (pa_pass_refresh_interval) {
	clearInterval(pa_pass_refresh_interval);
}
if (pa_pass_target_refresh_interval) {
	clearInterval(pa_pass_target_refresh_interval);
}

function pa_refreshActivityLog() {
	$.post("/components/infusions/portalauth/functions.php",{refreshActivityLog:""},function(d){
		$('#pa_pass_activitylog').html(d);
	});
}
function pa_refreshTargetLog() {
	$.post("/components/infusions/portalauth/functions.php",{refreshTargetLog:""},function(d){
		$('#pa_pass_targetlog').html(d);
	});
}
$('#pa_clear_activity_log').on('click',function(){
	$.post("/components/infusions/portalauth/functions.php",{clearActivityLog:""},function(d){
		$('#pa_pass_activitylog').html(d);
	});
});
$('#pa_clear_target_log').on('click',function(){
	$.post("/components/infusions/portalauth/functions.php",{clearTargetLog:""},function(d){
		$('#pa_pass_targetlog').html(d);
	});
});

// Auth log functions and timers
var pa_auth_log_interval;
if (pa_auth_log_interval) {
	clearInterval(pa_auth_log_interval);
}

function pa_refreshAuthLog() {
	$.post("/components/infusions/portalauth/functions.php",{refreshAuthLog:""},function(d){
		$('#authlog').html(d);
	});
}