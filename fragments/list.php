<?php
/* @var $this rex_fragment */
?>
Media Set Typen<br>
<?
foreach($this->listElements as $element) {
  ?>
  <br>
  <a href="<?= rex_url::currentBackendPage(["func" => "edit", "mediatypeSetName" => $element['mediatypeSetName']]) ?>"><?= $element['mediatypeSetName'] ?></a><br>
  <?
}
?>
<br>
<a class="btn btn-edit" href="<?= rex_url::currentBackendPage(["func" => "add"]) ?>">Neues Set hinzufügen</a>

