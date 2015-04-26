<?php
namespace pineapple;
$pineapple = new Pineapple(__FILE__);
define('__INCLUDES__', $pineapple->directory . "/includes/");
define('__CONFIG__', __INCLUDES__ . "config"); 
define('__LOGS__', __INCLUDES__ . "logs/");
define('__SCRIPTS__', __INCLUDES__ . "scripts/");
define('__INJECTS__', __SCRIPTS__ . "injects/");
define('__INJECTJS__', __INJECTS__ . $_POST['module'] . "/injectJS.txt");
define('__INJECTCSS__', __INJECTS__ . $_POST['module'] . "/injectCSS.txt");
define('__INJECTHTML__', __INJECTS__ . $_POST['module'] . "/injectHTML.txt");
define('__AUTHPHP__', __INJECTS__ . $_POST['module'] . "/auth.php");
define('__JSBAK__', __INJECTS__ . $_POST['module'] . "/backups/injectJS.txt");
define('__HTMLBAK__', __INJECTS__ . $_POST['module'] . "/backups/injectHTML.txt");
define('__AUTHBAK__', __INJECTS__ . $_POST['module'] . "/backups/auth.php");
define('__CSSBAK__', __INJECTS__ . $_POST['module'] . "/backups/injectCSS.txt");

$configs = array();
loadConfigData($configs);

$foundStatus = "<span style='color: #ff0000;'>Captive Portal Detected</span>";
$notFoundStatus = "<span style='color: #34D134;'>No Captive Portal Detected</span>";

