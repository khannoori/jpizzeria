<?php
// no direct access
defined('_JEXEC') or die;

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering';
?>
<tr>
	<th width="1%">
		<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
	</th>
	<th>
		<?php echo JText::_('COM_PIZZA_HEADING_DETAILS'); ?>
	</th>
	<th width="12%">
		<?php echo JText::_('COM_PIZZA_HEADING_PRICES'); ?>
	</th>	
	<th width="5%">
		<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
	</th>
	<th width="5%">
		<?php echo JHtml::_('grid.sort', 'JFEATURED', 'a.featured', $listDirn, $listOrder, NULL, 'desc'); ?>
	</th>
	<th width="10%">
		<?php echo JHtml::_('grid.sort', 'JCATEGORY', 'category_title', $listDirn, $listOrder); ?>
	</th>
	<th width="10%">
		<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
		<?php if ($saveOrder) :?>
			<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'items.saveorder'); ?>
		<?php endif; ?>
	</th>
	<th width="10%">
		<?php echo JHtml::_('grid.sort', 'COM_PIZZA_HEADING_CRTIME', 'a.creation_time', $listDirn, $listOrder); ?>
	</th>
	<th width="10%">
		<?php echo JHtml::_('grid.sort', 'COM_PIZZA_HEADING_MODTIME', 'a.modification_time', $listDirn, $listOrder); ?>
	</th>
	<th width="1%" class="nowrap">
		<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
	</th>
</tr>