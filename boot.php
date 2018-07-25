<?php

//ajax request must be done late
rex_extension::register("PACKAGES_INCLUDED", function(){
  if(rex::isBackend() && rex_request::isXmlHttpRequest() && rex_request::get("action") != "") rex_media_type_set::handleAJAX(rex_request::get("action"));
});

if(!rex::isBackend()) {

  rex_extension::register("OUTPUT_FILTER", function($p){

    $page = $p->getSubject();

    $addonConfig = $this->getConfig('addonConfiguration');

    //if auto lazy load active, add js to head end
    if($addonConfig['autoloadLazyload'] == 1) {
      $js = '<script type="text/javascript" src="' . rex_url::assets('addons/media_type_manager/vendor/js/lazysizes.min.js') . '"></script>';
      $page = str_replace('</body>', $js . '</body>', $page);
    }

    if($addonConfig['autoloadPicturefill'] == 1) {
      if(rex_addon::exists('useragent')) {
        if (useragent::isBrowserInternetExplorer()) {
           $js = '<script src="' . rex_url::assets('addons/media_type_manager/vendor/js/picturefill.min.js') . '"></script>';
        }
      } else {
        $js = '<script src="' . rex_url::assets('addons/media_type_manager/vendor/js/picturefill.min.js') . '"></script>';
      }
      $page = str_replace('</body>', $js . '</body>', $page);
    }

    return $page;

  });
}