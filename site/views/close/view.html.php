<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * This view is displayed after successfull saving of reviews
 * 
 * @author Ismail Faizi
 */
class PizzaViewClose extends JView
{
	/**
	 * Display the view
	 */
	function display($tpl = null)
	{
		// close a modal window
		JFactory::getDocument()->addScriptDeclaration('
			window.parent.location.href=window.parent.location.href;
			window.parent.SqueezeBox.close();
		');
	}
}
