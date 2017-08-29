# Wikidistanz

Auf dem Weihnachtsmarkt mit Kommilitonen?
Das kann doch nicht gut enden.
So also auch im Dezember 2016, als jemand das "Wikipediaspiel" zu Sprache bringt.

Die Regeln sind einfach, von einem beliebigen Statartikel ist zu einem bestimmten Zielartikel mit möglichst wenig Klicks durch die Wikipedia zu navigieren.
Alleine ist es ein Spiel gegen die Zeit, mit mehreren ist es die Suche nach dem effizientesten Weg.

Einer der Teilnehmer der Weihnachtsmarktrunde stellte slso bald die Behauptung auf, dass "jeder Artikel in der deutschen Wikipedia mit höchstens 4 Klicks zu Adolf Hitler führt".
(Hey, ich habe mir das Ziel nicht ausgesucht!)

Was wäre ich für ein Informatikstudent, wenn sich nicht sofort die Frage gestellt hätte:
"Wirklich jeder?"

Also habe ich am nächsten Tag überlegt, ob sich diese Behauptung beweisen oder widerlegen lässt, hierfür dieses kleine Script.

# Vorgehensweise:
Über die Wikipedia-API werden die Links, die von dem Startartikel wegführen gesammelt.
Da die Wikipedia-API die Anzahl der Aufrufe limitiert, indem jeder Aufruf eine Sekunde Reaktionszeit kostet, werden die Aufrufe gecached.
Da die Wikipedia-API zusätzlich für jeden Artikel nur eine bestimmte Anzahl an Links pro API Call rausgibt, werden die Antworten solange zusammengesetzt, bis die Version im Cache vollständig ist.

Für den Zielartikel werden die Links, die AUF ihn zeigen gecached.

Nun wird für den Startartikel geprüft, ob er bereits auf den Zielartikel zeigt (S -> Z)
oder ob einer der vom Startartikel verlinkten Artikel auf den Zielartikel verlinkt (S -> X -> Z).

Ist beides nicht der Fall, wird ein weiterer Zwischenschritt durchgeführt.


Fazit:
Bisher wurde kein einziger Artikel gefunden, der mehr als 4 Klicks zu Adolf Hitler benötigt.
(S -> W -> X -> Y -> Z)


Ausblick:
Um die Behauptung zu beweisen oder zu widerlegen, müsste jeder Artikel untersucht werden, wenigstens solange, bis ein Artikel gefunden wurde, der weiter als 4 Klicks eintfernt ist.

# Benutzung
Außer einer halbwegs aktuellen php Version sind keine weiteren Abhängigkeiten vorhanden.

Getestet wurde der Code in einer Instanz der Cloud9 IDE ( http://c9.io ).
(Eröffnen eines neuen Workspace mit dem "php, Apache & MySql" Templte, Import von Github, Starten des Servers aus der IDE, los gehts!)

Da die Wikipedia-API Calls pro Aufruf seitens der Wikipedia künstlich auf eine (1) Sekunde pro Aufruf gedrosselt werden und da für längere Artikel auch mal mehrere Aufrufe pro Artikel notwendig werden, ist es notwendig, die Zeit für einen Timeout deutlich zu erhöhen.

Aus dem gleichen Grund wird ein Cache benutzt, hierfür muss php im Dateisystem schreiben dürfen.
