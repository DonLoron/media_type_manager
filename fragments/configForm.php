<div class="rex-form">
  <form action="<?= rex_url::currentBackendPage() ?>" method="post">
    <?
    //autoload lazyload?
    $selected = '';
    if($this->formData['autoloadLazyload'] == 1) $selected = 'selected="selected"';

    $n = [];
    $n['label'] = "<label for=\"autoloadLazyload\">Autoload Lazyload? <span data-toggle='tooltip' data-placement='top' data-title='Soll das JS-Asset für den lazyload automatisch geladen werden?' class='glyphicon glyphicon-info-sign'></span></label>";
    $n['field'] = "<select class='form-control' type=\"text\" id=\"autoloadLazyload\" name=\"addonConfiguration[autoloadLazyload]\"><option value='0'>Nein</option><option value='1' $selected>Ja</option></select>";
    $formElements[] = $n;

    //autoload picturefill?
    $selected = '';
    if($this->formData['autoloadPicturefill'] == 1) $selected = 'selected="selected"';

    $n = [];
    $n['label'] = "<label for=\"autoloadPicturefill\">Autoload Picturefill? <span data-toggle='tooltip' data-placement='top' data-title='Soll das JS-Asset für den <picture> polyfill automatisch geladen werden?' class='glyphicon glyphicon-info-sign'></span></label>";
    $n['field'] = "<select class='form-control' type=\"text\" id=\"autoloadPicturefill\" name=\"addonConfiguration[autoloadPicturefill]\"><option value='0'>Nein</option><option value='1' $selected>Ja</option></select>";
    $formElements[] = $n;

    $n = [];
    $n['field'] = '<button class="btn btn-apply" type="submit" name="sendit" value="1"' . rex::getAccesskey(rex_i18n::msg('update'), 'apply') . '>' . rex_i18n::msg('update') . '</button>';
    $formElements[] = $n;

    $fragment = new rex_fragment();
    $fragment->setVar("elements", $formElements, false);
    echo $fragment->parse("core/form/form.php");
    unset($formElements);
    ?>
  </form>
</div>