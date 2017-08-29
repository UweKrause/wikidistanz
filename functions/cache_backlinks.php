<?php
/**
 * BACKLINKS
 */
function cache_backlinks($requestartikel) {
	// initialer Artikel wird geladen.
	// Achtung, Variable wird ueberschrieben, wenn die API fuer einen Artikel mehrmals aufgerufen werden muss
	
	
	/**
	 * TODO: Case insensitive Speicherung der DAtei!!!
	 */
	
	if(file_exists(BACKLINKCACHEDIR. $requestartikel)) {
		//print("Aus CACHE geladen\n");
		
		$cache_artikel_available = true;
		
		$cache = unserialize(file_get_contents(BACKLINKCACHEDIR. $requestartikel));
		
		return $cache;
		
	} else {
		//print("kein CACHE gefunden, Stelle Anfrage an API\n");
		
		$artikeljson = json_decode(file_get_contents(API_Base. API_Action. API_Backlinks_Artikel. '&bltitle='. $requestartikel), true);
		
		$cache_artikel_available = false;
		
		if($artikeljson['query']['backlinks'] != []) {
			// Ein Artikel wurde gefunden, suche nach Verlinkungen
			
			do {
				
				foreach($artikeljson['query']['backlinks'] as $k => $v){
					// fuegt jeden gefundenen Backlink hinzu
					
					$cache[] = $v['title'];
					
				}
				
				if (isset($artikeljson['continue']['blcontinue'])) {
					// Wenn der aktuelle API-Call sagt, dass noch mehr Werte beim Folgeaufruf warten, bereite Folgeaufruf vor
					//print("Mehr!");
					
					$blcontinue = $artikeljson['continue']['blcontinue'];
					$artikeljson = json_decode(file_get_contents(API_Base. API_Action. API_Backlinks_Artikel. '&bltitle='. $requestartikel. '&blcontinue=' . $blcontinue), true);
					
					
				} else {
					// Artikel wurde komplett eingelesen.
					//print("fertig!");
					
					file_put_contents(BACKLINKCACHEDIR. $requestartikel, serialize($cache));
					
					$blcontinue = false;
				}
				
				// wiederhole, solange noch weitere Links bei naechstem API Call fuer den aktuellen Artikel folgen
			} while ($blcontinue != false);
			
			return $cache;
			
		} else {
			
			file_put_contents(BACKLINKCACHEDIR. $requestartikel, serialize([]));
			
			//print("<p>Kein Artikel <strong>$requestartikel</strong> gefunden</p>");
			return [];
		}
		
	}
	
}
?>