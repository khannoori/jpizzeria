<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Topping Prices View - AJAX format
 */
class PizzaViewPrices extends JView
{
	/**
	 * display method of Topping Prices view
	 * 
	 * @return void
	 */
	public function display($tpl = null) 
	{
		$msgQueue = JFactory::getApplication()->getMessageQueue();
		// Check for errors.
		if (is_array($msgQueue) && count($msgQueue))
		{
			foreach ($msgQueue as $msg){
				echo "<p>";
				if (is_array($msg))
					echo $msg['message'];
				else
					echo $msg; 
				echo "</p>";
			}
		}
		else 
		{
// 			$input = JFactory::getApplication()->input;
// 			$itemId = $input->get('itemId', 0, 'INT');
// 			$task = $input->get('task', 'update', 'STRING');
			
// 			if (!$itemId) {
// 				echo json_encode(
// 					array('error' => JText::_('COM_PIZZA_UPDATE_ERROR_ID')));
// 			}
			
			$result = array('error' => '');
			
// 			switch ($task) {
// 				case 'update':
// 				default:
					// get the Data
					$result['result'] = 'OK';
// 				break;
// 			}
				
			echo json_encode($result);
		}
	}
}