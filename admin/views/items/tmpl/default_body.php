<?php
// no direct access
defined('_JEXEC') or die;

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering';

foreach ($this->items as $i => $item) :
	$item->max_ordering = 0;
	$ordering	= ($listOrder == 'a.ordering');
	$canCreate	= $user->authorise('core.create', 'com_pizza.category.'.$item->catid);
	$canEdit	= $user->authorise('core.edit'); //$user->authorise('core.edit','com_pizza.item.'.$item->id);
	$canEditOwn	= $user->authorise('core.edit.own'); //$user->authorise('core.edit.own','com_pizza.item.'.$item->id);
	$canChange	= $user->authorise('core.edit.state'); //$user->authorise('core.edit.state','com_pizza.item.'.$item->id);
	$hasImg		= !(empty($item->image));
	$imgPath	= (!$hasImg ? 'com_pizza/generic-pizza.png' : JURI::root().$item->image);
	$imgAttr	= array('style' => 'width: 120px; height: 120px; padding-right: 10px;');
?>
<tr class="row<?php echo $i % 2; ?>">
	<td class="center">
		<?php echo JHtml::_('grid.id', $i, $item->id); ?>
	</td>
	<td>
		<div class="fltlft">
			<?php if ($hasImg): ?>
				<?php echo JHtml::_('image', $imgPath, JText::_('COM_PIZZA_ITEM_IMAGE_ALT'), $imgAttr, false); ?>
			<?php else: ?>
				<?php echo JHtml::_('image', $imgPath, JText::_('COM_PIZZA_ITEM_IMAGE_ALT_GENERIC'), $imgAttr, true); ?>
			<?php endif; ?>
		</div>
		<div class="fltlft">
			<!-- Item Number (if any) -->
			<?php if (strlen($item->number) > 0): ?>
				<div>
				<?php if (($canEdit || $canEditOwn) && !strlen($item->name)) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_pizza&task=item.edit&id='.$item->id);?>">
						<strong><?php echo $this->escape($item->number); ?></strong>
					</a>
				<?php else : ?>
					<strong><?php echo $this->escape($item->number); ?></strong>
				<?php endif; ?>
				</div>
			<?php endif; ?>
			<!-- Item Name (if any) -->
			<?php if (strlen($item->name) > 0): ?>
				<div style="padding: 10px 0px 5px 0px">
				<?php if ($canEdit || $canEditOwn) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_pizza&task=item.edit&id='.$item->id);?>">
						<?php echo $this->escape($item->name); ?>
					</a>
				<?php else : ?>
					<?php 
						echo $this->escape($item->name); 
					?>
				<?php endif; ?>
				</div>
			<?php endif; ?>
			<!-- Item Contents -->
			<div style="max-width: 250px; padding-top: 5px;">
				<strong><?php echo $item->content ? $this->escape($item->content) : 'N/A'; ?></strong>
			</div>			
			<!-- Item Description (if any) -->
			<?php if (!empty($item->description)): ?>
				<div style="max-width: 350px; padding-top: 5px;">
				<?php echo $this->escape($item->description); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="clr"> </div>
	</td>
	<!-- Item size(s) and price(s) -->
	<td class="center">
		<?php foreach ($item->prices as $s => $p) : ?>
			<div style="margin-bottom: 3px">
				<div style="float: left"><?php echo $s?></div> 
			 	<div style="padding: 0px 5px 0px 5px; float: right">
			 		<input type="text" 
			 			size="3" 
			 			maxlength="6" 
			 			value="<?php echo str_replace('.', ',', $p['price'])?>" 
			 			onblur="saveItemPrice(this, '<?php echo $item->id?>', '<?php echo $s?>', '<?php echo $p['price']?>')"/> 
					DKK
				</div>
				<div style="clear:both;"></div>
			</div>
		<?php endforeach; ?>
	</td>	
	<td class="center">
		<?php echo JHtml::_('jgrid.published', $item->state, $i, 'items.'); ?>
	</td>
	<td class="center">
		<?php echo JHtml::_('pizzaadministrator.featured', $item->featured, $i, $canChange); ?>
	</td>
	<td class="center">
		<?php echo $this->escape($item->category_title); ?>
	</td>
	<td class="order">
		<?php if ($canChange) : ?>
			<?php if ($saveOrder) :?>
				<?php if ($listDirn == 'asc') : ?>
					<span><?php echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid), 'items.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
					<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->catid == @$this->items[$i+1]->catid), 'items.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
				<?php elseif ($listDirn == 'desc') : ?>
					<span><?php echo $this->pagination->orderUpIcon($i, ($item->catid == @$this->items[$i-1]->catid), 'items.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
					<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->catid == @$this->items[$i+1]->catid), 'items.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
				<?php endif; ?>
			<?php endif; ?>
			<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
			<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
		<?php else : ?>
			<?php echo $item->ordering; ?>
		<?php endif; ?>
	</td>
	<td class="center nowrap">
		<?php echo JHtml::_('date',$item->creation_time, 'H:i d-m-Y'); ?>
	</td>
	<td class="center nowrap">
		<?php echo JHtml::_('date',$item->modification_time, 'H:i d-m-Y'); ?>
	</td>
	<td class="center">
		<?php echo (int) $item->id; ?>
	</td>
</tr>
<?php endforeach; ?>