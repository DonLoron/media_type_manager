<?php
/**
 * Created by PhpStorm.
 * User: walderwerber
 * Date: 16.02.18
 * Time: 17:03
 */

/* @var rex_addon $this */
if(!$this->hasConfig()) {
  $this->setConfig('defaultConfig', [
    'mediatypeSetName' => "fullscreen",
    'breakpoints' => [
      [
        "breakpointName" => "L",
        "values" => [
          "width" => 1920,
          "height" => 1080
        ]
      ],
      [
        "breakpointName" => "M",
        "values" => [
          "width" => 1024,
          "height" => 768
        ]
      ],
      [
        "breakpointName" => "S",
        "values" => [
          "width" => 750,
          "height" => 420
        ]
      ],
      [
        "breakpointName" => "XS",
        "values" => [
          "width" => 375,
          "height" => 210
        ]
      ]
    ],
    "defaultEffects" => [
      [
        "effect" => "rex_effect_crop"
      ],
      [
        "effect" => "rex_effect_resize"
      ]
    ],
  ]);
}

if(rex_request::post("sendit") == 1) {
  $this->setConfig("defaultConfig", rex_request::post("mediatypeSet"));
  echo rex_view::success("Konfiguration erfolgreich aktualisiert!");
}

$formData = $this->getConfig('defaultConfig');

$frag = new rex_fragment();
$frag->setVar("formData", $formData);
$body = $frag->parse("form.php");

$title = "Standardkonfiguration (Neue Sets werden immer mit diesen Werten angelegt)";

$fragment = new rex_fragment();
$fragment->setVar('body', $body, false);
$fragment->setVar('title', $title, false);
$content = $fragment->parse('core/page/section.php');

echo $content;