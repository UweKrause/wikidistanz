<?php

/**
 * IMMER vor Aufruf rawurl_encode!!!
 */
function cache_links($requestartikel) {
	
	
	/**
	 * TODO: bei abweichender Schreibweise die Datei in der normalisierten Art speichern ?
	 */
	
	$ret = [];
	
	// initialer Artikel wird geladen.
	// Achtung, Variable wird ueberschrieben, wenn die API fuer einen Artikel mehrmals aufgerufen werden muss
	
	if(file_exists(ARTICLECACHEDIR. $requestartikel)) {
		//print("Aus CACHE geladen\n");
		
		$cache_artikel_available = true;		
		$artikeljson = json_decode(file_get_contents(ARTICLECACHEDIR. $requestartikel), true);
		
	} else {
		//print("kein CACHE gefunden, Stelle Anfrage an API\n");
		
		/*
		 *   Um alle Verlinkungen zu bekommen muss die API ggf mehrmals aufgerufen werden,
		 *   da jeder Aufruf nur maximal 500 Eintraege liefert.
		 *   Freundlicherweise liefert ein Aufruf aber auch mit, ob es noch weitere Links gibt und die API bietet an, an einer bestimmten Stelle fortzufahren
		 */
		
		$cache_artikel_available = false;
		
		$api_url = API_Base. API_Action. API_Prop_Artikellinks. '&titles='. $requestartikel;
		$file_get_contents = file_get_contents($api_url);
		$artikeljson = json_decode($file_get_contents, true);
		
	}
	
	if(!isset($artikeljson['query']['pages']['-1'])) {
		// Ein Artikel wurde gefunden, suche nach Verlinkungen
		
		$anzahl_do_durchlaufe = 0;
		do {
			
			foreach($artikeljson['query']['pages'] as $k => $v){
				// geht in den aktuellen Artikel (Schleife wird genau 1 mal ausgefuehrt, eigentlich nur, um bequem an die unbekannte id $k zu kommen)
				
				if (!$cache_artikel_available && $anzahl_do_durchlaufe == 0) {
					// beim ersten Durchlauf wird der Kopf gefuellt
					$cache[] = '{';
					$cache[] = "\t". '"batchcomplete": "",';
					$cache[] = "\t".	'"query": {';
					$cache[] = "\t\t".	'"pages": {';
					$cache[] = "\t\t\t".	'"'. $k. '": {';
					$cache[] = "\t\t\t\t".	'"pageid": '. $k. ',';
					$cache[] = "\t\t\t\t".	'"ns": 0,';
					$cache[] = "\t\t\t\t".    '"title": "'. $v['title']. '",';
					$cache[] = "\t\t\t\t".	'"links": [';
				}
				
				foreach($v['links'] as $id => $content) {
					// durchlauft fuer den aktuellen API Call alle Links
					
					if (!$cache_artikel_available) {
						$cache[] = "\t\t\t\t\t". '{';
						$cache[] = "\t\t\t\t\t\t". '"ns": 0,';
						$cache[] = "\t\t\t\t\t\t".'"title": "'. $content['title']. '"';
						$cache[] = "\t\t\t\t\t". '},'; // dieses Komma muss einmal entfernt werden
					}
					
					$ret[] = $content['title'];
					
				}
				
				if (isset($artikeljson['continue']['plcontinue'])) {
					// wenn aus Cache geladen, gibt es keine Continue, der Block wird nur bei API Call betreten!
					// Wenn der aktuelle API-Call sagt, dass noch mehr Werte beim Folgeaufruf warten, bereite Folgeaufruf vor
					
					$plcontinue = $artikeljson['continue']['plcontinue'];
					$artikeljson = json_decode(file_get_contents(API_Base. API_Action. API_Prop_Artikellinks. '&titles='. $requestartikel. '&plcontinue=' . $plcontinue), true);
					
				} else {
					// Artikel wurde komplett eingelesen.
					$plcontinue = false;
					
					if(!$cache_artikel_available) {
						
						// der letzten Zeile das letzte Komma entfernen
						$last = sizeof($cache) -1;
						$cache[$last] = substr($cache[$last], 0, -1); 
						
						// das Ende der JSON Datei anhaengen
						$cache[] = "\t\t\t\t]";
						$cache[] = "\t\t\t}";
						$cache[] = "\t\t}";
						$cache[] = "\t}";
						$cache[] = "}";
						
						$json_file_complete = implode("\n", $cache);
						
						file_put_contents(ARTICLECACHEDIR. $requestartikel, $json_file_complete);
					}
					
				}
			}
			
			$anzahl_do_durchlaufe++;
			
			// wiederhole, solange noch weitere Links bei naechstem API Call fuer den aktuellen Artikel folgen
		} while ($plcontinue != false);
		
	} else {
		// kein Artikel gefunden
		
		$json_file_complete = '{"batchcomplete": "","query": {"pages": {"-1": {
		"ns": 0,
		"title": "'. $requestartikel. '",
		"missing": ""
	}}}}';
	
	file_put_contents(ARTICLECACHEDIR. $requestartikel, $json_file_complete);
	
	}
	
	return $ret;
	
}

?>