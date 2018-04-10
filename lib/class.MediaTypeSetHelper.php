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