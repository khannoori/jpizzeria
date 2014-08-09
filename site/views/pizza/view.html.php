<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Pizza Component
 */
class PizzaViewPizza extends JView
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// Load helper
		$this->loadHelper('items');
		
		// Assign data to the view
		$items = $this->get('Data');
		$this->items = $items ? $items : array();

		// load rating system
		$user = JFactory::getUser();
		$document = JFactory::getDocument();
		$document->addScriptDeclaration(
			ItemsHelper::loadRatingSystem($this->items, $user->guest));
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, print_r($errors, true));
			return false;
		}
		
		JHtml::_('behavior.modal');
		
		// Display the view
		parent::display($tpl);
	}
}