if (isset($_POST['updateStatus'])) {
	echo portalExists();
} else if (isset($_POST['configureTServer'])) {
	$configs['testSite'] = $_POST['testSite'];
	$configs['dataExpected'] = $_POST['dataExpected'];
	echo saveConfigData($configs);
} else if (isset($_POST['updateConfig'])) {
	unset($_POST['updateConfig']);
	echo saveConfigData($_POST);
} else if (isset($_POST['installDepends'])) {
	echo installDepends();
} else if (isset($_POST['moveDepends'])) {
	echo moveDepends();
} else if (isset($_POST['uninstallDepends'])) {
	echo uninstallDepends();
} else if (isset($_POST['autoAuthenticate'])) {
	echo autoAuthenticate();
} else if (isset($_POST['saveInjectJS'])) {
	echo saveClonerFile(__INJECTJS__, $_POST['saveInjectJS']);
} else if (isset($_POST['saveInjectHTML'])) {
	echo saveClonerFile(__INJECTHTML__, $_POST['saveInjectHTML']);
} else if (isset($_POST['saveAuthPHP'])) {
	echo saveClonerFile(__AUTHPHP__, $_POST['saveAuthPHP']);
} else if (isset($_POST['saveInjectCSS'])) {
	echo saveClonerFile(__INJECTCSS__, $_POST['saveInjectCSS']);
} else if (isset($_POST['checkPortalName'])) {
	echo clonedPortalExists($_POST['checkPortalName']);
} else if (isset($_POST['clonePortal'])) {
	echo clonePortal($_POST['clonePortal'], $_POST['options'], $_POST['module']);
} else if (isset($_POST['configurePA'])) {
	echo configurePA();
} else if (isset($_POST['activatePortal'])) {
	echo activatePortal($_POST['activatePortal'], $_POST['module']);
} else if (isset($_POST['checkIsOnline'])) {
	echo checkIsOnline();
} else if (isset($_POST['backupFile'])) {
	switch ($_POST['backupFile']) {
		case "injectjs":
			saveClonerFile(__INJECTJS__, $_POST['backupData']);
			echo backupFile(__INJECTJS__, __JSBAK__);
			break;
		case "injecthtml":
			saveClonerFile(__INJECTHTML__, $_POST['backupData']);
			echo backupFile(__INJECTHTML__, __HTMLBAK__);
			break;
		case "authphp":
			saveClonerFile(__AUTHPHP__, $_POST['backupData']);
			echo backupFile(__AUTHPHP__, __AUTHBAK__);
			break;
		case "injectcss":
			saveClonerFile(__INJECTCSS__, $_POST['backupData']);
			echo backupFile(__INJECTCSS__, __CSSBAK__);
			break;
		default:
			break;
	}
	echo false;
} else if (isset($_POST['restoreFile'])) {
	switch ($_POST['restoreFile']) {
		case "injectjs":
			echo restoreFile(__INJECTJS__, __JSBAK__);
			break;
		case "injecthtml":
			echo restoreFile(__INJECTHTML__, __HTMLBAK__);
			break;
		case "authphp":
			echo restoreFile(__AUTHPHP__, __AUTHBAK__);
			break;
		case "injectcss":
			echo restoreFile(__INJECTCSS__, __CSSBAK__);
			break;
		default:
			break;
	}
	echo false;
} else if (isset($_POST['scanNetwork'])) {
	echo scanNetwork();
} else if (isset($_POST['allow_mac_collection'])) {
	echo allowsMACCollection();
} else if (isset($_POST['saveCapturedMACs'])) {
	echo saveCapturedMACs($_POST['saveCapturedMACs']);
} else if (isset($_POST['getCapturedMACs'])) {
	echo getCapturedMACs();
} else if (isset($_POST['retrieveLog'])) {
	echo retrieveLog($_POST['retrieveLog']);
} else if (isset($_POST['deleteLog'])) {
	echo deleteLog($_POST['deleteLog']);
} else if (isset($_POST['refreshAuthLog'])) {
	echo refreshAuthLog();
} else if (isset($_POST['createInjectionSet'])) {
	echo createInjectionSet($_POST['createInjectionSet']);
} else if (isset($_POST['getInjectionSets'])) {
	echo getInjectionSets();
} else if (isset($_POST['deleteInjectionSet'])) {
	echo deleteInjectionSet($_POST['deleteInjectionSet']);
} else if (isset($_POST['getInjectionFile'])) {
	echo getInjectionFile($_POST['getInjectionFile'], $_POST['module']);
} else if (isset($_POST['exportInjectionSet'])) {
	echo exportInjectionSet($_POST['exportInjectionSet']);
} else if (isset($_GET['downloadInjectionSet'])) {
	downloadInjectionSet($_GET['downloadInjectionSet']);
} else if (isset($_FILES['importFile'])) {
	echo importInjectionSet($_FILES['importFile']);
} else if (isset($_POST['activateAuthPHP'])) {
	echo activateAuthPHP($_POST['module']);
} else if (isset($_POST['downloadExternalInjectionSet'])) {
	echo downloadExternalInjectionSet($_POST['downloadExternalInjectionSet']);
} else if (isset($_POST['fetchAvailableInjectionSets'])) {
	echo fetchAvailableInjectionSets();
}

function portalExists() {
	global $configs;
	if (strcmp(file_get_contents($configs['testSite']), $configs['dataExpected']) == 0) {
        return false;
	} else {
		return true;
	}
}

function autoAuthenticate() {
	global $configs;
	$args = implode(" ", explode(";", $configs['tags']));
	$data = array();
	$res = exec("python " . __SCRIPTS__ . "portalauth.py " . $args . " 2>&1", $data);
	if ($res != "Complete") {
		logError("auto_auth_error", implode("\r\n",$data));
	}
	return $res;
}

function checkIsOnline() {
	global $pineapple;
	return $pineapple->online();
}

function clonedPortalExists($name) {
	global $configs;
	if (file_exists($configs['p_archive'] . $name)) {
		return true;
	}
	return false;
}

function clonePortal($name, $opts, $injectionSet) {
	global $configs;
	if (clonedPortalExists($name)) {
		// Delete the current portal
		rrmdir($configs['p_archive'] . $name);
	}
	$data = array();
	$res = exec("python " . __SCRIPTS__ . "portalclone.py " . $name . " " . $configs['p_archive'] . " '" . $opts . "' " . $configs['testSite'] . " '" . $injectionSet . "' 2>&1", $data);
	if ($res == "Complete") {
		return true;
	}
	logError("clone_error", implode("\r\n",$data));
	return false;
}

