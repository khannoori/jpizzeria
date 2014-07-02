<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Customers own reviews view
 */
class PizzaViewOwncncs extends JView
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// get the customer
		$this->customer = JFactory::getUser()->id;
		
		$model = $this->getModel('cncs');
		$this->opinions = $model->getCustomerCncs($this->customer);			
		
		if (!is_array($this->opinions)) {
			$this->opinions = array();	
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		parent::display($tpl);
	}
}