<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Size Model for pizza item sizes.
 *
 * @author Ismail Faizi
 */
class PizzaModelSize extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 */
	protected $text_prefix = 'COM_PIZZA';

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	JTable	A database object
	*/
	public function getTable($type = 'Sizes', $prefix = 'PizzaTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	/**
	* Method to get the record form.
	*
	* @param	array	$data		Data for the form.
	* @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	*
	* @return	mixed	A JForm object on success, false on failure
	*/
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_pizza.size', 'size', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
	
		return $form;
	}
	
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_pizza.edit.size.data', array());
	
		if (empty($data)) {
			$data = $this->getItem();
		}
	
		return $data;
	}

	public function getSizes()
	{
		try {
			$db = $this->getDbo();
			
			$query = "SELECT * FROM #__pizza_sizes";
			$db->setQuery($query);
			return $db->loadObjectList();
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
		
		return false;
	}
	
   /**
	* Method to delete a record using the table instance.
	*
	* @param   int  $pk  A record primary key.
	* @return  boolean  True if successful, false if an error occurs.
	*/
	public function delete($pk)
	{
		$table = $this->getTable();
		return $table->delete($pk);
	}	
}
