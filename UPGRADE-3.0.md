# Upgrade auf 3.0: Laravel 13 und PHP 8.4

## Ziel und Umfang

Version 3.0 ist der stabile Zielstand für den Einsatz im Materialpool mit Laravel 13 und PHP 8.4. Das Bundle erhält absichtlich keine Abhängigkeit auf `laravel/framework` oder `illuminate/*`: Parser, Value Object, Interface und JavaScript-Exports sind frameworkunabhängig. Laravel registriert bei Bedarf `BibleVerseService` im eigenen Container.

Die PHP-Mindestversion steigt auf 8.3, weil Laravel 13 selbst mindestens PHP 8.3 verlangt. Dieser Plattformwechsel erfordert trotz unveränderter Fach-API eine neue Major-Version.

## Kompatibilitätsvertrag

- PHP 8.3 und 8.4 werden in CI getestet.
- PHPUnit 12.5 ist die Testbasis.
- Die optionale Symfony-Bundle-Hülle wird mit Symfony 7.4 unter PHP 8.3 und Symfony 8.x unter PHP 8.4 geprüft.
- Das eingecheckte Entwicklungs-Lockfile wird auf PHP 8.3 mit Symfony 7.4 erzeugt und ist dadurch auch unter PHP 8.4 installierbar. Die PHP-8.4-CI führt `composer update` aus und prüft so zusätzlich die neueste Symfony-8-Auflösung.
- Der PHP-Service `StevenBuehner\BibleVerseBundle\Service\BibleVerseService`, `BibleVerseInterface`, die Entity-Methoden sowie Exception-Klassen behalten ihre Namen.
- `js/out/BibleVerseService_de.js`, `js/out/BibleVerseService_en.js` und die Dateien unter `js/in/` bleiben veröffentlichte Verbraucher-Verträge.
- Der Twig-3-Generator muss die beiden versionierten JavaScript-Ausgaben bytegleich reproduzieren.
- Das alte Doctrine-Annotation-Mapping und `BibleVerseRepository` sind keine Voraussetzung für Laravel. Wer diese optionale Legacy-Integration verwendet, muss Doctrine ORM separat installieren. Eine Umstellung auf aktuelle Doctrine-Attribute ist ein eigenes, potenziell brechendes Teilprojekt.

## Änderungen für PHP 8.4 und aktuelle Werkzeuge

- Implizite Nullable-Parameter werden durch explizite Nullable-Typen ersetzt.
- PHPUnit-Lifecycle-Methoden verwenden `protected function setUp(): void` und rufen den Parent-Hook auf.
- PHPUnit lädt direkt `vendor/autoload.php`; der historische eigene Bootstrap entfällt.
- Der JavaScript-Generator verwendet die namespaced Twig-3-API und `Environment::load()` statt entfernter Twig-1-Klassen und `loadTemplate()`.
- Symfony-Extension-Methoden führen die von aktuellen Symfony-Versionen verlangten Rückgabetypen.
- Nicht verwendete Entwicklungsabhängigkeiten werden entfernt; die tatsächlich getesteten optionalen Symfony-Komponenten werden als Entwicklungsabhängigkeiten geführt.

## Release- und Verbraucherschritte

1. CI für PHP 8.3/Symfony 7.4 und PHP 8.4/Symfony 8 vollständig grün ausführen.
2. `composer validate --strict`, `composer audit --locked` und die vollständige PHPUnit-Suite ausführen.
3. `composer generate-js` ausführen und sicherstellen, dass dadurch kein unerklärter Diff unter `js/out/` entsteht.
4. Den lokalen Paketstand im Laravel-13-Materialpool einbinden und dessen vollständige Suite ausführen.
5. Den geprüften Commit als stabilen Tag `3.0.0` veröffentlichen.
6. Im Materialpool den temporären Commit-Pin durch `^3.0` ersetzen und ausschließlich `stevenbuehner/bible-verse-bundle` samt erforderlicher Lockfile-Auflösung aktualisieren.
7. Composer-Vertrag, Bundle-Vertragstest und vollständige Materialpool-Suite erneut ausführen.

## Rückbau

Das Bundle besitzt keine Migrationen und verändert keine Daten. Ein Rückbau erfolgt durch Wiederherstellung des vorherigen Composer-Lockfiles im Verbraucher. Wegen der höheren PHP-Mindestversion darf Version 3.0 nicht in Anwendungen mit PHP 8.2 oder älter installiert werden.

## Verifizierter Stand vom 9. September 2026

- PHP 8.3.15, Symfony 7.4.18, PHPUnit 12.5.35: 40 Tests und 89.352 Assertions bestanden.
- PHP 8.4.25, Symfony 8.1.6, PHPUnit 12.5.35: 40 Tests und 89.352 Assertions bestanden.
- Der auf PHP 8.3 erzeugte Lockstand wurde unter PHP 8.4 unverändert installiert und erneut mit 40 Tests und 89.352 Assertions geprüft.
- `composer validate --strict` ist fehlerfrei; `composer audit --locked` meldet keine Security-Advisories.
- Die Generator-Ausgaben für Deutsch und Englisch sind bytegleich zu den versionierten JavaScript-Dateien.
- Ein isoliertes Composer-Projekt hat Laravel 13.31.0 und den lokalen Bundle-Stand konfliktfrei gemeinsam installiert; der Parser-/Formatter-Smoke-Test ergab `1. Timotheus 3,16`.
- Die vollständige Laravel-13-Materialpool-Suite mit lokal überlagertem Bundle ist mit 156 Tests und 1.588 Assertions grün.

Technische Referenzen: [Laravel-13-Releasehinweise](https://laravel.com/framework/docs/releases) und [Symfony Best Practices for Reusable Bundles](https://symfony.com/doc/current/bundles/best_practices.html).
