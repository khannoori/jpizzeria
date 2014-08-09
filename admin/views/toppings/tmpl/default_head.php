<?php
// no direct access
defined('_JEXEC') or die;
?>
<tr>
	<th width="1%">
		<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
	</th>
	<th with="15%">
		<?php echo JText::_('COM_PIZZA_HEADING_NAME'); ?>
	</th>
	<th width="5%">
		<?php echo JText::_('COM_PIZZA_HEADING_EXTRA'); ?>
	</th>	
	<th width="15%">
		<?php echo JText::_('COM_PIZZA_HEADING_TYPE'); ?>
	</th>
	<th width="1%" class="nowrap">
		<?php echo JText::_('JGRID_HEADING_ID'); ?>
	</th>
</tr>