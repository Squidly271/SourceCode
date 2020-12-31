#!/usr/bin/php
<?php
$feed = json_decode(file_get_contents("/tmp/GitHub/AppFeed/applicationFeed.json"),true);
$templates = $feed['applist'];
$index = @file_get_contents("/tmp/GitHub/AppFeed/lastcheckIndex");
if ( !$index )
	$index = 0;

$endapp = count($templates);

for ($i = 1; $i<50;$i++ ) {
	$template = $templates[$index];
	if ($template['Blacklist'] || $template['BranchID']) continue;
	echo "Pulling {$template['Repository']}\n";
	exec("timeout -s9 5 docker pull {$template['Repository']}",$output,$error);
	if ( $error && $error != 9 ) {
		exec("/usr/local/emhttp/plugins/dynamix/scripts/notify -e 'Download Failure' -s '{$template['Repository']}' -i 'alert'");
	}
	exec("docker rmi {$template['Repository']}> /dev/null 2>&1");
	$index++;
	if ( $index >= $endapp ) {
		$index = 0;
		break;
	}
}
file_put_contents("/tmp/GitHub/AppFeed/lastcheckIndex",$index);
?>

