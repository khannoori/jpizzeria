<?php
defined( '_JEXEC' ) || exit( 'Restricted access' );

function com_install()
{
	// Install mod_pizza_menucard Module
	JFolder::move(
		implode( DS, array( JPATH_ROOT, 'components', 'com_pizza', 'modules', 
							'mod_pizza_menucard' ) ),
		implode( DS, array( JPATH_ROOT, 'modules', 'mod_pizza_menucard' ) )
	);
	
	$db =& JFactory::getDBO();
	$db->setQuery( "DELETE FROM `#__extensions` ".
				"WHERE (`element` = 'mod_pizza_menucard') " );
	$db->query();
	$db->setQuery( "INSERT INTO `#__extensions` (`extension_id`, `name`, ".
				"`type`, `element`, `folder`, `client_id`, `enabled`, ".
				"`access`, `protected`, `manifest_cache`, `params`, ".
				"`custom_data`, `system_data`, `checked_out`, ".
				"`checked_out_time`, `ordering`, `state`) VALUES ".
				"(NULL, 'Pizza Menucard', 'module', 'mod_pizza_menucard', ".
				"'', 0, 1, 1, 0, 'a:11:{s:6:\"legacy\";b:0;s:4:\"name\";s:18:".
				"\"Pizza Menucard\";s:4:\"type\";s:6:\"module\";s:12:".
				"\"creationDate\";s:16:\"4 January 2012\";s:6:\"author".
				"\";s:26:\"Ismail Faizi\";s:9:\"copyright\";s:39:".
				"\"Copyright (C) 2012 Open Source Matters. All rights ".
				"reserved.\";s:11:\"authorEmail\";s:18:\"kanafghan@gmail.com\"".
				";s:9:\"authorUrl\";s:21:\"www.hamwatanet.com\";s:7:".
				"\"version\";s:3:\"1.0\";s:11:\"description\";s:0:\"\"".
				";s:5:\"group\";s:0:\"\";}', '{}', '', '', 0, ".
				"'0000-00-00 00:00:00', 0, 0);" );
	$db->query();
	$db->setQuery( "INSERT INTO `#__modules` ( `id`, `title`, `note`, ".
				"`content`, `ordering`, `position`, `checked_out`, ".
				"`checked_out_time`, `publish_up`, `publish_down`, ".
				"`published`, `module`, `access`, `showtitle`, `params`, ".
				"`client_id`, `language`) VALUES (NULL, 'Menukort', '', '', ".
				"3, 'left', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ".
				"'0000-00-00 00:00:00', 1, 'mod_pizza_menucard', 1, 1, '', ".
				"0, '*');" );
	$db->query();
	$id = $db->insertid();
	$db->setQuery( "INSERT INTO `#__modules_menu` (`moduleid`, `menuid`) ".
				"VALUES ({$id}, 0 )" );
	$db->query();
	
	// Create pizza folder for images
	JFolder::create(implode( DS, array( JPATH_ROOT, 'images', 'pizza')));
	
	echo '<strong>The component has been successfully installed togethor with its modules.</strong>';
}