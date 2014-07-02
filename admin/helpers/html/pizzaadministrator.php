<?php
// no direct access
defined('_JEXEC') or die;

/**
 * JHtml extension for the pizza component
 * 
 * @author Ismail Faizi
 */
abstract class JHtmlPizzaAdministrator
{
	/**
	 * @param	int $value	The state value
	 * @param	int $i
	 */
	static function featured($value = 0, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states	= array(
			0	=> array('disabled.png',
						'items.featured',	
						'COM_PIZZA_UNFEATURED',	
						'COM_PIZZA_TOGGLE_TO_FEATURE'),
			1	=> array('featured.png',
						'items.unfeatured',	
						'COM_PIZZA_FEATURED',		
						'COM_PIZZA_TOGGLE_TO_UNFEATURE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$html	= JHtml::_('image','admin/'.$state[0], JText::_($state[2]), NULL, true);
		if ($canChange) {
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'
					. $html.'</a>';
		}

		return $html;
	}
}
