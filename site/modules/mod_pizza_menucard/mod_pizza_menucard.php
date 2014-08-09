<?php
// no direct access
defined('_JEXEC') or die;

// Include the helper functions only once
require_once dirname(__FILE__).'/helper.php';

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base().'modules/mod_pizza_menucard/styles.css');

$list = modPizzaMenucardHelper::getList($params);
if (!empty($list)) {
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
	$startLevel = reset($list)->getParent()->level;
	require JModuleHelper::getLayoutPath('mod_pizza_menucard', $params->get('layout', 'default'));
}
