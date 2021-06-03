<?php
$thisDir = preg_replace("#(.+/)([^/]+$)#","$1",$_SERVER["SCRIPT_URL"]); #print $thisDir;

$cnt = 0;

$files = array_merge(loadDirSimple("./",".*","f"), loadDirSimple("./",".*","d"));
if (sizeof($files)>0) { ?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<title>Eclipse EPP Download Links via mirror</title>
</head>
<body>
<?php
$downloadPrefix = "http://www.eclipse.org/downloads/download.php?file=";
$downloadDotEclipseServer = preg_match("#download.eclipse.org#",$_SERVER["DOCUMENT_ROOT"]) || preg_match("#download.eclipse.org#",$_SERVER["SERVER_NAME"]) || preg_match("#download.eclipse.org#",$_SERVER["SCRIPT_URI"]);

echo "<table>\n";
echo "<tr class=\"h\"><td colspan=\"3\"><h1 class=\"p\">Eclipse EPP Download Links via mirror</h1></td></tr>";
sort($files);
foreach ($files as $file) {
	$cnt++;
	if ($file != ".htaccess" && false===strpos($file,"index.") && $file != "CVS")
	{
		if (is_file($file))
		{
			$downloadSize = filesize("$file");
			echo '<tr><td> &#149; <a href="' . ($downloadDotEclipseServer ? $downloadPrefix . $thisDir : '') . $file . '">' . $file. '</a> (' . pretty_size($downloadSize) . ')</td></tr>';
		}
		else
		{
			echo '<tr><td> &#149; <a href="' . $file . '">' . $file. '</a></td></tr>';
		}
	}
}
echo "</table>\n";
} else {
	echo "No files found!";
}
print "<p>&nbsp;</p>";

function loadDirSimple($dir,$ext,$type) { // 1D array
	$stuff = array();
	if (is_dir($dir) && is_readable($dir)) {
		$handle=opendir($dir);
		while (($file = readdir($handle))!==false) {
			if ( ($ext=="" || preg_match("/".$ext."$/",$file)) && $file!=".." && $file!=".") {
				if (($type=="f" && is_file($file)) || ($type=="d" && is_dir($file))) {
					$stuff[] = "$file";
				}
			}
		}
		closedir($handle);
	}
	return $stuff;
}

function pretty_size($bytes)
{
	$sufs = array("B", "K", "M", "G", "T", "P"); //we shouldn't be larger than 999.9 petabytes any time soon, hopefully
	$suf = 0;

	while($bytes >= 1000)
	{
		$bytes /= 1024;
		$suf++;
	}

	return sprintf("%3.1f%s", $bytes, $sufs[$suf]);
}
?>
