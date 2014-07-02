<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Toppings Table class
 */
class PizzaTableToppings extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__pizza_toppings', 'id', $db);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see JTable::check()
	 */
	public function check()
	{
		// Initialize some variables.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$query->select('id')
			->from($this->_tbl)
			->where("name = ". $db->quote(trim($this->name)));
		
		try {
			$db->setQuery($query);
			$dupilcate = $db->loadResult();
			
			if ($dupilcate)
			{
				$this->setError(JText::_('COM_PIZZA_TOPPING_DUPLICATE_ERROR'));
				return false;
			}
		} catch (JDatabaseException $e) {
			$this->setError($e->getMessage());
			return false;
		}
		
		return true;
	}
}