<?php
/**
 * Created by PhpStorm.
 * User: walderwerber
 * Date: 16.02.18
 * Time: 17:03
 */

/* @var rex_addon $this */

$this->setConfig('defaultConfig', [
  'mediatypeSetName' => "fullscreen",
  'breakpoints' => [
    [
      "breakpointName" => "L",
      "variables" => [
        "width" => 1920,
        "height" => 1080
      ]
    ],
    [
      "breakpointName" => "M",
      "variables" => [
        "width" => 1024,
        "height" => 768
      ]
    ],
    [
      "breakpointName" => "S",
      "variables" => [
        "width" => 750,
        "height" => 420
      ]
    ],
    [
      "breakpointName" => "XS",
      "variables" => [
        "width" => 375,
        "height" => 210
      ]
    ]
  ],
  "defaultEffects" => [
    "rex_effect_resize" => [
      "width" => 1920,
      "height" => 1080
    ],
    "rex_effect_crop" => [
      "width" => 1920,
      "height" => 1080
    ]
  ],
  "retina" => true
]);

$title = "Standardkonfiguration (Neue Sets werden immer mit diesen Werten angelegt)";

$formData = $this->getConfig('defaultConfig');

$frag = new rex_fragment();
$frag->setVar("formData", $formData);
$body = $frag->parse("form.php");


$formElements = [];
$n = [];
$n['field'] = '<a class="btn btn-abort" href="' . rex_url::currentBackendPage() . '">' . rex_i18n::msg('form_abort') . '</a>';
$formElements[] = $n;

$n = [];
$n['field'] = '<button class="btn btn-apply" type="submit" name="sendit" value="1"' . rex::getAccesskey(rex_i18n::msg('update'), 'apply') . '>' . rex_i18n::msg('update') . '</button>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');

$fragment = new rex_fragment();
$fragment->setVar('body', $body, false);
$fragment->setVar('title', $title, false);
$fragment->setVar('buttons', $buttons, false);
$content = $fragment->parse('core/page/section.php');

echo $content;