#!/usr/bin/php
<?php
/* echo "This script tests every repository to see if it can be downloaded.  This will take quite a while\n\n";
$templates = json_decode(file_get_contents("/tmp/community.applications/tempFiles/templates.json"),true);

foreach ($templates as $template) {
        if ($template['Plugin'] || $template['LanguageURL']) {
                continue;
        }
        if ( $template['Blacklist'] || $template['BranchID'] ) { continue; }
        echo "Pulling {$template['Repository']}\n";
        exec("timeout -s9 5 docker pull {$template['Repository']}",$output,$error);
        if ( $error && $error != 9) {
                $downloadError .= "{$template['Repository']}\n";
        }
        exec("docker rmi {$template['Repository']}> /dev/null 2>&1");
}
echo "The following repositories could not be downloaded:\n\n$downloadError\n\n  It is recommended to delete and recreate the docker.img after running this script\n";*/
############################################################################

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

