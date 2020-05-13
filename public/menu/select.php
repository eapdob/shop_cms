<?php $parent_id = \shop\App::$app->getProperty('parent_id'); ?>
<option value="<?=$id;?>" <?=($id == $parent_id) ? 'selected' : '';?><?=(isset($_GET['id']) && $id == $_GET['id']) ? ' disabled' : '';?>><?=$tab . $category['title'];?></option>
<?php if (isset($category['childs'])): ?>
   <?=$this->getMenuHtml($category['childs'], '&nbsp;' . $tab . '-');?>
<?php endif; ?>