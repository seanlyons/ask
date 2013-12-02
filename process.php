<?PHP

if (!isset($argv[1])) {
	echo "input file name required.\n";
	return;
}
$input = $argv[1];

if ($input !== 'all') {
	go($input);
} else {
	$all_files = scandir('./');
	foreach ($all_files as $k => $v) {
		$match = '/^askreddit_[0-9]+\.html$/';
		if (preg_match($match, $v)) {
			go($v);
			rename($v, 'USED_'.$v);
		}
	}
}

function go ( $input ) {
	if (strpos($input, 'html') === FALSE) {
		echo "input file name required.\n";
		return;
	}

	$dom = new DomDocument();
	@$dom->loadHTMLFile($input);
	$finder = new DomXPath($dom);

	$classname="title";
	$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), '$classname')]");
	print_r($nodes);

	for ($i = 0; $i < $nodes->length; $i++) {
		$nv = $nodes->item($i)->nodeValue;
		if (strpos($nv, 'self.AskReddit') === FALSE) {
			$qa[] = $nv;
		}
	}
	print_r($qa);
	for ($i = 0; $i <= 4; $i++) {
		array_pop($qa);
	}
	unset($qa[0], $qa[1], $qa[2]);
	print_r($qa);
    foreach ($qa as $k => $v) {
        $fixed[sha1($v)] = $v;
    }    
    
    $master_doc = file('master_doc.txt', FILE_IGNORE_NEW_LINES);
    foreach($master_doc as $k => $v) {
        $md[sha1($v)] = $v;
    }
    $merged = array_merge($master_doc, $fixed);
	$string = implode("\n", $merged);
	$string .= "\n\n";
	file_put_contents('master_doc.txt', $string);
}