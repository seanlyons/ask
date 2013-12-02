<?PHP

while (TRUE) {
    $url = "www.reddit.com/r/AskReddit";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $head = curl_exec($ch);
    curl_close($ch);
    
    file_put_contents('askreddit_'.time().'.html', $head);

    for($i = 0; $i <= 1800; $i++) {
        echo "$i\n";
		sleep(1);
    }
}
