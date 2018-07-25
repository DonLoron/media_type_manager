<?php

/**
 * Created by PhpStorm.
 * User: walderwerber
 * Date: 13.12.17
 * Time: 16:41
 */
class rex_media_type_set
{

  const MEDIA_SET_BREAKPOINT_DELIMETER = "-";
  const MEDIA_TYPE_IDENTIFIER = "@";

  public $translatedData;

  /**
   * MediaEffectSet constructor.
   * @param $data
   */
  public function __construct($data)
  {
    $this->translatedData = $data;
  }

  /**
   * deletes a type by name
   * @param $mediaSetName
   * @return bool
   */
  public static function delete($mediaSetName) {
    $sqlFactory = rex_sql::factory();
    try {
      $name = $mediaSetName . self::MEDIA_SET_BREAKPOINT_DELIMETER;
      $sqlFactory->setQuery("DELETE t,e FROM rex_media_manager_type t LEFT JOIN rex_media_manager_type_effect e  ON t.id = e.type_id WHERE t.name LIKE '$name%'");
    } catch (rex_sql_exception $e) {
      echo "Konnte set nicht aktualisieren: " . $e->getMessage();
      return false;
    }
    return true;
  }

  /**
   * saves the type
   * @param bool $update update type?
   * @return bool
   */
  public function save($update = false) {

    $sqlFactory = rex_sql::factory();
    //only try to safe data if translatedData is available
    if(isset($this->translatedData)) {

      //when updating, delete old entries ):
      if($update) {
        try {
          $sqlFactory->setQuery("DELETE e,t FROM rex_media_manager_type_effect e LEFT JOIN rex_media_manager_type t ON e.type_id = t.id WHERE t.name LIKE '{$this->translatedData['mediatypeSetOldName']}%'");
        } catch (rex_sql_exception $e) {
          echo "Konnte set nicht aktualisieren: " . $e->getMessage();
          return false;
        }
      }

      //loop trough all breakpoints and add new data needed for effect generation
      foreach($this->translatedData['breakpoints'] as $breakpoint) {

        $breakpoint['types'] = [];

        //base name of type
        $typeBaseName = $this->translatedData["mediatypeSetName"] . self::MEDIA_SET_BREAKPOINT_DELIMETER . $breakpoint['breakpointName'] . self::MEDIA_TYPE_IDENTIFIER;

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
            'optionType' => 0.5
          ];
        }

        //add these media types
        foreach($breakpoint['types'] as $newType) {

          try {
            $sqlFactory->reset();
            $result = $sqlFactory->setQuery("INSERT INTO " . rex::getTablePrefix() . "media_manager_type (status, name, description) VALUES (0, '$newType[name]', '" . $this->translatedData['mediatypeSetDescription'] . "')");
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
                if(is_numeric($newType['optionType']) && isset($breakpoint['values'][$key])) {
                  $optionValue = $newType['optionType'] * $breakpoint['values'][$key];
                }

                $options[$effect["effect"] . '_' . $key] = $optionValue;
              }

              //and encode to json
              $jsonifiedEffect = json_encode([
                $effect["effect"] => $options
              ]);

              $shortName = str_replace("rex_effect_", "", $effect['effect']);

              $effectValueArray[] = "($newType[id], '$shortName', '$jsonifiedEffect', " . ($i + 1) . ")";
            }

            $effectInsert .= implode(',', $effectValueArray) . ";";

            //try inserting, or catch error.
            try {
              $sqlFactory->reset();
              $sqlFactory->setQuery($effectInsert);
            } catch (rex_sql_exception $e) {
              echo "Konnte Effekte nicht hinzufügen.<br>";
              echo "<br>";
              echo $effectInsert."<br>";
              echo "<br>";
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

    return false;
  }

  /**
   * translates sql to a useful data form
   * @param $result
   * @param bool $short
   * @return array
   */
  public static function translateSql($result, $short = true) {

    $sql = rex_sql::factory();
    $sets = [];

    if(count($result) > 0) {
      foreach($result as $row) {
        if(strpos($row['name'], self::MEDIA_TYPE_IDENTIFIER) !== false) {

          $mediaTypeName = self::getMediaTypeSetName($row['name']);

          $sets[$mediaTypeName]["mediatypeSetName"] = $mediaTypeName;
          $sets[$mediaTypeName]["mediatypeSetDescription"] = $row['description'];
          $sets[$mediaTypeName]["lazyloadActive"] = ((!isset($sets[$mediaTypeName]['lazyloadActive']) || $sets[$mediaTypeName]['lazyloadActive'] == '0') && strpos($row['name'], 'lazy') !== false ? '1' : '0');

          $breakPointName = self::getMediaTypeBreakpointName($row['name']);
          //only if this breakpoint is not set
          $sets[$mediaTypeName]["breakpoints"][$breakPointName]['breakpointName'] = $breakPointName;

          //now we get the effects, only if 1x, but only do this if all data is needed
          if(strpos($row['name'], self::MEDIA_TYPE_IDENTIFIER . "1x") !== false && !$short) {

            $effectsForType = $sql->getArray("SELECT * FROM " . rex::getTablePrefix() . "media_manager_type_effect WHERE type_id = $row[id]");

            $defaultEffects = [];
            $breakPointValues = [];

            foreach($effectsForType as $effect) {

              $options = [];

              foreach(end(json_decode($effect['parameters'])) as $key => $param) {

                $key = str_replace("rex_effect_" . $effect["effect"] . "_", "", $key);

                if($key != "width" && $key != "height") {
                  $options[$key] = $param;
                } else {
                  $options[$key] = "";
                  $breakPointValues[$key] = $param;
                }
              }

              $defaultEffects[] = [
                "effect" => "rex_effect_" . $effect["effect"],
                "options" => $options
              ];
            }

            $sets[$mediaTypeName]["defaultEffects"] = $defaultEffects;
            $sets[$mediaTypeName]["breakpoints"][$breakPointName]['values'] = $breakPointValues;
          }
        }
      }

      //this is hacky
      foreach($sets as &$set) {
        $set["breakpoints"] = array_values($set["breakpoints"]);
      }
      unset($set);
    }

    return $sets;
  }

  /**
   * helper function for getting effects on change
   * @param $action
   */
  public static function handleAJAX($action)
  {

    $response = [];

    switch ($action) {
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

  /**
   * tries to allocate name of media set
   * example: headerImage-X@[suffix]
   * @param $mediaManagerTypeName
   * @return string
   */
  public static function getMediaTypeSetName($mediaManagerTypeName) {

    $matched = preg_match("/(.*)-/Ui", $mediaManagerTypeName, $matches);

    if($matched === 1) {
      return $matches[1];
    } else {
      return "";
    }
  }

  /**
   * tries to allocate name of media set breakpoint
   * example: headerImage-X@[suffix]
   * @param $mediaManagerTypeName
   * @return string
   */
  public static function getMediaTypeBreakpointName($mediaManagerTypeName) {

    //first split by MEDIA_TYPE_IDENTIFIER
    $matched = preg_match("/-(.*)@/Ui", $mediaManagerTypeName, $matches);

    if($matched === 1) {
      return $matches[1];
    } else {
      return "";
    }
  }

  /**
   * gets all sets, ungrouped
   * @return array
   */
  public static function getAllSets() {
    $sql = rex_sql::factory();

    $allMediaManagerTypes = $sql->getArray("SELECT * FROM " . rex::getTablePrefix() . "media_manager_type WHERE name LIKE '%" . self::MEDIA_TYPE_IDENTIFIER . "%'");

    return self::translateSql($allMediaManagerTypes);
  }

  /**
   * gets a set by name and translates to usable data
   * @param $setName
   * @return bool|mixed
   */
  public static function getSetByName($setName) {
    $sql = rex_sql::factory();

    $allMediaManagerTypes = $sql->getArray("SELECT * FROM " . rex::getTablePrefix() . "media_manager_type WHERE name LIKE '" . $setName . self::MEDIA_SET_BREAKPOINT_DELIMETER . "%'");

    $translatedData = self::translateSql($allMediaManagerTypes, false);

    if(count($translatedData) == 1) {
      return end($translatedData);
    } else {
      return false;
    }
  }
}