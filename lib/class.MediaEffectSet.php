<?php

/**
 * Created by PhpStorm.
 * User: walderwerber
 * Date: 13.12.17
 * Time: 16:41
 */
class MediaEffectSet
{

  public static $sqlFactory;

  public function __construct()
  {
    if(!isset(self::$sqlFactory)) {
      self::$sqlFactory = rex_sql::factory();
    }
  }

  public function getMediaEffectSets() {
    return self::$sqlFactory->getArray("SELECT * FROM rex_media_effect_set");
  }
}