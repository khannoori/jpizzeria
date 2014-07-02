<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Reviews view
 */
class PizzaViewCncs extends JView
{
	// Overwriting JView display method
	function display($tpl = null)
	{		
		$this->opinions = $this->get('Data');		
		
		if (!is_array($this->opinions)) {
			$this->opinions = array();	
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Check whether we have a registered user
		$this->allowOpinion = !JFactory::getUser()->guest;
		
		// get the customer
		if ($this->allowOpinion)
			$this->customer = JFactory::getUser()->id;
		
		parent::display($tpl);
	}
}