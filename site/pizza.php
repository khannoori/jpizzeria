<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Add a reference to a CSS file
JHTML::_(
	'stylesheet',
	'com_pizza/site.css',
	array(),
	true
);

// Add a reference to a Javascript file
JHTML::_(
	'script',
	'com_pizza/moostarrating.js',
	true,
	true
);
JHTML::_(
	'script',
	'com_pizza/ratinghandler.js',
true,
true
);

// import joomla controller library
jimport('joomla.application.component.controller');
 
// add admin models
JController::addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models', 'pizza');

// Get an instance of the controller prefixed by Pizza
$controller = JController::getInstance('Pizza');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
 
// Redirect if set by the controller
$controller->redirect();