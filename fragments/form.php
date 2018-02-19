<?php
dump($this->formData);

$fragment = new rex_fragment();

?>
<div class="rex-form">
  <form action="<?= rex_url::currentBackendPage() ?>" method="post">
    <fieldset>
      <legend>Allgemeine Settings</legend>
      <?
      $n = [];
      $n['label'] = "<label for=\"media_set_title\">Mediatype-Set Name</label>";
      $n['field'] = "<input class='form-control' type=\"text\" id=\"media_set_title\" name=\"mediatypeSet[mediatypeSetName]\" value=\"{$this->formData['mediatypeSetName']}\">";
      $formElements[] = $n;

      $n = [];
      $n['label'] = "<label for=\"media_set_title\">Lazyload Aktiv?</label>";
      $n['field'] = "<select class='form-control' type=\"text\" id=\"media_set_title\" name=\"mediatypeSet[lazyloadActive]\"><option value=\"0\" " . ($this->formData['lazyloadActive'] == 0 ? 'selected' : '') . ">Nein</option><option value=\"1\" " . ($this->formData['lazyloadActive'] == 1 ? 'selected' : '') . ">Ja</option></select>";
      $formElements[] = $n;

      $fragment->setVar("elements", $formElements, false);
      echo $fragment->parse("core/form/form.php");
      ?>
    </fieldset>
    <fieldset>
      <legend>Breakpoints</legend>
      <div id="accordion">
      <? foreach($this->formData['breakpoints'] as $index => $breakpoint) { ?>
        <? unset($formElements) ?>
        <div class="card panel panel-info" data-panelid="<?= $index ?>">
          <div class="card-header panel-heading" id="hbreak<?= $index ?>" data-toggle="collapse" data-target="#break<?= $index ?>">
            <h5 class="mb-break<?= $index ?> panel-title">
                Breakpoint "<?= $this->formData['mediatypeSetName'] . $breakpoint['breakpointName'] ?>" <button type="button" class="removePanel btn btn-xs btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span></button>
            </h5>
          </div>
          <div id="break<?= $index ?>" class="collapse panel-body" data-parent="#accordion">
            <div class="card-body">
              <?
              $n = [];
              $n['label'] = "<label>Breakpoint Name</label>";
              $n['field'] = "<input class=\"form-control breakpointName\" type=\"text\" name=\"mediatypeSet[breakpoints][breakpointName]\" value=\"{$breakpoint['breakpointName']}\">";
              $formElements[] = $n;

              $fragment->setVar("elements", $formElements, false);
              echo $fragment->parse("core/form/form.php");
              ?>
              <fieldset>
                <legend>Standard Variablen</legend>
                <?
                $innerFormElements = [];

                foreach($breakpoint['variables'] as $k => $var) {

                  $n = [];
                  $n['label'] = "<label for=\"media_set_title\">Variable \"$k\"</label>";
                  $n['field'] = "<input class=\"form-control defaultVars\" type=\"text\" id=\"media_set_title\" name=\"mediatypeSet[breakpoints][$breakpoint[breakpointName]][values][$k]\" value=\"{$var}\">";
                  $innerFormElements[] = $n;

                }

                $fragment->setVar("elements", $innerFormElements, false);
                echo $fragment->parse("core/form/form.php");
                ?>
              </fieldset>
            </div>
          </div>
        </div>
      <? } ?>
      </div>
      <button type="button" class="addPanel btn btn-warning">Neuer Breakpoint</button>
      <br>
      <br>
    </fieldset>
    <fieldset>
      <legend>Effekte</legend>
      <div id="accordionEffect">
        <?
        $effects = [];
        foreach (rex_media_manager::getSupportedEffects() as $class => $shortName) {
          $effects[$shortName] = new $class();
        }
        unset($formElements);
        foreach($this->formData['defaultEffects'] as $index => $mediaTypeEffectValues) {?>
          <? $shortName = str_replace("rex_effect_", "", $index)?>
          <div class="card panel panel-info">
            <div class="card-header panel-heading" id="heffect<?= $index ?>">
              <h5 class="mb-effect<?= $index ?> panel-title" data-toggle="collapse" data-target="#effect<?= $index ?>">
                Effekt "<?= $shortName ?>" <button type="button" class="removePanel btn btn-xs btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span></button>
              </h5>
            </div>
            <div id="effect<?= $index ?>" class="collapse panel-body" data-parent="#accordionEffect">
              <div class="card-body">
                <?

                $options = "<option value=\"\" disabled " . ($index == "" ? "selected" : "") . ">Effekt auswählen</option>";
                foreach($effects as $effectName => $effect) {
                  $options .= "<option value=" . get_class($effect) . " " . ($index == get_class($effect) ? 'selected' : '') . ">" . $effectName . "</option>";
                }

                $n = [];
                $n['label'] = "<label for=\"media_set_title\">Effekt</label>";
                $n['field'] = "<select class=\"form-control\" type=\"text\" id=\"media_set_title\" name=\"mediatypeSet[defaultEffects]\">$options</select>";
                $formElements[] = $n;

                $fragment->setVar("elements", $formElements, false);
                echo $fragment->parse("core/form/form.php");
                ?>
                <fieldset>
                  <legend>Standard Vars</legend>
                  <?
                  $innerFormElements = [];
                  foreach($effects[$shortName]->getParams() as $k => $param) {

                    switch($param["type"]) {
                      case 'select':

                        $options = "";
                        foreach($param['options'] as $optionsValue => $optionsText) {
                          $options .= "<option value=\"$optionsValue\"" . ($mediaTypeEffectValues[$param['name']] == $optionsValue || $param['default'] == $optionsText ? 'selected' : '') . ">" . $optionsText . "</option>";
                        }

                        $n = [];
                        $n['label'] = "<label for=\"media_set_title\">$param[label]</label>";
                        $n['field'] = "<select class='form-control' type=\"text\" id=\"media_set_title\" name=\"mediatypeSet[defaultEffects][$shortName][$param[name]]\">$options</select>";
                        $innerFormElements[] = $n;

                        break;
                      default:

                        $n = [];
                        $n['label'] = "<label for=\"media_set_title\">$param[label]</label>";
                        $n['field'] = "<input class='form-control' type=\"text\" id=\"media_set_title\" name=\"mediatypeSet[defaultEffects][$shortName][$param[name]]\" value=\"{$mediaTypeEffectValues[$param['name']]}\">";
                        $innerFormElements[] = $n;

                        break;
                    }
                  }

                  $fragment->setVar("elements", $innerFormElements, false);
                  echo $fragment->parse("core/form/form.php");
                  ?>
                </fieldset>
              </div>
            </div>
          </div>
          <?php
        }
        ?>
      </div>
      <button type="button" class="addPanel btn btn-warning">Neuer Effekt</button><br>
    </fieldset>
  </form>
