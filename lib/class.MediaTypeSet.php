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

    $sqlFactory = rex_sql::factory();

    //only try to safe data if translatedData is available
    if(isset($this->translatedData)) {

      if(!$update) {

        $newMediaTypeIds = [];

        //loop trough all breakpoints and add new data needed for effect generation
        foreach($this->translatedData['breakpoints'] as $breakpoint) {

          $breakpoint['types'] = [];

          //base name of type
          $typeBaseName = $this->translatedData["mediatypeSetName"] . $breakpoint['breakpointName'] . self::MEDIA_TYPE_IDENTIFIER;

          //amounts of types retina
          $amount = 2; //TODO maybe make it selectable 1x/2x/3x ? now default non- and retina
          for($i = 1; $i <= $amount; $i++) {
            $breakpoint['types'][] = [
              'name' => $typeBaseName . $i . "x",
              'optionType' => $i
            ];
          }

          //add lazyload type if active
          if($this->translatedData['lazyloadActive'] == 1) {
            $breakpoint['types'][] = [
              'name' => $typeBaseName . "lazy",
              'optionType' => "lazy"
            ];
          }

          //add these media types
          foreach($breakpoint['types'] as $newType) {

            try {
              $sqlFactory->reset();
              $result = $sqlFactory->setQuery("INSERT INTO " . rex::getTablePrefix() . "media_manager_type (status, name, description) VALUES (1, '$newType[name]', '" . $this->translatedData['mediatypeSetDescription'] . "')");
              $newType['id'] = $result->getLastId();

              //now add effects for these new types
              $effectInsert = "INSERT INTO " . rex::getTablePrefix() . "media_manager_type_effect (type_id, effect, parameters, priority) VALUES ";
              $effectValueArray = [];

              //loop trough default effects
              foreach($this->translatedData['defaultEffects'] as $i => $effect) {

                //convert to right option array
                $options = [];
                foreach($effect['options'] as $key => $optionValue) {

                  //if there is a var set in breakpoint, try to allocate it in option value
                  if(is_int($newType['optionType']) && isset($breakpoint['values'][$key])) {
                    $optionValue = $newType['optionType'] * $breakpoint['values'][$key];
                  }

                  $options[$effect["effect"] . '_' . $key] = $optionValue;
                }

                //and encode to json
                $jsonifiedEffect = json_encode([
                  $effect["effect"] => $options
                ]);

                $shortName = str_replace("rex_effect_", "", $effect['effect']);

                $prio = $i + 1;

                $effectValueArray[] = "($newType[id], '$shortName', '$jsonifiedEffect', $prio)";
              }

              $effectInsert .= implode(',', $effectValueArray) . ";";

              //try inserting, or catch error.
              try {
                $sqlFactory->reset();
                $sqlFactory->setQuery($effectInsert);
              } catch (rex_sql_exception $e) {
                echo "Konnte Effekte nicht hinzufügen.<br>";
                echo $e->getMessage();
              }

            } catch (rex_sql_exception $e) {
              echo "Konnte Eintrag $newType[name] nicht hinzufügen. Existiert bereits.<br>";
            }
          }
        }
        //all types successfully added
        return true;
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