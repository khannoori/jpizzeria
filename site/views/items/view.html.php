<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for Items
 */
class PizzaViewItems extends JView
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// Load helper
		$this->loadHelper('items');
		
		// Assign data to the view
		$this->items = $this->get('Data');

		// load rating system
		$user = JFactory::getUser();
		$document = JFactory::getDocument();
		$document->addScriptDeclaration(
			ItemsHelper::loadRatingSystem($this->items, $user->guest));
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		JHtml::_('behavior.modal');
		
		// Display the view
		parent::display($tpl);
	}
}