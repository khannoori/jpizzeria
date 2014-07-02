<?php
// no direct access
defined('_JEXEC') or die;

$prfx = 'index.php?option=com_pizza&view=items&cid=';
foreach ($list as $item) :
?>
	<li <?php if ($_SERVER['PHP_SELF'] == JRoute::_($prfx.$item->id)) echo ' class="active"';?>> 
	<?php $levelup=$item->level - $startLevel - 1; ?>
  <h<?php echo $params->get('item_heading') + $levelup; ?>>
		<a href="<?php echo JRoute::_($prfx.$item->id); ?>">
		<?php echo $item->title;?></a>
   </h<?php echo $params->get('item_heading')+ $levelup; ?>>

		<?php
		if($params->get('show_description', 0))
		{
			echo JHtml::_('content.prepare', $item->description, $item->getParams());
		}
		if($params->get('show_children', 0) && 
				(($params->get('maxlevel', 0) == 0) || 
						($params->get('maxlevel') >= 
								($item->level - $startLevel))) && 
				count($item->getChildren()))
		{

			echo '<ul>';
			$temp = $list;
			$list = $item->getChildren();
			require JModuleHelper::getLayoutPath('mod_pizza_menucard', 
					$params->get('layout', 'default').'_items');
			$list = $temp;
			echo '</ul>';
		}
		?>
 </li>
<?php endforeach; ?>