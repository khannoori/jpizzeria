<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Items View - AJAX format
 */
class PizzaViewItems extends JView
{
	/**
	 * display method of Items view
	 * @return void
	 */
	public function display($tpl = null) 
	{
 
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			echo JText::_('COM_PIZZA_RATING_ERROR');
		} 
		else 
		{
			$result = array("result" => JText::_('COM_PIZZA_RATING_OK'));				
			echo json_encode($result);
		}
	}
}