# Media Type Manager Addon

Das Media Type Manager Addon soll helfen, einfach schnell und unkompliziert neue Mediamanager Typen für den REDAXO 5 Mediamanager anzulegen. 

## Warum?
Du willst den `<picture>` tag nutzen, und perfekt zugeschnittene Bilder in der source liefern? Musst im Mediamanager aber gefühlte hundert clicks machen? Zudem passieren dir diverse Rechnungs- und Tippfehler?
 
Mit diesem Addon kannst du breakpoints definieren und für diese (bis jetzt) breite und höhe angeben, diese werden dann auf retina Screens (bis jetzt nur x2) hochgerechnet und die angegebenen Effekte mit den darin definierten Settings für alle Typen gespeichert.

Lazyload und Picturefill werden mitgeliefert. Wenn das useragent Addon installiert ist, wird der Picturefill nur bei IE eingebunden.

## Wie funktionierts?

Ganz einfach!

Typen im Modul anlegen, danach im HTML des Modul-Files folgende PHP-Methode aufrufen

```
$mediaManagerTypename = "fullscreen";
$file = "test_bild.jpg";
$additionalAttributes = ["class" => "a-super-cool-class", "id" => "also-a-pretty-cool-id"];
echo rex_media_type_set_helper::getPictureTag($mediaManagerTypename, $file, $additionalAttributes);
```

Daraus wird folgender output generiert.

```
<picture class="a-super-cool-class" id="also-a-pretty-cool-id">
	<source media="(max-width: 375px)" srcset="index.php?rex_media_type=fullscreen-XS@lazy&rex_media_file=test_bild.jpg" data-srcset="index.php?rex_media_type=fullscreen-XS@1x&rex_media_file=test_bild.jpg 1x">
	<source media="(min-width: 376px) and (max-width: 750px)" srcset="index.php?rex_media_type=fullscreen-S@lazy&rex_media_file=test_bild.jpg" data-srcset="index.php?rex_media_type=fullscreen-S@1x&rex_media_file=test_bild.jpg 1x">
	<source media="(min-width: 751px) and (max-width: 1024px)" srcset="index.php?rex_media_type=fullscreen-M@lazy&rex_media_file=test_bild.jpg" data-srcset="index.php?rex_media_type=fullscreen-M@1x&rex_media_file=test_bild.jpg 1x">
	<source media="(min-width: 1025px)" srcset="index.php?rex_media_type=fullscreen-L@lazy&rex_media_file=test_bild.jpg" data-srcset="index.php?rex_media_type=fullscreen-L@1x&rex_media_file=test_bild.jpg 1x">
	<img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload" data-src alt=" ">
</picture>
```

Wichtig: wenn lazyload aktiv ist, muss entweder:
 * die lazyload option in der addon konfiguration auf ja gesetzt sein
 * das lazyload js file manuell geladen werden
 
Ich höre es schon: "Der Picture Tag wird aber vom IE nicht unterstützt D: !". Keine sorge, der Picturefill wird auch automatisch geladen (solange aktiviert). Wenn das useragent Addon installiert ist, wird dieses auch nur im IE geladen.

## Entwicklungsstadium
 Es gibt noch diverse bugs und viele funktionen die noch nicht implentiert sind wie z.B.:
 * Benutzerdefinierte typen funktionieren nicht richtig
 * Es können nur Breite und Höhe durch Breakpoint überschrieben werden
 * media queries für alle typen, nicht pro typ
 
 Freue mich auf jedes Feedback
 
