<?php

defined('_JEXEC') or die;

/**
 * Helper class for pizza items
 */
abstract class ItemsHelper
{
	/**
	 * Potential DB errors
	 * @var array
	 */
	private static $errors = array();
	
	/**
	 * The item sizes in order to get a quick lookup using size name as key
	 * 
	 * @var array
	 */
	private static $sizes = array();
	
	public static function loadItemPrices($id, $db)
	{
		try {
			$query = $db->getQuery(true);
			$query->select('A.price, B.name AS size')
				->from('#__pizza_item_prices AS A')
				->innerJoin('#__pizza_sizes AS B ON B.id=A.size_id')
				->where('A.item_id='. (int) $id);
			$db->setQuery($query);
			// load items price(s) and size(s) as [size=>price,...]
			return $db->loadAssocList('size');
		} catch (Exception $e) {
			self::$errors[] = $e->getMessage();
			return false;
		}
	}
	
	public static function loadItemRating($id, $db) {
// 		try {
			$query = $db->getQuery(true);
			$query->select('AVG(R.vote) AS rating')
				->from('#__pizza_items AS I')
				->innerJoin('#__pizza_ratings AS R ON R.item_id=I.id')
				->where('I.id='. $id);
			$db->setQuery($query);
			// load items rating
			$rating = $db->loadResult();
			return $rating ? $rating : 0;
// 		} catch (Exception $e) {
// 			self::$errors[] = $e->getMessage();
// 			return false;
// 		}
	}

	public static function loadItemContent($id, $db) {
// 		try {
			$query = $db->getQuery(true);
			$query->select('A.name')
				->from('#__pizza_toppings AS A')
				->innerJoin('#__pizza_contents AS B ON B.topping_id=A.id')
				->where('B.item_id='. (int) $id);
			$db->setQuery($query);
			$rows = $db->loadAssocList();
			
			if (empty($rows)) return false;
			
			$content = $rows[0]['name'];
			for ($i=1; $i<count($rows); $i++) {
				$content .= ", ". $rows[$i]['name'];
			}
			return $content;
				
// 		} catch (Exception $e) {
// 			self::$errors[] = $e->getMessage();
// 			return false;
// 		}
	}
	
