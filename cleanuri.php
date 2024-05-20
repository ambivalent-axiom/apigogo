<?php
include 'ApiGoGo.php';
$shorten = new ShortenUri('https://cleanuri.com/api/v1/shorten');
$result = json_decode($shorten->shortenUri(trim(readline("URL: "))));
echo $result->result_url;

