<?php
/* @var $this rex_fragment */
?>
Das isch eh liste!<br>
<?
foreach($this->listElements as $element) {
  ?>
  <br>
  <a href="<?= rex_url::currentBackendPage(["func" => "edit", "mediatypeSetName" => $element['mediatypeSetName']]) ?>"><?= $element['mediatypeSetName'] ?></a><br>
  <?
}
?>
<br>
<a class="btn btn-edit" href="<?= rex_url::currentBackendPage(["func" => "add"]) ?>">¡Neu!</a>

