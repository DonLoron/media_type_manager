<?php

if(rex_request::post("sendit") == 1) {
  $this->setConfig("addonConfiguration", rex_request::post("addonConfiguration"));
  echo rex_view::success("Konfiguration erfolgreich aktualisiert!");
}

$formData = $this->getConfig('addonConfiguration');

$frag = new rex_fragment();
$frag->setVar("formData", $formData);
$body = $frag->parse("configForm.php");

$title = "Addon Konfiguration";

$fragment = new rex_fragment();
$fragment->setVar('body', $body, false);
$fragment->setVar('title', $title, false);
$content = $fragment->parse('core/page/section.php');

echo $content;