#!/usr/bin/php
<?
$appPaths['AppFeed']         = "/tmp/GitHub/AppFeed/applicationFeed.json";
$appPaths['repos']           = "/tmp/GitHub/AppFeed/repositoryList.json";

$appfeed = json_decode(file_get_contents($appPaths['AppFeed']),true);
foreach ($appfeed['repositories'] as $repo => $entry) {
	$repos = $entry;
	$repos['title'] = $repo;
	if ( $repos['bio'] )
		$bioRepo[] = $repos;
	else
		$nonbioRepo[] = $repos;
		
//	$allRepos[] = $repos;
}

$sortOrder['sortBy'] == "bio";
$sortOrder['sortDir'] = "Up";
usort($bioRepo,"mySort");
usort($nonbioRepo,"mySort");

$allRepos = array_merge($bioRepo,$nonbioRepo);
$allRepos = array_map("indexer",$allRepos,array_keys($allRepos));

file_put_contents($appPaths['repos'],json_encode($allRepos,JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

function indexer($a,$k) {
	$a['index'] = $k;
	return $a;
}
#######################
# Custom sort routine #
#######################
function mySort($a, $b) {

	return (strtolower($a['title']) <=> strtolower($b['title']));
}
?>
