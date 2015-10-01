<!DOCTYPE html>
<head>
<style>@import url('<?php echo $rel_dir; ?>/includes/css/infusion.css')</style>
<script src="/components/infusions/portalauth/includes/js/infusion.js" type="text/javascript"></script>
<script>
$('a').on("click",function(){
	$('div.changelog_version').slideUp("slow");
	var id = $(this).attr("id");
	$('div[id="'+id+'"]').slideDown("slow");
});
</script>
</head>
<body>
<div style="overflow: auto">
<a href='#' class='displayVersion' id='v2.9'>Version 2.9 - Released Oct 1, 2015</a>
<div id='v2.9' class='changelog_version' style="display: block">
<pre>
	[->] Fixed bugs that prevented depends from being installed on newly flashed devices.
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v2.8'>Version 2.8 - Released Sept 23, 2015</a>
<div id='v2.8' class='changelog_version'>
<pre>
	[->] Added Payload tab which includes the Portal Auth Shell Server (PASS), payload upload center, and a default payload for Windows and OS X.
	
	[->] Modified the auth log tab to auto refresh.
	
	[->] Moved the Test Website and depends back to PuffyCode.com.
	
	[->] Added the Payloader injection set for delivering payloads to victim machines.
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v2.7'>Version 2.7 - Released Aug 30, 2015</a>
<div id='v2.7' class='changelog_version'>
<pre>
	[->] Added support for downloading multiple files with the same name from a site.
	
	[->] Fixed encoding/decoding issue with external CSS files that would cause the operation to crash.
	
	[->] Removed AP search from command line executable and updated cloning options.
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v2.6'>Version 2.6 - Released Aug 27, 2015</a>
<div id='v2.6' class='changelog_version'>
<pre>
	[->] Moved dependency downloads back to PuffyCode.com since they were randomly deleted from InfoTomb.com.
	
	[->] Added SetupTools dependency for installation of other dependencies.
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v2.5'>Version 2.5 - Released Mar 08, 2015</a>
<div id='v2.5' class='changelog_version'>
<pre>
	[->] Added support for downloading images referenced within the style attribute of element tags.

	[->] Fixed the back up and restore links for InjectCSS.

	[->] Added the ability to download Injection Sets directly to the Pineapple.
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v2.4'>Version 2.4 - Released Mar 04, 2015</a>
<div id='v2.4' class='changelog_version'>
<pre>
	[->] Added Injection Sets which can be created, exported, shared, and imported between Pineapple users.
	     Inject Sets work like previous versions of Injections but are now modular and can be chosen when cloning a portal.

	[->] Fixed a bug with portals that use self-signed SSL certificates.
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v2.3'>Version 2.3 - Released Feb 21, 2015</a>
<div id='v2.3' class='changelog_version'>
<pre>
	[->] Added CSS parsing when cloning a portal so images referenced in CSS files are downloaded.
	
	[->] Added a tab for Auth Log to show captured credentials
	
	[->] Portals are now cloned significantly faster.
	
	[->] The default Test Website has been changed to InfoTomb.  The request is made via HTTPS unlike past requests.
	
	[->] Dependencies are now downloaded from InfoTomb, an anonymous file hosting site.
	     All download sessions are SSL enabled and MD5 checksums are verified for every download.
	
	[->] Dependencies are no longer installed through setup scripts but instead are copied to /sd/depends/
	     making the installation process much faster.  The size of each archive has also been reduced making 
	     the download time shorter.
	
	[->] The SSL version of wget is now installed via opkg if not already installed on the Pineapple.
	     This is for downloading dependencies via HTTPS.
	
	[->] Fixed issue caused by reinstalling dependencies after updating PortalAuth.
	
	[->] Fixed issue with following relative URLs in meta refreshes
	
	[->] External JS files are now downloaded into the images directory and the link modified within the HTML
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v2.2'>Version 2.2 - Released Feb 07, 2015</a>
<div id='v2.2' class='changelog_version'>
<pre>
	[->] Fixed meta refresh bugs in cloning and authentication
	
	[->] Added Error Logs tab
	
	[->] Added client MAC collection
	
	[->] Updated the layout of the change log tab
	
	[->] Fixed layout issues on mobile devices
	
	[->] Made minor changes to the UI
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v2.1'>Version 2.1 - Released Jan 23, 2015</a>
<div id='v2.1' class='changelog_version'>
<pre>
	[->] Removed Check Portal button. Refreshes can now be performed by clicking the refresh button in the top right corner of the small tile.
	
	[->] Made the auto-authenticator more robust.  It now searches for more content and accounts for redirects, relative URLs, and meta refreshes.  *still in beta though*
	
	[->] Made the portal cloner more robust.  It now searches for files based on relative URLs, accounts for redirects and meta refreshes, and now accepts multiple options for how a portal is cloned.
	
	[->] Updated the UI to include portal cloning options.
	
	[->] Updated the configuration script.
	
	[->] Modified the default InjectJS and InjectHTML files.
	
	[->] Added an InjectCSS file.
	
	[->] Added the ability to restore InjectJS, InjectCSS, InjectHTML, and auth.php files.
	
	[->] Fixed a bug where the small tile displayed 'Captive Portal Detected' when the Pineapple is offline.  The new message displays 'Pineapple must be online to use PortalAuth'.
	
	[->] Fixed a bug in the Portal Cloner that would add multiple login forms to the document.
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v2.0'>Version 2.0 - Released Jan 17, 2015</a>
<div id='v2.0' class='changelog_version'>
<pre>
	[->] Added the ability to clone a captive portal.  The portal can be activated upon cloning and managed with Evil Portal II.
	
	[->] Updated user interface.
	
	[->] Added Injects tab to allow user-defined JavaScript and HTML for injecting into a cloned portal.
	
	[->] Included JS and HTML injects for creating a basic username & password form.
	
	[->] Included a standard auth.php file for capturing credentials.
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v1.1'>Version 1.1 - Released Jan 14, 2015</a>
<div id='v1.1' class='changelog_version'>
<pre>
	[->] Added the option to auto-authenticate with a captive portal.

	[->] Updated UI that now alerts you to what is happening.  A progress bar appears when checking, loading, and auto-authenticating with captive portals.
	
	[->] Updated the Portal tab to display a progress bar and loading message when loading a portal.
	
	[->] Moved the Portal tab one position to the right so it won't automatically load every time the large tile is opened.
	
	[->] Added the Element Tags option in the Config tab for user-defined elements to search for when auto-authenticating.
	
	[->] Dependencies are now required for PortalAuth but can be removed in the Config tab.  Only remove if you desire to uninstall PortalAuth.

	[->] Removed the Last Checked timestamp from the small tile.
</pre>
</div>
<br />
<a href='#' class='displayVersion' id='v1.0'>Version 1.0 - Released Jan 09, 2015</a>
<div id='v1.0' class='changelog_version'>
<pre>
	[->] Infusion Created
</pre>
</div>
</div>
</body>
</html>