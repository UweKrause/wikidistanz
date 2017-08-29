<?php

/**
 * Programmkonstanten
 */
 
 // hier ggf. Benutzereingabe uebernehmen
 define('LAENDERCODE', 'de');
 
 define('CACHEDIR', 'cache_'. LAENDERCODE. '_article');
 
 define('BACKLINKCACHEDIR', 'cache_'. LAENDERCODE. '_backlinks/');
 define('ARTICLECACHEDIR', 'cache_'. LAENDERCODE. '_article/');



/**
 * Konstanten fuer den API Aufruf
 */
 
define('API_Base', 'https://'. LAENDERCODE.'.wikipedia.org/w/api.php');
define('API_Action', '?action=query&utf8=true&format=json');

define('API_Backlinks_Artikel', '&list=backlinks&blnamespace=0&bllimit=max');

define('API_Prop_Artikellinks', '&prop=links&plnamespace=0&pllimit=max');

/**
 * Unterfunktionen
 */

require_once('functions/cache_links.php');
require_once('functions/cache_backlinks.php');
require_once('functions/microtime_float.php');
require_once('functions/wikipedia_util.php');








?>