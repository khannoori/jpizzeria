<?php
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>
<script type="text/javascript">
	//Joomla.submitbutton = function(task) {
		
	//}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_pizza&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="size-form" class="form-validate">
	<div class="width-40 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_PIZZA_NEW_SIZE'); ?></legend>
			<ul class="adminformlist">			
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>

				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
			</ul>
		</fieldset>
	</div>
	
	<div class="width-60 fltrt">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_PIZZA_MANAGE_EXISTING_SIZES')?></legend>
			<table class="adminlist">
				<thead>
					<tr>
						<th width="1%"><?php echo JText::_('JGRID_HEADING_ID')?></th>
						<th width="10%"><?php echo JText::_('COM_PIZZA_ITEM_SIZE_NAME')?></th>
						<th width="15%"><?php echo JText::_('COM_PIZZA_ITEM_SIZE_ACTIONS')?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($this->sizes as $i => $size): ?>
					<tr id="row-<?php echo $size->id?>" class="row<?php echo $i % 2; ?>">
						<!-- ID -->
						<td class="center">
							<?php echo (int) $size->id; ?>
						</td>
						<!-- Size Name -->
						<td>
							<div id="size-<?php echo $size->id?>">
								<?php echo $size->name; ?>
							</div>
						</td>
						<!-- Actions -->
						<td class="right">
							<?php 
								echo JHtml::_(
									// function to call in JHtml
									'image', 
									// path to image
									'com_pizza/btn-remove.png',
									// alt text
									JText::_('COM_PIZZA_DELETE_SIZE'),
									// img-tag attributes
									array('id'=>"remove-btn-". $size->id,
							  			  'class'=>"remove-button",	
							  			  'onclick'=>"Sizes.remove('".$size->id."')"),
									// path is relative
									true
								);
							?>&nbsp;&nbsp;&nbsp;
							<?php 
								echo JHtml::_(
									// function to call in JHtml
									'image', 
									// path to image
									'com_pizza/btn-edit.png',
									// alt text
									JText::_('COM_PIZZA_EDIT_SIZE'),
									// img-tag attributes
									array('id'=>"edit-btn-". $size->id,
							  			  'class'=>"edit-button",	
							  			  'onclick'=>"Sizes.edit('".$size->id."')"),
									// path is relative
									true
								);
							?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</fieldset>
	</div>
	
	<div>
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
