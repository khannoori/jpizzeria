<?php
// no direct access
defined('_JEXEC') or die;

foreach ($this->items as $i => $item) :
?>
<tr class="row<?php echo $i % 2; ?>">
	<td class="center">
		<?php echo JHtml::_('grid.id', $i, $item->id); ?>
	</td>
	<!-- Topping Name -->
	<td>
		<?php echo $item->name ?>
	</td>
	<!-- Topping Extra -->
	<td class="center">
		<?php echo $item->extra ? JText::_('JYES') : JText::_('JNO')?>
	</td>
	<!-- Topping Type -->	
	<td class="center">
		<?php echo $item->type; ?>
	</td>
	<!-- Topping ID -->
	<td class="center">
		<?php echo (int) $item->id; ?>
	</td>
</tr>
<?php endforeach; ?>