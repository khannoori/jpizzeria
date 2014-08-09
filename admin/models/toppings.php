<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of topping records.
 *
 * @author Ismail Faizi
 */
class PizzaModelToppings extends JModelList
{
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.id, a.name, a.extra');
		$query->from('#__pizza_toppings AS a');
		
		// Join over types
		$query->select('t.name AS type');
		$query->leftJoin('#__pizza_topping_types AS t ON t.id=a.type_id');
		
		$query->order('a.id');

		return $query;
	}
}
