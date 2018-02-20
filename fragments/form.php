<?php
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
      $n['label'] = "<label for=\"media_set_title\">Mediatype-Set Beschreibung</label>";
      $n['field'] = "<textarea class='form-control' type=\"text\" id=\"media_set_description\" name=\"mediatypeSet[mediatypeSetDescription]\" rows=\"4\">{$this->formData['mediatypeSetDescription']}</textarea>";
      $formElements[] = $n;

      $n = [];
      $n['label'] = "<label for=\"media_set_title\">Lazyload Aktiv?</label>";
      $n['field'] = "<select class='form-control' type=\"text\" id=\"media_set_title\" name=\"mediatypeSet[lazyloadActive]\"><option value=\"0\" " . ($this->formData['lazyloadActive'] == 0 ? 'selected' : '') . ">Nein</option><option value=\"1\" " . ($this->formData['lazyloadActive'] == 1 ? 'selected' : '') . ">Ja</option></select>";
      $formElements[] = $n;

      $fragment->setVar("elements", $formElements, false);
      echo $fragment->parse("core/form/form.php");
      unset($formElements);

      ?>
    </fieldset>
    <fieldset>
      <legend>Breakpoints</legend>
      <div id="accordion">
      <? foreach($this->formData['breakpoints'] as $index => $breakpoint) { ?>
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
              $n['field'] = "<input class=\"form-control breakpointName\" type=\"text\" name=\"mediatypeSet[breakpoints][$index][breakpointName]\" value=\"{$breakpoint['breakpointName']}\">";
              $formElements[] = $n;

              $fragment->setVar("elements", $formElements, false);
              echo $fragment->parse("core/form/form.php");
              unset($formElements);
              ?>
              <fieldset>
                <legend>Breakpoint spezifische Variablen</legend>
                <?
                $innerFormElements = [];

                foreach($breakpoint['values'] as $k => $var) {

                  $n = [];
                  $n['label'] = "<label for=\"media_set_title\">Variable \"$k\"</label>";
                  $n['field'] = "<input class=\"form-control defaultVars\" type=\"text\" id=\"media_set_title\" name=\"mediatypeSet[breakpoints][$index][values][$k]\" value=\"{$var}\">";
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

        $effects = MediaEffectManagerHelper::getMediaManagerEffectArray();

        foreach($this->formData['defaultEffects'] as $index => $savedEffectValues) { ?>
          <? $effectShortName = str_replace("rex_effect_", "", $savedEffectValues["effect"])?>
          <div class="card panel panel-info">
            <div class="card-header panel-heading" id="heffect<?= $index ?>" data-toggle="collapse" data-target="#effect<?= $index ?>">
              <h5 class="mb-effect<?= $index ?> panel-title">
                Effekt "<?= $effectShortName ?>" <button type="button" class="removePanel btn btn-xs btn-danger pull-right"><span class="glyphicon glyphicon-trash"></span></button>
              </h5>
            </div>
            <div id="effect<?= $index ?>" class="collapse panel-body" data-parent="#accordionEffect">
              <div class="card-body">
                <?

                $options = "<option value=\"\" disabled " . ($savedEffectValues["effect"] == "" ? "selected" : "") . ">Effekt auswählen</option>";
                foreach($effects as $effectName => $effect) {
                  $options .= "<option value=" . get_class($effect) . " " . ($savedEffectValues["effect"] == get_class($effect) ? 'selected' : '') . ">" . $effectName . "</option>";
                }

                $n = [];
                $n['label'] = "<label for=\"media_set_title\">Effekt</label>";
                $n['field'] = "<select class=\"form-control effectSelect\" type=\"text\" name=\"mediatypeSet[defaultEffects][$index][effect]\">$options</select>";
                $formElements[] = $n;

                $fragment->setVar("elements", $formElements, false);
                echo $fragment->parse("core/form/form.php");
                unset($formElements);

                $fragment->setVar("formIndex", $index);
                $fragment->setVar("effectShortName", $effectShortName);
                $fragment->setVar("savedEffectValues", $savedEffectValues);
                echo $fragment->parse("effectVarsFieldset.php");
                ?>
              </div>
            </div>
          </div>
          <?php
        }
        ?>
      </div>
      <button type="button" class="addPanel btn btn-warning">Neuer Effekt</button><br>
    </fieldset>
    <br>
    <?php
    $formElements = [];
    $n = [];
    $n['field'] = '<a class="btn btn-abort" href="' . rex_url::currentBackendPage() . '">' . rex_i18n::msg('form_abort') . '</a>';
    $formElements[] = $n;

    $n = [];
    $n['field'] = '<button class="btn btn-apply" type="submit" name="sendit" value="1"' . rex::getAccesskey(rex_i18n::msg('update'), 'apply') . '>' . rex_i18n::msg('update') . '</button>';
    $formElements[] = $n;

    $fragment = new rex_fragment();
    $fragment->setVar('elements', $formElements, false);
    echo $fragment->parse('core/form/submit.php');
    ?>
  </form>
</div>
<script>
  $(document).ready(function(){

    //changes breakpoint name
    var oldTitle = $('#media_set_title').val();
    $('#media_set_title').on('keyup', function(){
      var newTitle = $(this).val();
      $('#accordion h5').each(function() {
        $(this).html($(this).html().replace(" \"" + oldTitle, " \"" + newTitle));
      });
      oldTitle = newTitle;
    });

    //renames breakpoint on change
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

    //adds a panel
    $(document).on('click', '.addPanel', function(e){
      e.stopPropagation();

      var $newPanel = $(this).prev().find('.panel:last-child').clone();
      var oldId = $newPanel.data('panelid');

      var search = new RegExp("break" + oldId, 'g');
      var replace = "break" + (oldId + 1);

      $newPanel = $($newPanel[0].outerHTML.replace(search, replace));
      $newPanel.attr('panelid', oldId + 1);

      $(this).prev().append($newPanel);
    });

    //removes a panel
    $(document).on('click', '.removePanel', function(e){
      e.stopPropagation();
      $(this).closest('.panel').remove();
    });

    $(document).on('change', '.effectSelect', function(){
      var $panel = $(this).closest('.panel');
      var $panelTitle = $panel.find('h5');
      var effectShortName = $(this).find('option:selected').html();

      //replace panel title
      $panelTitle.html($panelTitle.html().replace(/"(.*?)"/,'"' + effectShortName + '"'));

      //do ajax on current url and get new vars
      $.get('', {action: 'getEffectVars', effectShortName: effectShortName}, function(data){
        if(data !== "") {
          data = JSON.parse(data);
          if(data.status === "success") {
            $panel.find('.effectVariables').replaceWith($(data.content));
          } else {
            alert('there was an error with this request');
          }
        }
      });

    });
  });
</script>