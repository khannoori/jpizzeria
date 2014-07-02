<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<div class="art-box art-post">
<div class="art-box-body art-post-body">
<div class="art-post-inner">
<div class="art-postcontent">
<?php
foreach ($this->opinions as $opinion):
?> 
<div class="art-box-body art-block-body">
<div class="art-box art-blockcontent">
<div class="art-box-body art-blockcontent-body">
<div class="art-opinions-container">
<!-- Edit form -->
<form action="index.php" method="post">
	<div class="art-opinions-box">
		<textarea rows="7" cols="103" name="review"><?php echo $opinion->review;?></textarea>
	</div>
	<div class="art-opinions-btns">
		<span class="art-button-wrapper">
		<span class="art-button-l"></span>
		<span class="art-button-r"></span>
		<input 
		type="submit"
		class="button art-button"
		name="submit" 
		value="<?php echo JText::_('COM_PIZZA_CNC_SAVE_BUTTON');?>"
		>
		</span>
	</div>
<input type="hidden" name="id" value="<?php echo $opinion->id;?>">	
<input type="hidden" name="customer_id" value="<?php echo $this->customer;?>">
<input type="hidden" name="option" value="com_pizza">
<input type="hidden" name="task" value="cnc.edit">
<?php echo JHtml::_('form.token'); ?>
</form>
<!-- Delete form -->
<form action="index.php" method="post">
	<span class="art-button-wrapper">
	<span class="art-button-l"></span>
	<span class="art-button-r"></span>
	<input 
	type="submit"
	class="button art-button"
	name="submit" 
	value="<?php echo JText::_('COM_PIZZA_CNC_DELETE_BUTTON');?>"
	>
	</span>
	<input type="hidden" name="id" value="<?php echo $opinion->id;?>">	
	<input type="hidden" name="task" value="cnc.delete">
	<?php echo JHtml::_('form.token'); ?>
</form>	
</div></div></div></div>
<br />
<?php 
endforeach;
?>
</div></div></div></div>