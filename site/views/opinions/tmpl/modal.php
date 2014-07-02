<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

foreach ($this->opinions as $opinion):
?>
<div class="art-layout-cell art-content">
<div class="art-box art-post">
<div class="art-box-body art-post-body">
<div class="art-post-inner">
<div class="art-postcontent">
<div class="art-box-body art-block-body">
<div class="art-box art-blockcontent">
<div class="art-box-body art-blockcontent-body">
<div class="art-opinions-container">
	<h3><?php echo $opinion->name;?></h3>
	<div class="art-opinion-date">
		<?php echo date("j F, Y", strtotime($opinion->creation_date)); ?>
	</div>
	<div><?php echo $opinion->review;?></div>
</div></div></div></div></div></div></div></div></div>
<br />
<?php 
endforeach;
?>
<!-- If no reviews available -->
<?php
if (empty($this->opinions)):
?>
<div style="text-align: center; width: 100%; padding: 15px 0px 15px 0px;">
<strong><?php echo JText::_('COM_PIZZA_NO_REVIEWS_ADD_ONE');?></strong>
</div>
<?php endif;?>
<!-- Dispaly form -->
<?php
if ($this->allowOpinion):
?>	
<form action="index.php" method="post">
<div class="art-opinion-form">
	<fieldset class="art-opinion-fieldset">
		<legend><?php echo JText::_('COM_PIZZA_ADD_OPINION_FORM'); ?></legend>
		<div class="art-opinions-box">
			<textarea rows="7" cols="100" name="review"></textarea>
		</div>
		<div class="art-opinions-btns">
			<span class="art-button-wrapper">
			<span class="art-button-l"></span>
			<span class="art-button-r"></span>
			<input 
			type="submit"
			class="button art-button"
			name="submit" 
			value="<?php echo JText::_('COM_PIZZA_OPINION_SUBMIT_BUTTON')?>"
			>
			</span>
		</div>
	</fieldset>
	<input type="hidden" name="customer_id" value="<?php echo $this->customer;?>">
	<input type="hidden" name="item_id" value="<?php echo $this->item;?>">
	<input type="hidden" name="option" value="com_pizza">
	<input type="hidden" name="task" value="opinion.add">
	<?php echo JHtml::_('form.token'); ?>
</div>
</form>
<?php else: ?>
<div style="text-align:center;"><?php echo JText::_('COM_PIZZA_LOGIN_TO_REVIEW'); ?></div>
<?php endif;?>