<?php

function microtime_float() {
	/* gibt die aktuelle Zeit in Milisekunden zurueck,
	 * nuetzlich fuer die messung der Ausfuehrungsdauer von Funktionen, Scripten, etc.
	 */
	
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

?>