<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Item Sizes View - AJAX format
 */
class PizzaViewSize extends JView
{
	/**
	 * display method of Item view
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
			$input = JFactory::getApplication()->input;
			$task = $input->get('task', 'update', 'STRING');
			
			$result = array('error' => '');
			
			switch ($task) {
				case 'remove':
				case 'edit':
					// get the Data
					$result['result'] = 'OK';
				break;
				default:
					$result['error'] = JText::_('COM_PIZZA_ITEM_SIZE_UNDEFINED_TASK');
			}
				
			echo json_encode($result);
		}
	}
}