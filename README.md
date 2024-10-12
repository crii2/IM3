schwierigkeiten weil dopplete daten auf datenbank. deshalb musste ich pollution date and time hinzufügen, damit man bestimmten datenbereich bestimmen kann.

Datenbankabfragen und Performance: Bei der Arbeit mit einer größeren Datenmenge mussten effiziente Abfragen formuliert werden, um die Performance beim Einfügen und Abrufen der Daten zu optimieren.

Benutzte Ressourcen
AirVisual API: Die Hauptquelle der Daten für Luftqualität und Wetterinformationen. Link zur Dokumentation.
PHP PDO: Für sichere und effiziente Datenbankoperationen. PHP PDO-Dokumentation.
PHP DateTime: Für die Arbeit mit und Umwandlung von Datums- und Zeitwerten. PHP DateTime-Dokumentation.
MySQL-Dokumentation: Für SQL-Abfragen und die Arbeit mit Datums- und Zeitfeldern in der Datenbank. MySQL-Dokumentation.
w3schools für flexboxen

Benutzte Ressourcen

PHP Dokumentation (offizielle Dokumentation für PDO und cURL)
MySQL Dokumentation (für die Arbeit mit SQL-Abfragen, insbesondere GROUP BY, AVG(), und Datentypen)
StackOverflow (für spezifische Probleme mit der Datenbankabfrage und Chart.js-Integration)
Chart.js Dokumentation (für die Erstellung von dynamischen Diagrammen und das Anpassen der Diagrammoptionen)
GitHub (zur Versionskontrolle und für die Dokumentation im ReadMe)

Learnings
Während des Projekts habe ich gelernt, wie man effizient Daten von einer externen API abruft und diese in eine MySQL-Datenbank speichert. Ich konnte meine Fähigkeiten in der Verwendung von PDO für Datenbankabfragen und Transaktionen vertiefen. Außerdem habe ich durch die Arbeit mit Chart.js meine Kenntnisse in der Datenvisualisierung erweitert, insbesondere bei der dynamischen Darstellung von Daten aus einer Datenbank.

Learnings
Während der Arbeit an diesem Projekt habe ich einige wichtige technische und organisatorische Fähigkeiten verbessert:

API-Datenverarbeitung: Ich habe gelernt, wie man mit APIs effizient arbeitet, insbesondere wie man dynamische API-Aufrufe für mehrere Städte erstellt und die Ergebnisse verarbeitet.
Datenbankintegration mit PDO: Ich habe mein Wissen über die sichere und effiziente Nutzung von PDO für Datenbankabfragen vertieft.
Fehlerbehandlung und Debugging: Ich habe gelernt, wie man Fehler bei cURL-Anfragen und in SQL-Abfragen systematisch analysiert und löst.
Chart.js-Visualisierungen: Der Umgang mit Chart.js war eine neue Herausforderung, insbesondere das dynamische Erstellen und Anpassen von Diagrammen auf Basis von Echtzeitdaten.

Schwierigkeiten
Ein Problem, das auftrat, war der Umgang mit mehrfachen Datensätzen für einen Tag, die aufgrund stündlicher Datenpunkte in der Datenbank gespeichert wurden. Es war herausfordernd, eine Lösung zu finden, um nur einen Durchschnittswert pro Tag zu berechnen, aber mit SQL-Funktionen wie AVG() und GROUP BY konnte ich dies erfolgreich umsetzen. Ein weiteres Hindernis war die korrekte Handhabung von API-Fehlermeldungen und die Sicherstellung, dass das System robust auf Ausfälle der API reagiert.


design probleme im header, gelöst mit flexboxen. container von logo & aq switzerland in zwei flexboxen mit space inbetween


Schweiz Karte als SVG da alles andere (containter, flext etc) nicht funktioniert hat. Tipp von Lea bekommen.

