<?PHP

while (TRUE) {
    $url = "www.reddit.com/r/AskReddit";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $head = curl_exec($ch);
    curl_close($ch);
    
    go( $head );

    for($i = 0; $i <= 1800; $i++) {
        if ($i % 100 == 0) {
            echo date(DATE_RFC2822) . " - $i\n";
		}
        sleep(1);
    }
}

function go ( $input ) {
	$dom = new DomDocument();
	@$dom->loadHTML($input);
	$finder = new DomXPath($dom);

	$classname="title";
	$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), '$classname')]");
// print_r($nodes);

	for ($i = 0; $i < $nodes->length; $i++) {
		$nv = $nodes->item($i)->nodeValue;
		if (strpos($nv, 'self.AskReddit') === FALSE) {
			$qa[] = $nv;
		}
	}
// print_r($qa);
	for ($i = 0; $i <= 4; $i++) {
		array_pop($qa);
	}
	unset($qa[0], $qa[1], $qa[2]);
// print_r($qa);
    foreach ($qa as $k => $v) {
        $fixed[sha1($v)] = $v;
    }    
    
    $master_doc = file('master_doc.txt', FILE_IGNORE_NEW_LINES);
    foreach($master_doc as $k => $v) {
        $md[sha1($v)] = $v;
    }
print_r($md);
print_r($fixed);
    $merged = array_merge($md, $fixed);
	$string = implode("\n", $merged);
	$string .= "\n";
	file_put_contents('master_doc.txt', $string);
}