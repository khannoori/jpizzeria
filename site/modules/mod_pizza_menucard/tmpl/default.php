<?php
defined('_JEXEC') or die;
?>
<ul class="categories-module<?php echo $moduleclass_sfx; ?>">
<?php
require JModuleHelper::getLayoutPath('mod_pizza_menucard', $params->get('layout', 'default').'_items');
?>
</ul>