	public static function loadItemContentAsObjectList($id, $db) {
		$query = $db->getQuery(true);
		$query->select('A.id, A.name')
			->from('#__pizza_toppings AS A')
			->innerJoin('#__pizza_contents AS B ON B.topping_id=A.id')
			->where('B.item_id='. (int) $id);
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	public static function loadItemReviews($id, $db) {
		try {
			$query = $db->getQuery(true);
			$query->select('*')
				->from('#__pizza_reviews AS R')
				->where('R.item_id='. (int) $id);
			$db->setQuery($query);
			$db->query();
			$count = $db->getNumRows();
			return $count;
		} catch (Exception $e) {
			self::$errors[] = $e->getMessage();
			return false;
		}
	}
	
	public static function getItemLayout($item) 
	{
		// Initialize some variables	
		$h = array();
		$imgPath = (empty($item->image) ? 
						'com_pizza/generic-pizza.png' : 
						 JURI::base() . $item->image);
		$cfg = JComponentHelper::getParams('com_pizza');
		
		// Start building til layout
		$h[] = '<div class="art-box-body art-block-body">';
		$h[] = '	<div class="art-box art-blockcontent">';
		$h[] = '		<div class="art-box-body art-blockcontent-body">';
		$h[] = '		<div class="art-item-image">';
		// Display item image (if any)
		if (strlen($item->image) > 0) {
			$h[] = '			<a class="modal" href="'. $imgPath .'">';
		}
		$h[] = JHtml::_(
					// function to call in JHtml
					'image', 
					// path to image
					$imgPath,
					// alt text
					JText::_('COM_PIZZA_ITEM_IMAGE_ALT'),
					// img-tag attributes
					array('class'=>"art-item-img"),
					// is the path relative?
					empty($item->image));
		if (strlen($item->image) > 0) {
			$h[] = '			</a>';
		}
		$h[] = '		</div>';
		// Item name/nr
		$h[] = '		<div class="art-item-details">';
		$h[] = (empty($item->name) ? '<h3>'. $item->number .'</h3>': 
				'<h3>'. $item->number ."</h3><h4>". $item->name ."</h4>");
		$h[] = '			<div>';
		
		// add rating
		if ($cfg->get('ratings', 1))
		{
			$rating = round($item->rating);
			$h[] = '<form name="rating-'. $item->id .'">';
			$h[] = '<input type="hidden" name="id" value="'. $item->id .'">';
			for ($i=1; $i<=5; $i++) {
				if ($rating == $i)
					$h[] = '    <input type="radio" name="rating" value="'.$i.'" checked>';
				else
					$h[] = '    <input type="radio" name="rating" value="'.$i.'">';
			}
			$h[] = '</form>';
		}
		
		// add reviews
		if ($cfg->get('reviews', 1))
		{
			$link = 'index.php?option=com_pizza&amp;view=opinions&amp;';
			$link .= 'layout=modal&amp;tmpl=component&amp;itemId='. $item->id;
			$rel = '{handler: \'iframe\', size: {x:610, y:450}}';
		
			$h[] = '<a class="modal" href="'.$link.'" rel="'.$rel.'">';
			$h[] = JText::sprintf('COM_PIZZA_SEE_USERS_REVIEWS', $item->reviews);
			$h[] = '</a>';
		}
		
		// add contents of the food
		$h[] = '			</div>';
		$h[] = '			<div class="art-item-content">';
		$h[] = $item->content;
		$h[] = '			</div>';
		$h[] = '		</div>';
		
		// item size(s) and price(s)
		$h[] = '		<div class="art-item-sp">';
		if (count($item->prices) == 1)
		{
			// single price
			$p = array_shift($item->prices);
			$h[] = '				<div class="art-item-price-single">';
			$h[] = 'DKK '. str_replace('.', ',', $p['price']);
			$h[] = '				</div>';
		} 
		else
		{
			$h[] = '			<ul>';
			foreach ($item->prices as $s => $p)
			{
				$h[] = '				<li>';
				$h[] = '				<span>'. $s .'</span>';
				$h[] = '				<div class="art-item-price">';
				$h[] = 'DKK '. str_replace('.', ',', $p['price']);
				$h[] = '				</div>';
				$h[] = '				</li>';
			}
			$h[] = '			</ul>';
		} 
		$h[] = '		</div>';
		$h[] = '		<div class="cleared"></div>';
		
		// item description (if any)
		if (!empty($item->description)) 
		{
			$h[] = '			<div class="art-item-description">';
			$h[] = $item->description;
			$h[] = '			</div>';
		}
		
		$h[] = '		</div>';
		$h[] = '	</div>';
		$h[] = '	<div class="cleared"></div>';
		$h[] = '</div>';
		
		return implode("\n", $h);
	}
	
	public static function loadRatingSystem($items, $disabled = false) {
		$s = array();
		$s[] = 'window.addEvent("domready", function() {';
		$s[] = '   MooStarRatingImages.defaultImageFolder = "'. 
				JURI::base() .
				'media/com_pizza/images/"';
		
		foreach ($items as $item) {
			$s[] = '   var rating'. $item->id .' = new MooStarRating({';
			$s[] = "      form: 'rating-".$item->id."',";
			$s[] = "	  imageEmpty: 'star_empty.png',";
			$s[] = "	  imageFull:  'star_full.png',";
			$s[] = "	  imageHover: 'star_hover.png',";
		
			if ($disabled) $s[] = "	disabled: true";
		
			$s[] = '   });';
			$s[] = "   rating". $item->id .".addEvent('click', handleRating);";
		}
		
		$s[] = '})';
		
		return implode("\n", $s);
	}
	
	public static function getErrors() {
		return self::$errors;
	}
	
	public static function getSizeId($size) {
		if (empty(self::$sizes)) {
			self::loadSizes();
		}
		
		if (isset(self::$sizes[$size])) {
			return self::$sizes[$size];
		}
		
		return false;
	}
	
	private static function loadSizes() {
		try {
			$db = JFactory::getDbo();
		
			$query = "SELECT * FROM #__pizza_sizes ORDER BY id";
			$db->setQuery($query);
			$objs = $db->loadObjectList();
		
			if (!is_array($objs)) return;
		
			foreach ($objs as $obj) {
				self::$sizes[$obj->name] = $obj;
			}
		} catch (Exception $e) {
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}
	}
}