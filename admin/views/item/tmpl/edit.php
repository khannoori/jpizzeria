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
	//Joomla.submitbutton = function(task)
	//{
		//if (task == 'banner.cancel' || document.formvalidator.isValid(document.id('banner-form'))) {
			//Joomla.submitform(task, document.getElementById('banner-form'));
		//}
	//}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_pizza&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo !isset($this->item->id) ? JText::_('COM_PIZZA_NEW_ITEM') : JText::sprintf('COM_PIZZA_EDIT_ITEM', $this->item->id); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('number'); ?>
				<?php echo $this->form->getInput('number'); ?></li>
			
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>
	
				<li><?php echo $this->form->getLabel('description'); ?>
				<?php echo $this->form->getInput('description'); ?></li>
				
				<li><?php echo $this->form->getLabel('image'); ?>
				<?php echo $this->form->getInput('image'); ?></li>
				
				<li><?php echo $this->form->getLabel('catid'); ?>
				<?php echo $this->form->getInput('catid'); ?></li>

				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>

				<li><?php echo $this->form->getLabel('featured'); ?>
				<?php echo $this->form->getInput('featured'); ?></li>

				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
			</ul>
		</fieldset>
	</div>
	
	<div class="width-40 fltrt">
		<?php echo JHtml::_('sliders.start', 'item-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
			<?php echo JHtml::_('sliders.panel', JText::_('COM_PIZZA_GROUP_LABEL_CONTENTS'), 'item-contents'); ?>
			<fieldset class="panelform">
				<?php echo $this->form->getLabel('content'); ?>
				<?php echo $this->form->getInput('content'); ?>
			</fieldset>			

			<?php echo JHtml::_('sliders.panel', JText::_('COM_PIZZA_GROUP_LABEL_PRICES'), 'item-prices'); ?>
			<fieldset class="panelform">
				<?php echo $this->form->getLabel('prices'); ?>
				<?php echo $this->form->getInput('prices'); ?>
			</fieldset>			
			<!-- other panels goes here -->
		<?php echo JHtml::_('sliders.end'); ?>	
	</div>
	<div class="clr"></div>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
