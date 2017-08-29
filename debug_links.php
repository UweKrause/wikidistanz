<?php

require_once('functions.php');

// ToDo: bessere input sanitation
$requestartikel = rawurlencode(trim($_GET['artikel']));
$requestartikel_decode = rawurldecode($requestartikel);

$links = cache_links($requestartikel);

print('<pre>');
print_r($links);
print('</pre>');


?>