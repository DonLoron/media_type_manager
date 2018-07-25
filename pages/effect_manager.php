<?php
/**
 * User: Laurin Waller
 * Date: 13.12.17
 * Time: 16:05
 */

$func = rex_request('func', 'string');

$fragment = new rex_fragment();

if ($func == '' || $func == "delete") {

  if($func == "delete") {
    rex_media_type_set::delete(rex_request("mediatypeSetName"));
  }

  //save media type set
  if(rex_request::post("sendit") == 1) {
    $mediaSet = new rex_media_type_set(rex_request::post("mediatypeSet"));
    $mediaSet->save((rex_request("update") != ""));
  }

  $sets = rex_media_type_set::getAllSets();

  //make the list!
  $fragment->setVar("listElements", $sets);
  echo $fragment->parse("list.php");

} else if($func == "add" || $func == "edit") {

  if($func == "edit") {
    $formData = rex_media_type_set::getSetByName(rex_request("mediatypeSetName"));
  } else {
    $formData = $this->getConfig('defaultConfig');
  }

  $frag = new rex_fragment();
  $frag->setVar("formData", $formData);
  $frag->setVar("mediaQueryActive", false);
  $body = $frag->parse("form.php");

  $title = "Standardkonfiguration (Neue Sets werden immer mit diesen Werten angelegt)";

  $fragment->setVar('body', $body, false);
  $fragment->setVar('title', $title, false);
  $content = $fragment->parse('core/page/section.php');

  echo $content;

}

?>