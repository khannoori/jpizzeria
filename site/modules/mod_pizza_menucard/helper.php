<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.categories');

/**
 * Helper class for the module
 * 
 * @author Ismail Faizi
 *
 */
abstract class modPizzaMenucardHelper
{
	public static function getList(&$params)
	{
		$categories = JCategories::getInstance('Pizza');
		$category = $categories->get($params->get('parent', 'root'));
		$items = $category->getChildren();
		if($params->get('count', 0) > 0 && count($items) > $params->get('count', 0))
		{
			$items = array_slice($items, 0, $params->get('count', 0));
		}
		return $items;
	}

}
