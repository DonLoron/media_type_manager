<?php

/* @var rex_addon $this */

/* @var rex_addon $this */
if(!$this->hasConfig()) {
  $this->setConfig('defaultConfig', [
    'mediatypeSetName' => "fullscreen",
    'lazyloadActive' => 1,
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
          "height" => 1170
        ]
      ],
      [
        "breakpointName" => "XS",
        "values" => [
          "width" => 375,
          "height" => 585
        ]
      ]
    ],
    "defaultEffects" => [
      [
        "effect" => "rex_effect_resize"
      ],
      [
        "effect" => "rex_effect_crop"
      ]
    ],
  ]);
}