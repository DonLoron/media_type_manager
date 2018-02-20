<?php
/**
 * User: Laurin Waller
 * Date: 13.12.17
 * Time: 16:05
 */

$func = rex_request('func', 'string');

if ($func == '') {
  $list = rex_list::factory("SELECT * FROM `" . rex::getTablePrefix() . "media_effect_set` ORDER BY `name` ASC");
  $list->addTableAttribute('class', 'table-striped');
  $list->setNoRowsMessage($this->i18n('media_effect_manager_norowsmessage'));

  // icon column
  $thIcon = '<a href="' . $list->getUrl(['func' => 'add']) . '" title="' . $this->i18n('column_hashtag') . ' ' . rex_i18n::msg('add') . '"><i class="rex-icon rex-icon-add-action"></i></a>';
  $tdIcon = '<i class="rex-icon fa-file-text-o"></i>';
  $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
  $list->setColumnParams($thIcon, ['func' => 'edit', 'id' => '###id###']);

  $list->setColumnLabel('name', $this->i18n('media_effect_manager_name'));
  $list->setColumnLabel('description', $this->i18n('media_effect_manager_description'));

  $list->setColumnParams('name', ['id' => '###id###', 'func' => 'edit']);

  $list->removeColumn('id');

  $content = $list->get();

  echo $content;
} else if($func == "add" || $func == "edit") {



}

?>