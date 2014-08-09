<?php
defined( '_JEXEC' ) || exit( 'Restricted access' );

function com_uninstall()
{
	$db =& JFactory::getDBO();

	// Uninstall mod_pizza_menucard
	$db->setQuery( "DELETE FROM `#__modules` WHERE `module` = 'mod_pizza_menucard'" );
	$db->query();
	$db->setQuery( "DELETE FROM `#__extensions` WHERE `element` = 'mod_pizza_menucard'" );
	$db->query();
	JFolder::delete( implode( DS, array( JPATH_ROOT, 'modules', 'mod_pizza_menucard' ) ) );
	
	// Delete alle the categories
	$db->setQuery("DELETE FROM `#__categories` WHERE `extension`='com_pizza'");
	
	// Delete pizza folder
	JFolder::delete(implode(DS, array(JPATH_ROOT, 'images', 'pizza')));
}