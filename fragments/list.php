<?php
/* @var $this rex_fragment */
?>
<div class="panel panel-default">
  <div class="panel-heading">Media Set Typen</div>
  <table class="panel-body table table-striped">
    <thead>
      <tr>
        <th>Media Typ Name</th>
      </tr>
    </thead>
    <tbody>
    <?
    foreach($this->listElements as $element) {
      ?>
      <tr>
        <td><a href="<?= rex_url::currentBackendPage(["func" => "edit", "mediatypeSetName" => $element['mediatypeSetName']]) ?>"><?= $element['mediatypeSetName'] ?></a>
          <div class="btn-group pull-right">
            <a class="btn btn-xs btn-default" href="<?= rex_url::currentBackendPage(["func" => "edit", "mediatypeSetName" => $element['mediatypeSetName']]) ?>"><span class="glyphicon glyphicon-edit"></span></a>
            <a class="btn btn-xs btn-danger" href="<?= rex_url::currentBackendPage(["func" => "delete", "mediatypeSetName" => $element['mediatypeSetName']]) ?>"><span class="glyphicon glyphicon-trash"></span></a>
          </div>
        </td>
      </tr>
      <?
    }
    ?>
    </tbody>
  </table>
  <div class="panel-footer">
    <a class="btn btn-edit" href="<?= rex_url::currentBackendPage(["func" => "add"]) ?>">Neues Set hinzufügen</a>
  </div>
</div>
