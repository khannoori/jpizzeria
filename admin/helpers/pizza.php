<?php
// No direct access
defined('_JEXEC') or die;

/**
 * Pizza component helper.
 *
 * @author Ismail Faizi
 */
class PizzaHelper
{
	public static $extension = 'com_pizza';

	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	$vName	The name of the active view.
	 *
	 * @return	void
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_PIZZA_SUBMENU_ITEMS'),
				'index.php?option=com_pizza&view=items',
			strtolower($vName) == 'items'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_PIZZA_SUBMENU_CATEGORIES'),
				'index.php?option=com_categories&extension=com_pizza',
			strtolower($vName) == 'categories'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_PIZZA_SUBMENU_TOPPINGS'),
				'index.php?option=com_pizza&view=toppings',
			strtolower($vName) == 'toppings'
		);
		JSubMenuHelper::addEntry(
			JText::_('COM_PIZZA_SUBMENU_SIZES'),
				'index.php?option=com_pizza&view=size',
			strtolower($vName) == 'size'
		);		
// 		JSubMenuHelper::addEntry(
// 			JText::_('COM_PIZZA_SUBMENU_ORDERS'),
// 			'index.php?option=com_pizza&view=orders',
// 			strtolower($vName) == 'orders'
// 		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The category ID.
	 * @param	int		The item ID.
	 *
	 * @return	JObject
	 */
	public static function getActions($categoryId = 0, $itemId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($itemId) && empty($categoryId)) {
			$assetName = 'com_pizza';
		}
		elseif (empty($itemId)) {
			$assetName = 'com_pizza.category.'.(int) $categoryId;
		}
		else {
			$assetName = 'com_pizza.item.'.(int) $itemId;
		}

		$actions = array(
			'core.admin', 
			'core.manage', 
			'core.create', 
			'core.edit', 
			'core.edit.own', 
			'core.edit.state', 
			'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	
	public static function prices($height = '210', $width = '675')
	{
		$top = 0;
		$left = 0;
		$bar = JToolBar::getInstance('toolbar');
		
		// Add a button for prices.
		$bar->appendButton('Popup', 
			'prices',
			'COM_PIZZA_BUTTON_PRICES',
			'index.php?option=com_pizza&amp;view=prices&amp;tmpl=component', 
			$width, 
			$height, 
			$top, 
			$left, 
			'');
	}
}
