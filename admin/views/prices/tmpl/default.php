<?php
// no direct access
defined('_JEXEC') or die;
?>
<fieldset>
	<div class="fltrt">
		<button type="button" 
			onclick="<?php echo JRequest::getBool('refresh', 0) ? 'window.parent.location.href=window.parent.location.href;' : '';?>  window.parent.SqueezeBox.close();">
			<?php echo JText::_('JCANCEL');?></button>
	</div>
	<div class="configuration" >
		<?php echo JText::_('COM_PIZZA_TOPPING_PRICES') ?>
	</div>
</fieldset>
<fieldset>	
<table class="adminlist">
	<!-- Header: Item Sizes -->
	<tr>
		<td>&nbsp;</td>
		<?php foreach ($this->sizes as $size):?>
			<td><strong><?php echo $size['name']?></strong></td>
		<?php endforeach;?>
	</tr>
	<!-- Body: Topping types and sizes -->
	<?php foreach ($this->types as $i => $type):?>
	<tr>
		<td><strong><?php echo $type['name']?></strong></td>
		<?php 
			foreach ($this->sizes as $j => $size):
				$val = $this->matrix[$i][$j] ? $this->matrix[$i][$j]->price : '';
				$id	= $this->matrix[$i][$j] ? $this->matrix[$i][$j]->id : 0;  
				$func = 'saveToppingPrice(this, ';
				$func .= $id .', ';
				$func .= $i .', ';
				$func .= $j .', ';
				$func .= "'$val')";
		?>
			<td>
				<input type="text" 
					value="<?php echo str_replace('.', ',', $val)?>" 
					onblur="<?php echo $func?>"
					size="5">
			</td>	
		<?php endforeach;?>
	</tr>
	<?php endforeach;?>
</table>
</fieldset>