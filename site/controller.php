<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * Pizza Component Controller
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
		// set model(s) for the owncncs view
		$view = $this->getView('owncncs', 'html');
		$view->setModel($this->getModel('cncs', 'PizzaModel'), true);
	
		// call parent behavior
		parent::display($cachable);
	}
}