</div>
<script>
  $(document).ready(function(){

    var oldTitle = $('#media_set_title').val();

    $('#media_set_title').on('keyup', function(){
      var newTitle = $(this).val();
      $('#accordion h5').each(function() {
        $(this).html($(this).html().replace(" \"" + oldTitle, " \"" + newTitle));
      });
      oldTitle = newTitle;
    });

    $(document).on('keyup','.breakpointName', function(){

      var newBrk = $(this).val();
      var $panelVariables = $(this).closest('.panel').find('.defaultVars');
      var $panelTitle = $(this).closest('.panel').find('h5');

      $panelTitle.html($panelTitle.html().replace(/"(.*?)"/, '"' + oldTitle + newBrk + '"'));
      $panelVariables.each(function(){
        var attrName = $(this).attr('name');

        $(this).attr('name', attrName.toString().replace(/\[breakpoints]\[.*?]/, "[breakpoints][" + newBrk + "]"));
      });

    });

    $('.addPanel').on('click', function(e){
      e.stopPropagation();

      var $newPanel = $(this).prev().find('.panel:last-child').clone();
      var oldId = $newPanel.data('panelid');

      var search = new RegExp("break" + oldId, 'g');
      var replace = "break" + (oldId + 1);

      $newPanel = $($newPanel[0].outerHTML.replace(search, replace));
      $newPanel.attr('panelid', oldId + 1);

      $(this).prev().append($newPanel);
    });

    $('.removePanel').on('click', function(e){
      e.stopPropagation();
      $(this).closest('.panel').remove();
    });
  });
</script>