<?php

/*
Was ist schneller?
Die API-Aufrufe als JSON cachen oder als Array mit Serialize?
Die Frage scheint nicht deutlich geklaert zu sein und haengt von mehreren Faktoren ab,
daher kann es sich lohnen das auf dem Server auszuprobieren, auf dem das Script liegt.

Vergleichscode von Peter Bailey, Stackoverflow
https://stackoverflow.com/questions/804045/preferred-method-to-store-php-arrays-json-encode-vs-serialize
*/

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Make a big, honkin test array
// You may need to adjust this depth to avoid memory limit errors
$testArray = fillArray(0, 5);

// Time json encoding
$start = microtime(true);
json_encode($testArray);
$jsonTime = microtime(true) - $start;
echo "JSON encoded in $jsonTime seconds\n";

// Time serialization
$start = microtime(true);
serialize($testArray);
$serializeTime = microtime(true) - $start;
echo "PHP serialized in $serializeTime seconds\n";

// Compare them
if ($jsonTime < $serializeTime) {
    printf("json_encode() was roughly %01.2f%% faster than serialize()\n", ($serializeTime / $jsonTime - 1) * 100);
}
elseif ($serializeTime < $jsonTime ) {
    printf("serialize() was roughly %01.2f%% faster than json_encode()\n", ($jsonTime / $serializeTime - 1) * 100);
} else {
    echo "Unpossible!\n";
}

function fillArray( $depth, $max ) {
    static $seed;
    if (is_null($seed)) {
        $seed = array('a', 2, 'c', 4, 'e', 6, 'g', 8, 'i', 10);
    }
    if ($depth < $max) {
        $node = array();
        foreach ($seed as $key) {
            $node[$key] = fillArray($depth + 1, $max);
        }
        return $node;
    }
    return 'empty';
}

?>