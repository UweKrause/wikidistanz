<?php

/**
 * Sets the areacode which defines which Wikipedia language will be used
 */
 
// see https://meta.wikimedia.org/wiki/List_of_Wikipedias
// for different areacodes

define('AREACODE', 'de');


/**
 * Constants for the program
 */
 
 
 define('CACHEDIR', 'cache_'. AREACODE. '_article');
 
 define('BACKLINKCACHEDIR', 'cache_'. AREACODE. '_backlinks/');
 define('ARTICLECACHEDIR', 'cache_'. AREACODE. '_article/');


/**
 * Constants for API call
 */
 
define('API_Base', 'https://'. AREACODE.'.wikipedia.org/w/api.php');
define('API_Action', '?action=query&utf8=true&format=json');

define('API_Backlinks_Artikel', '&list=backlinks&blnamespace=0&bllimit=max');

define('API_Prop_Artikellinks', '&prop=links&plnamespace=0&pllimit=max');

/**
 * Prepare and load subfunctions
 */

require_once('functions/server_util.php');
require_once('functions/cache_links.php');
require_once('functions/cache_backlinks.php');
require_once('functions/microtime_float.php');
require_once('functions/wikipedia_util.php');


?>