function activatePortal($name, $injectSet) {
	global $configs;
	$data = array();
	$res = exec(__SCRIPTS__ . "activateportal.sh " . $configs['p_archive'] . " " . $name . " " . $injectSet, $data);
	if ($res == "") {
		return true;
	}
	logError("activate_portal_error", implode("\r\n",$data));
	return false;
}

function activateAuthPHP($injectSet) {
	if (copy(__INJECTS__ . $injectSet . "/auth.php", "/www/nodogsplash/auth.php")) {
		return true;
	}
	return false;
}

function scanNetwork() {
	$data = array();
	$res = exec(__SCRIPTS__ . "scan.sh", $data);
	if ($res == "failed") {
		logError("mac_scan_error", implode("\r\n",$data));
		return false;
	} else {
		return implode(";",$data);
	}
}

function allowsMACCollection() {
	global $configs;
	if ($configs['mac_collection'] == "") {
		return false;
	}
	return true;
}

function saveCapturedMACs($macs) {
	$fh = fopen(__INCLUDES__ . "macs.txt", "w+");
	fwrite($fh, $macs);
	fclose($fh);
}

function refreshAuthLog() {
	return file_get_contents('/www/nodogsplash/auth.log');
}

function getCapturedMACs() {
	return file_get_contents(__INCLUDES__ . "macs.txt");
}

/* DEPENDENCY FUNCTIONS */

function tserverConfigured() {
	global $configs;
	loadConfigData($configs);
	if (empty($configs['testSite']) || empty($configs['dataExpected'])) {
		return false;
	}
	return true;
}

function isConfigured() {
	if (file_exists("/www/nodogsplash/auth.php") && file_exists("/www/nodogsplash/jquery.min.js")) {
		return true;
	}
	return false;
}

function configurePA() {
	$data = array();
	$res = exec(__SCRIPTS__ . "config.sh 2>&1", $data);
	if ($res == "") {
		return true;
	}
	logError("configure_error", implode("\r\n",$data));
	return false;
}

function dependsInstalled() {
	$res = exec(__SCRIPTS__ . "check_depends.sh");
	if ($res == "Installed") {
		return true;
	}
	return false;
}

function installDepends() {
	uninstallDepends();
	$data = array();
	$res = exec(__SCRIPTS__ . "install_depends.sh 2>&1", $data);
	if ($res == "Complete") {
		return true;
	}
	logError("install_depends_error", implode("\r\n",$data));
	return false;
}

function uninstallDepends() {
	$data = array();
	$res = exec(__SCRIPTS__ . "remove_depends.sh 2>&1", $data);
	if ($res == "Complete") {
		return true;
	}
	logError("uninstall_depends_error", implode("\r\n",$data));
	return false;
}

/* RESTORATION FUNCTIONS */

function restoreFile($localFile, $remoteFile) {
	$file = file_get_contents($remoteFile);
	if ($file) {
		saveClonerFile($localFile, $file);
		return $file;
	}
	return false;
}

/* FILE SAVE FUNCTIONS */

function saveClonerFile($filename, $data) {
	$fh = fopen($filename, "w+");
	if ($fh) {
		fwrite($fh, $data);
		fclose($fh);
		return true;
	}
	return false;
}

function saveConfigData($data) {
	$fh = fopen(__CONFIG__, "w+");
	if ($fh) {
		foreach ($data as $key => $value) {
			fwrite($fh, $key . "=" . $value . "\n");
		}
		fclose($fh);
		return true;
	}
	return false;
}

function loadConfigData(&$configs) {
	$config_file = fopen(__CONFIG__, "r");
	if ($config_file) {
		while (($line = fgets($config_file)) !== false) {
			$item = explode("=", $line);
			$key = $item[0]; $val = trim($item[1]);
			$configs[$key] = $val;
		}
	}
	fclose($config_file);
}

