<?php

// ToDo: bessere input sanitation
$article_request = rawurlencode(trim($form_start));
$article_request_decode = rawurldecode($article_request);

$article_compare = rawurlencode(trim($form_ziel));
$article_compare_decode = rawurldecode($article_compare);

/**
 * Single point of control
 */
 
// Sets the areacode which defines which Wikipedia language will be used

// see https://meta.wikimedia.org/wiki/List_of_Wikipedias
// for different areacodes

define('AREACODE', 'de');



/**
 * Limit for tries to find a connection between two articels
 */

// 0 fuer kein Limit // STIMMT DAS NOCH???
$limit_dreier = 500;
$limit_vierer = 50;
$limit_fuenfer = 15;


$minimum_links_in_article = 1;
$minimum_backlinks_in_article = 5;


if($article_request != "" && $article_compare != "" ) {
	
	if($article_request == $article_compare) {
		// Anfrage und Vergleichsartikel sind gleich (a == v)
		
		print("<p>Artikel und Vergleich sind gleich!</p>");	
		print("$article_request_decode<br>");	
		
	} else {
		
		//	Man muesste die jetzt noch nicht laden, aber ich wuesste gerne jetzt schon, ob die angeforderten Artikel gueltig sind
		
		$artikellinks = cache_links($article_request);
		$backlinks = cache_backlinks($article_compare);
		
		$count_artikellinks = count($artikellinks);
		$count_backlinks = count($backlinks);
		
		?>
		
		<form>
			<fieldset>
				<legend>Zu &uuml;berpr&uuml;fende Artikel</legend>
				
				"<?= w_url($article_request_decode) ?>" verweist auf <?= $count_artikellinks ?> Artikel.<br>
				<?= $count_backlinks ?> Artikel verweisen auf "<?= w_url($article_compare_decode) ?>".
			</fieldset>
		<p></p>
		<fieldset>
			<legend>Gefundene Pfade</legend>
		
		<?php
		
		if($count_artikellinks >= $minimum_links_in_article && $count_backlinks >= $minimum_backlinks_in_article) {
			
			if(in_array($article_request_decode, $backlinks)) {
				// Vergleichsartikel ist direkt verlinkt ( a => v )
				
//				print("$article_request_decode &rarr; $article_compare_decode<br>");	
				print(w_url($article_request_decode).
					" &rarr; ". w_url($article_compare_decode).
					"</br>\n");	

				
			} else {
				
				
				
				$anzahl_dreierverbindungen = 0;
				
				foreach ($artikellinks as $k => $link) {
					
					if(in_array($link, $backlinks)) {
						// Eine Verlinkung des Artikel ist mit dem Vergleichsartikel verlinkt ( a => x => v )
						
						// print(w_url($article_request_decode). " &rarr; $link &rarr; $article_compare_decode<br>\n");	
						
						print(w_url($article_request_decode).
									" &rarr; ". w_url($link).
									" &rarr; ". w_url($article_compare_decode).
									"</br>\n");	
						
						$anzahl_dreierverbindungen++;
						
						if($anzahl_dreierverbindungen >= $limit_dreier) {
							print("<p>LIMIT $limit_dreier!</p>");
							break 1;
						}
						
					}				
				}
				
				if($anzahl_dreierverbindungen == 0) {
					//echo "<p>keine Dreierverbindungen gefunden, suche nach Vier</p>";
					
					$anzahl_viererverbindungen = 0;
					
					foreach ($artikellinks as $k => $link) {
						
						$ebene3 = cache_links(rawurlencode($link));
						
						foreach($ebene3 as $ebene3artikel) {
							
							if (in_array($ebene3artikel, $backlinks)) {
								// Eine Verlinkung des Artikel ist ueber Umwege mit dem Vergleichsartikel verlinkt ( a => x => y => v )
								
								print(w_url($article_request_decode).
									" &rarr; ". w_url($link).
									" &rarr; ". w_url($ebene3artikel).
									" &rarr; ". w_url($article_compare_decode).
									"</br>\n");	
								
								// Wenn wir eine vierer Verbindung gefunde haben, freuen wir uns ersteinmal
								$anzahl_viererverbindungen++;
								
								if($anzahl_viererverbindungen >= $limit_vierer) {
									print("<p>LIMIT $limit_vierer!</p>");
									break 2;
								}
								
							}
						}
					}
				}
				
				if($anzahl_dreierverbindungen == 0 && $anzahl_viererverbindungen == 0) {
					//echo "<p>keine Viererverbindungen gefunden, suche nach Fuenf</p>";
					
					$anzahl_fuenferverbindungen = 0;
					
					foreach ($artikellinks as $k => $link) {
						
						$ebene3 = cache_links(rawurlencode($link));
						
						foreach($ebene3 as $ebene3artikel) {
							
							$ebene4 = cache_links(rawurlencode($ebene3artikel));
							
							foreach($ebene4 as $ebene4artikel) {
								
								if (in_array($ebene4artikel, $backlinks)) {
									// Eine Verlinkung des Artikel ist ueber Umwege mit dem Vergleichsartikel verlinkt ( a => x => y => z => v )
									
									// print("$article_request_decode &rarr; $link &rarr; $ebene3artikel &rarr; $ebene4artikel &rarr; $article_compare_decode</br>\n");
									
									print(w_url($article_request_decode).
									" &rarr; ". w_url($link).
									" &rarr; ". w_url($ebene3artikel).
									" &rarr; ". w_url($ebene4artikel).
									" &rarr; ". w_url($article_compare_decode).
									"</br>\n");
									
									// Wenn wir eine fuenfer Verbindung gefunde haben, freuen wir uns ersteinmal
									$anzahl_fuenferverbindungen++;
									
									if($anzahl_fuenferverbindungen >= $limit_fuenfer) {
										print("<p>LIMIT $limit_fuenfer!</p>");
										break 3;
									}
								}
							}
						}
					}
				}
				
				if($anzahl_dreierverbindungen == 0 && $anzahl_viererverbindungen == 0 && $anzahl_fuenferverbindungen == 0) {
					
					// (Also so langsam wird aber auch arg imperformant, erstmal abwarten, ob das bei 3 Zwischenschritten ueberhaupt noch funktioniert...)
					
					print("Keine Verbindung in der Form<br>
					Start => X => Y => Z => Ziel (also mit weniger als 5 Clicks)<br>
					gefunden</br>\n");	
					
				}
			}
			
			?>
			</fieldset>
			
			<?php
			
		} else {
			// $minimum_backlinks_in_article
			// $minimum_links_in_article
			print("<p>Artikel oder Vergleichartikel kein gueltiger Wikipedia Artikel!<br>
			(Gross/Kleinschreibung richtig beachtet?)<br>
			Ausserdem gelten folgende Regeln: (experimentell!)<br>
			Mindestanzahl Links bei Startartikel: $minimum_links_in_article<br>
			Mindestanzahl Links ZU Zielartikel: $minimum_backlinks_in_article</p>");
		}
	}
} else {
	print("<p>Artikel oder Vergleichartikel nicht angegeben!</p>");
}
?>