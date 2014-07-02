<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');
 
/**
 * Review Model
 */
class PizzaModelReview extends JModelAdmin
{
	/**
	 * Returns a reference to the Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Reviews', $prefix = 'PizzaTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
   /**
	* Method for getting the form from the model.
	*
	* @param   array    $data      Data for the form.
	* @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	*
	* @return  mixed  A JForm object on success, false on failure
	* @since   11.1
	*/
	public function getForm($data = array(), $loadData = true) {
		return false;
	}
	
}