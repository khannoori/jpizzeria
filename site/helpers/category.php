<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.categories');

/**
 * Pizza Component Category Tree
 * 
 * @author Ismail Faizi
 */
class PizzaCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__pizza_items';
		$options['extension'] = 'com_pizza';
		parent::__construct($options);
	}
}
