<?php
/**
 * Created by PhpStorm.
 * User: walderwerber
 * Date: 16.02.18
 * Time: 17:03
 */

if(rex_request::post("sendit") == 1) {
  $this->setConfig("defaultConfig", rex_request::post("mediatypeSet"));
  echo rex_view::success("Konfiguration erfolgreich aktualisiert!");
}

$formData = $this->getConfig('defaultConfig');

$frag = new rex_fragment();
$frag->setVar("formData", $formData);
$frag->setVar("mediaQueryActive", true);
$body = $frag->parse("form.php");

$title = "Standardkonfiguration (Neue Sets werden immer mit diesen Werten angelegt)";

$fragment = new rex_fragment();
$fragment->setVar('body', $body, false);
$fragment->setVar('title', $title, false);
$content = $fragment->parse('core/page/section.php');

echo $content;