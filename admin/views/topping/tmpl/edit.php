<?php
// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>
<script type="text/javascript">
	//Joomla.submitbutton = function(task) {
		
	//}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_pizza&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="topping-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo !isset($this->item->id) ? JText::_('COM_PIZZA_NEW_TOPPING') : JText::sprintf('COM_PIZZA_EDIT_TOPPING', $this->item->id); ?></legend>
			<ul class="adminformlist">			
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>
	
				<li><?php echo $this->form->getLabel('extra'); ?>
				<?php echo $this->form->getInput('extra'); ?></li>
				
				<li><?php echo $this->form->getLabel('type_id'); ?>
				<?php echo $this->form->getInput('type_id'); ?></li>

				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
			</ul>
		</fieldset>
	</div>
	
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
