<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * General Controller of Pizza component
 */
class PizzaController extends JController
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false) 
	{
		require_once JPATH_COMPONENT.'/helpers/pizza.php';
		
		$input = JFactory::getApplication()->input;
		$view = $input->get('view', 'Items', 'STRING');
		
		// set default view if not set
		$input->set('view', $view);
		
		// load the submenu
		PizzaHelper::addSubmenu($view);
		
		// call parent behavior
		parent::display($cachable);
	}
}