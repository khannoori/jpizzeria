<?php
// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

/**
 * Items Table class
 */
class PizzaTableItems extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__pizza_items', 'id', $db);
	}
	
   /**
	* Method to set the publishing state for a row or list of rows in the database
	* table.
	*
	* @param	mixed	An optional array of primary key values to update.  If not
	*					set the instance property value is used.
	* @param	integer The publishing state. eg. [0 = unpublished, 1 = published]
	* @param	integer The user id of the user performing the operation.
	* @return	boolean	True on success.
	*/
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;
	
		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;
	
		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}
	
		// Get an instance of the table
		$table = JTable::getInstance('Items', 'PizzaTable');
	
		// For all keys
		foreach ($pks as $pk)
		{
			// Load the item
			if(!$table->load($pk))
			{
				$this->setError($table->getError());
			}
	

			// Change the state
			$table->state = $state;
	
			// Store the row
			if (!$table->store())
			{
				$this->setError($table->getError());
			}
		}
		return count($this->getErrors())==0;
	}	
}