<?php

//ajax request must be done late
rex_extension::register("PACKAGES_INCLUDED", function(){
  if(rex::isBackend() && rex_request::isXmlHttpRequest() && rex_request::get("action") != "") MediaTypeSetHelper::handleAJAX(rex_request::get("action"));
});
