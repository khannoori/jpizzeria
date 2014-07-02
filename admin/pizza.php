<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Allow access only to special users
if (!JFactory::getUser()->authorise('core.manage', 'com_pizza'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include the helper files from front-end
require_once(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'items.php');

// Set some global property
// $document = JFactory::getDocument();
// $document->addStyleDeclaration(
// 	'.icon-48-xpider {background-image: 
// 			url(../media/com_xpider/images/xpider_icon_48x48.png);}');

// Add a reference to a Javascript file
JHTML::_(
	'script', 
	'com_pizza/backendajax.js', 
	true, 
	true
);

// Add a reference to a CSS file
JHTML::_(
	'stylesheet',
	'com_pizza/admin.css',
	array(),
	true
);

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Xpider
$controller = JController::getInstance('Pizza');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();