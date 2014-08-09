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
	<h3><?php echo $opinion->name;?></h3>
	<div class="art-opinion-date">
		<?php echo date("j F, Y", strtotime($opinion->creation_date)); ?>
	</div>
	<div><?php echo $opinion->review;?></div>
</div></div></div></div>
<br />
<?php 
endforeach;
?>
</div></div></div></div>
<?php
if ($this->allowOpinion):
?>
<form action="index.php" method="post">
<div class="art-fieldset-container">
	<fieldset>
		<legend><?php echo JText::_('COM_PIZZA_ADD_CNC_FORM'); ?></legend>
		<div class="art-opinions-box">
			<textarea rows="7" cols="103" name="review"></textarea>
		</div>
		<div class="art-opinions-btns">
			<span class="art-button-wrapper">
			<span class="art-button-l"></span>
			<span class="art-button-r"></span>
			<input 
			type="submit"
			class="button art-button"
			name="submit" 
			value="<?php echo JText::_('COM_PIZZA_CNC_SUBMIT_BUTTON')?>"
			>
			</span>
		</div>
	</fieldset>
	<input type="hidden" name="customer_id" value="<?php echo $this->customer;?>">
	<input type="hidden" name="option" value="com_pizza">
	<input type="hidden" name="task" value="cnc.save">
	<?php echo JHtml::_('form.token'); ?>
</div>
</form>
<?php endif;?>
<br />