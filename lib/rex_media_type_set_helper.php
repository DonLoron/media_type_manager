<?php


class rex_media_type_set_helper
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

  /**
   * returns picture tag by given type name and file
   * @param $mediaTypeSetName
   * @param $file
   * @param array $attributes key == html attr, value == html attr value
   * @return bool|string
   */
  public static function getPictureTag ($mediaTypeSetName, $file, $attributes = []) {

    $mediaTypeSet = rex_media_type_set::getSetByName($mediaTypeSetName);

    $defaultConfigBreakpoints = rex_addon::get('media_type_manager')->getConfig('defaultConfig')['breakpoints'];

    if(!is_array($mediaTypeSet)) {
      rex_logger::logException(new rex_exception("Tried to get picture tag $mediaTypeSetName, but it doesnt exist."));
      return false;
    }

    //try to get a title, but if file not exists, or is not synchronised, alt will be empty
    $media = rex_media::get($file);
    if($media instanceof rex_media) $alt = addslashes($media->getTitle());
    else $alt = " ";

    if(count($attributes) > 0) {

       $attrString = "";

       foreach($attributes as $attrKey => $attrVal) {
         $attrString .= " $attrKey=\"$attrVal\"";
       }

    }

    $tag = '';
    $tag .= '<picture' . $attrString . '>'.PHP_EOL;

    foreach(array_reverse($mediaTypeSet['breakpoints'], true) as $key => $breakpoint) {

      if($mediaTypeSet['lazyloadActive']) {
        $datasrcsetAttr = 'srcset="index.php?rex_media_type='.$mediaTypeSetName.'-' . $breakpoint['breakpointName'] . '@lazy&rex_media_file='.$file.'" ';
        $srcsetAttr = 'data-srcset';
      } else {
        $datasrcsetAttr = '';
        $srcsetAttr = 'srcset';
      }

      $tag .= '	<source media="' . $defaultConfigBreakpoints[$key]['mediaQuery'] . '" ' . $datasrcsetAttr . $srcsetAttr . '="index.php?rex_media_type='.$mediaTypeSetName.'-' . $breakpoint['breakpointName'] . '@1x&rex_media_file='.$file.' 1x, index.php?rex_media_type='.$mediaTypeSetName.'-' . $breakpoint['breakpointName'] . '@2x&rex_media_file='.$file.' 2x">'.PHP_EOL;
    }

    $tag .= '	<img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazyload" data-src alt="' . $alt .'">'.PHP_EOL;

    $tag .= '</picture>'.PHP_EOL;

    return $tag;
  }

}