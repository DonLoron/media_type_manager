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
      $n['field'] = "<input class='form-control mediatypeSetName' type=\"text\" id=\"media_set_title\" name=\"mediatypeSet[mediatypeSetName]\" value=\"{$this->formData['mediatypeSetName']}\">";
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
      <div class="accordion" id="breakpoints">
      <? foreach($this->formData['breakpoints'] as $index => $breakpoint) { ?>
        <div class="card panel panel-info">
          <div class="card-header panel-heading" data-toggle="collapse" data-target="#breakpoints<?= $index ?>">
            <h5 class="mb-break<?= $index ?> panel-title">
                Breakpoint "<?= $this->formData['mediatypeSetName'] . MediaTypeSet::MEDIA_SET_BREAKPOINT_DELIMETER . $breakpoint['breakpointName'] ?>"
              <div class="btn-group pull-right">
                <button type="button" class="movePanel moveUp btn btn-xs btn-default"><span class="glyphicon glyphicon glyphicon-chevron-up"></span></button>
                <button type="button" class="movePanel moveDown btn btn-xs btn-default"><span class="glyphicon glyphicon glyphicon-chevron-down"></span></button>
                <button type="button" class="removePanel btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
              </div>
            </h5>
          </div>
          <div id="breakpoints<?= $index ?>" class="collapse panel-body" data-parent="#accordion">
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
      <div class="accordion" id="defaultEffects">
        <?

        $effects = MediaEffectManagerHelper::getMediaManagerEffectArray();

        foreach($this->formData['defaultEffects'] as $index => $savedEffectValues) { ?>
          <? $effectShortName = str_replace("rex_effect_", "", $savedEffectValues["effect"])?>
          <div class="card panel panel-info">
            <div class="card-header panel-heading" data-toggle="collapse" data-target="#defaultEffects<?= $index ?>">
              <h5 class="panel-title">
                Effekt "<?= $effectShortName ?>"
                <div class="btn-group pull-right">
                  <button type="button" class="movePanel moveUp btn btn-xs btn-default"><span class="glyphicon glyphicon glyphicon-chevron-up"></span></button>
                  <button type="button" class="movePanel moveDown btn btn-xs btn-default"><span class="glyphicon glyphicon glyphicon-chevron-down"></span></button>
                  <button type="button" class="removePanel btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
                </div>
              </h5>
            </div>
            <div id="defaultEffects<?= $index ?>" class="collapse panel-body">
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

    $func = rex_request('func', 'string');

    if($func == "add") {
      $n = [];
      $n['field'] = '<button class="btn btn-apply" type="submit" name="sendit" value="1"' . rex::getAccesskey(rex_i18n::msg('add'), 'apply') . '>' . rex_i18n::msg('add') . '</button>';
      $formElements[] = $n;
    } else if ($func == "edit"){
      $n = [];
      $n['field'] = "<input type='hidden' name=\"mediatypeSet[mediatypeSetOldName]\" value=\"{$this->formData['mediatypeSetName']}\">";
      $formElements[] = $n;

      $n = [];
      $n['field'] = '<input type="hidden" name="update" value="true">';
      $formElements[] = $n;

      $n = [];
      $n['field'] = '<button class="btn btn-apply" type="submit" name="sendit" value="1"' . rex::getAccesskey(rex_i18n::msg('update'), 'apply') . '>' . rex_i18n::msg('update') . '</button>';
      $formElements[] = $n;
    } else {
      $n = [];
      $n['field'] = '<button class="btn btn-apply" type="submit" name="sendit" value="1"' . rex::getAccesskey(rex_i18n::msg('update'), 'apply') . '>' . rex_i18n::msg('update') . '</button>';
      $formElements[] = $n;
    }

    $fragment = new rex_fragment();
    $fragment->setVar('elements', $formElements, false);
    echo $fragment->parse('core/form/submit.php');
    ?>
  </form>
</div>
<script>
  $(document).ready(function(){

    //changes breakpoint name
    var oldTitle = $('.mediatypeSetName').val();
    $('.mediatypeSetName').on('keyup', function(){
      var newTitle = $(this).val();
      $('#breakpoints h5').each(function() {
        $(this).html($(this).html().replace(" \"" + oldTitle, " \"" + newTitle));
      });
      oldTitle = newTitle;
    });

    //renames breakpoint on change
    $(document).on('keyup','.breakpointName', function(){

      var newBrk = $(this).val();
      var $panelVariables = $(this).closest('.panel').find('.defaultVars');
      var $panelTitle = $(this).closest('.panel').find('h5');

      $panelTitle.html($panelTitle.html().replace(/"(.*?)"/, '"' + oldTitle + "<?= MediaTypeSet::MEDIA_SET_BREAKPOINT_DELIMETER ?>" + newBrk + '"'));
    });

    //adds a panel
    $(document).on('click', '.addPanel', function(e){
      e.stopPropagation();
      $(this).prev().append($(this).prev().find('.panel:last-child').clone());
      calculateNewPanelIndexes();
    });

    //removes a panel
    $(document).on('click', '.removePanel', function(e){
      e.stopPropagation();
      $(this).closest('.panel').remove();
      calculateNewPanelIndexes();
    });

    //moves a panel
    $(document).on('click', '.movePanel', function(e){

      e.stopPropagation();
      var $currentPanel = $(this).closest('.panel');

      if($(this).hasClass('moveUp') && $currentPanel.prev().length > 0) {
        var $previousPanel = $currentPanel.prev();
        $currentPanel = $currentPanel.detach();
        $currentPanel.insertBefore($previousPanel);
        calculateNewPanelIndexes();
      } else if($(this).hasClass('moveDown') && $currentPanel.next().length > 0) {
        var $previousPanel = $currentPanel.next();
        $currentPanel = $currentPanel.detach();
        $currentPanel.insertAfter($previousPanel);
        calculateNewPanelIndexes();
      }
    });

    //changes effect vars
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

  //calculates new accordeon panel indexes and ids
  function calculateNewPanelIndexes() {
    $('.accordion').each(function(){

      var idPrefix = $(this).attr('id');

      $(this).find('.panel').each(function(index) {

        //fix accordion ids
        var newId = idPrefix + index;
        $(this).find('.panel-heading').attr('data-target', "#" + newId);
        $(this).find('.panel-body').attr('id', newId);

        //first set value of found inputs because they do not get copied with html code
        //TODO: is there a better solution?
        $(this).find('input').each(function(){
          $(this).attr('value', $(this).val());
        });

        //fix form ids
        var thisHTML = $(this).clone().get(0).outerHTML;
        thisHTML = thisHTML.replace(/\[(\d*?)]/g, "[" + index + "]");
        $(this).replaceWith($(thisHTML));
      });

    });
  }
</script>