function backupFile($fileName, $backupFile) {
	// Attempt to create a backups directory in case it doesn't exist
	mkdir(dirname($backupFile));
	if (copy($fileName, $backupFile)) {
		return true;
	}
	return false;
}

/* INJECTION SET FUNCTIONS */
function createInjectionSet($setName) {
	// Check if the directory exists
	if (file_exists(__INJECTS__ . $setName)) {
		return "Injection set already exists";
	}

	// Create a directory for the set
	if (!mkdir(__INJECTS__ . $setName)) {
		logError("createInjectionSet", "Failed to create directory structure");
		return false;
	}
	// Create each of the Inject files
	$files = ["/injectJS.txt","/injectCSS.txt","/injectHTML.txt","/auth.php"];
	foreach ($files as $file) {
		$fh = fopen(__INJECTS__ . $setName . $file, "w+");
		if (!$fh) {
			logError("createInjectionSetFiles", "Failed to create " . $setName . $file);
			fclose($fh);
			return false;
		}
		fwrite($fh, "/* Replace me with your code */");
		fclose($fh);
	}
	return true;
}

function exportInjectionSet($setName) {
	$data = array();
	$res = exec(__SCRIPTS__ . "packInjectionSet.sh " . $setName, $data);
	if ($res != "Complete") {
		logError("exportInjectionSet", $data);
		return false;
	}
	return true;
}

function downloadInjectionSet($setName) {
    $file = "downloads/".$setName.".tar.gz";
    if (file_exists($file)) {
        header_remove();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$setName.'.tar.gz');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
    }
}

function fetchAvailableInjectionSets() {
	$data = array();
	$res = exec("python " . __SCRIPTS__ . "retrieveInjectionSetLinks.py", $data);
	$html = "<table class='pa_config_table'>";
    foreach ($data as $row) {
        $cells = explode(";", $row);
        $html .= "<tr><td><a href='#' class='pa_externalInjectionSet' url='" . $cells[1] . "'>Download</a></td><td><h2 class='pa_h2' style='display:inline'>" . $cells[0] . "</h2></td></tr><tr><td colspan='2'><hr /></td></tr>";
    }
    $html .= "</table>";
	return $html;
}

function downloadExternalInjectionSet($url) {
	$data = array();
	$res = exec(__SCRIPTS__ . "downloadExternalInjectionSet.sh " . $url, $data);
	if ($res != "Complete") {
		return false;
	}
	return true;
}

function importInjectionSet($file) {
	$data = array();
	$file['name'] = str_replace(array( '(', ')' ), '', $file['name']);
	$uploadfile = __INJECTS__ . basename($file['name']);
	if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
		exec(__SCRIPTS__ . "unpackInjectionSet.sh " . $file['name'], $data);
		return "Import Successful!";
	}
	return "Import Failed!";
}

function getInjectionSets() {
	$dirs = scandir(__INJECTS__);
	// Remove the . and .. directories from the results
	unset($dirs[0]); unset($dirs[1]);
	return implode(";", $dirs);
}

function getInjectionFile($fileName, $setName) {
	if (file_exists(__INJECTS__ . $setName . "/" . $fileName)) {
		return file_get_contents(__INJECTS__ . $setName . "/" . $fileName);
	}
	return false;
}

function deleteInjectionSet($setName) {
	rrmdir(__INJECTS__ . $setName);
	return true;
}

/* ERROR LOG FUNCTIONS */
function logError($filename, $data) {
	$time = exec("date +'%H_%M_%S'");
	$fh = fopen(__LOGS__ . $filename . "_" . $time . ".txt", "w+");
	fwrite($fh, $data);
	fclose($fh);
}
function retrieveLog($logname) {
	return file_get_contents(__LOGS__ . $logname);
}
function deleteLog($logname) {
	return unlink(__LOGS__ . $logname);
}

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") {
					rrmdir($dir."/".$object);
				} else {
					unlink($dir."/".$object);
				}
			}
		}
		reset($objects);
		rmdir($dir);
	}
}
?>
