<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if (!is_array($this->items)) die('No data!');

foreach ($this->items as $item) {
	echo ItemsHelper::getItemLayout($item);
	echo '<br />';
}