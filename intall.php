<?php

/* @var rex_addon $this */

if (!$this->hasConfig()) {
  $this->setConfig('defaultConfig', [
    'effectName' => "fullscreen",
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
    "defaultActions" => [
      "rex_effect_resize",
      "rex_effect_crop"
    ],
    "retina" => true
  ]);
}