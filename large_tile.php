<?php
namespace pineapple;
$pineapple = new Pineapple(__FILE__);
require_once $pineapple->directory . "/functions.php";

if (!$pineapple->online()) {
	echo "<p style='text-align: center; color: #FF0000;'>Pineapple must be online to use PortalAuth</p>";
	return;
}

if (dependsInstalled() && tserverConfigured()) {
	$pineapple->drawTabs(array("Config", "Portal", "Injects", "Payload", "Auth Log", "Change Log", "Error Logs"));
} else if (!dependsInstalled()) {
	echo "<h1 style='color: #FF0000; text-align:center'>Dependencies must be installed!</h1>";
	$pineapple->drawTabs(array("Error Logs"));
} else if (!tserverConfigured()) {
	echo "<h1 style='color: #FF0000; text-align:center'>Test server must be configured!</h1>";
	$pineapple->drawTabs(array("Error Logs"));
}
?>