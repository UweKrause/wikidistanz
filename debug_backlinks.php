<?php

require_once('functions.php');

// ToDo: bessere input sanitation
$requestartikel = rawurlencode(trim($_GET['artikel']));
$requestartikel_decode = rawurldecode($requestartikel);

$backlinks = cache_backlinks($requestartikel);

print('<pre>');
print_r($backlinks);
print('</pre>');


?>