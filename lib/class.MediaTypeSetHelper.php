<?php


class MediaTypeSetHelper
{

  /**
   * small helper method which returns available effects
   * @return array
   */
  public static function getMediaManagerEffectArray() {
    $effects = [];
    foreach (rex_media_manager::getSupportedEffects() as $class => $shortName) {
      $effects[$shortName] = new $class();
    }
    return $effects;
  }

  public static function getPictureTag ($mediaTypeSetName, $file) {

    $mediaTypeSet = MediaTypeSet::getSetByName($mediaTypeSetName);

    if(!is_array($mediaTypeSet)) return false;

    $media = rex_media::get($file);
    if($media instanceof rex_media) $title = addslashes($media->getTitle());
    else $title = " ";

    $tag = '';
    $tag .= '<picture>'.PHP_EOL;

    foreach($mediaTypeSet['breakpoints'] as $breakpoint) {

      //TODO: Add values in media manager form per breakpoint
      switch($breakpoint['breakpointName']) {
        case 'L':
          $mediaQuery = "(min-width: 1025px)";
          break;
        case 'M':
          $mediaQuery = "(min-width: 751px) and (max-width: 1024px)";
          break;
        case 'S':
          $mediaQuery = "(min-width: 376px) and (max-width: 750px)";
          break;
        case 'XS':
          $mediaQuery = "(max-width: 375px)";
          break;
      }

      $tag .= '	<source media="' . $mediaQuery . '" srcset="index.php?rex_media_type='.$mediaTypeSetName.'-' . $breakpoint['breakpointName'] . '@1x&rex_media_file='.$file.' 1x, index.php?rex_media_type='.$mediaTypeSetName.'-' . $breakpoint['breakpointName'] . '@2x&rex_media_file='.$file.' 2x">'.PHP_EOL;
    }

    $tag .= '	<img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" alt="' . $title .'">'.PHP_EOL;
    $tag .= '</picture>'.PHP_EOL;

    return $tag;
  }

  public static function handleAJAX($action) {

    $response = [];

    switch($action) {
      case 'getEffectVars':

        $fragment = new rex_fragment();
        $fragment->setVar("effectShortName", rex_request::get("effectShortName"));

        try {

          $response['content'] = $fragment->parse("effectVarsFieldset.php");
          $response['status'] = "success";

        } catch (Exception $e) {
          $response['debug'] = [
            "effectShortName" => rex_request::get("effectShortName")
          ];
          $response['status'] = "error";
        }

        break;
      default:
        $response['status'] = "error";
        break;
    }

    echo json_encode($response);
    die();
  }
}