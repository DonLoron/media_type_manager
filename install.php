<?php

/* @var rex_addon $this */

/* @var rex_addon $this */
if(!$this->hasConfig()) {
  $this->setConfig('defaultConfig', [
    'mediatypeSetName' => "fullscreen",
    'lazyloadActive' => 1,
    'breakpoints' => [
      [
        "mediaQuery" => "(min-width: 1025px)",
        "breakpointName" => "L",
        "values" => [
          "width" => 1920,
          "height" => 1080
        ]
      ],
      [
        "mediaQuery" => "(min-width: 751px) and (max-width: 1024px)",
        "breakpointName" => "M",
        "values" => [
          "width" => 1024,
          "height" => 768
        ]
      ],
      [
        "mediaQuery" => "(min-width: 376px) and (max-width: 750px)",
        "breakpointName" => "S",
        "values" => [
          "width" => 750,
          "height" => 1170
        ]
      ],
      [
        "mediaQuery" => "(max-width: 375px)",
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