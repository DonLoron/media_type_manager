<?php

/**
 * Created by PhpStorm.
 * User: walderwerber
 * Date: 13.12.17
 * Time: 16:41
 */
class MediaTypeSet
{

  const MEDIA_TYPE_IDENTIFIER = "@";

  public $translatedData;

  protected $mediaManagerData;

  /**
   * MediaEffectSet constructor.
   * @param $data
   */
  public function __construct($data)
  {

    dump($data);

    $this->translatedData = $data;
  }

  public function save($update = false) {

    //only try to safe data if translatedData is available
    if(isset($this->translatedData)) {

      if(!$update) {

        $types = [];

        //loop trough all breakpoints
        foreach($this->translatedData['breakpoints'] as $breakpoint) {
          //base name of type
          $typeBaseName = $this->translatedData["mediatypeSetName"] . $breakpoint['breakpointName'] . self::MEDIA_TYPE_IDENTIFIER;

          //amounts of types retina
          $amount = 2; //TODO maybe make it selectable 1x/2x/3x ?
          for($i = 1; $i <= $amount; $i++) {
            $types[] = $typeBaseName . $i . "x";
          }
          //add lazyload type if active
          if($this->translatedData['lazyloadActive'] == 1) $types[] = $typeBaseName . "lazy";

          //add these
        }
      }
    }

  }

  public static function getSetByName() {

  }

  public static function saveMediaTypeSet() {

  }

  protected static function translateSql($result) {

    $sets = [];

    if(count($result) > 0) {
      foreach($result as $row) {
        if(strpos($row['name'], self::MEDIA_TYPE_IDENTIFIER) !== false) {
          $setName = explode(self::MEDIA_TYPE_IDENTIFIER, $row['name'])[0];
        }
      }
    }

    return $sets;
  }

  /**
   *
   */
  public static function getAllSets() {
    $sql = rex_sql::factory();

    $allMediaManagerTypes = $sql->getArray("SELECT * FROM " . rex::getTablePrefix() . "media_manager_type WHERE name LIKE '%" . self::MEDIA_TYPE_IDENTIFIER . "%'");

  }
}