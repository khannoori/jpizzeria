<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Size controller class
 * 
 * @author Ismail Faizi
 */
class PizzaControllerSize extends JControllerForm
{
   /**
	* Method to cancel managing of sizes and return to items
	*
	* @param   string  $key  The name of the primary key of the URL variable.
	* @return  boolean  True if access level checks pass, false otherwise.
	*/
	public function cancel($key = null)
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$this->setRedirect('index.php?option='. $this->option .'&view=items');
	
		return true;
	}
		
   /**
	* Method to edit an existing record.
	*
	*/
	public function edit()
	{
		$input = JFactory::getApplication('administrator')->input;

		$url = 'index.php?option=com_pizza&view=size&format=ajax&task=edit';
		
		$id = $input->get('id', 0, 'INT');
		$size = $input->get('size', '', 'STRING');
		
		if (!$id) {
			$msg = JText::_('COM_PIZZA_ITEM_SIZE_ERROR_NO_ID');
			$this->setRedirect($url, $msg, 'error');
			return false;
		}
		
		if (empty($size)) {
			$msg = JText::_('COM_PIZZA_ITEM_SIZE_ERROR_NO_SIZE');
			$this->setRedirect($url, $msg, 'error');
			return false;
		}
		
		$model = $this->getModel();
		
		if (!$model->save(array('id'=>$id, 'name'=>$size))) {
			$msg = JText::_('COM_PIZZA_ITEM_SIZE_ERROR_EDITING_RECORD');
			$this->setRedirect($url, $msg, 'error');
			return false;
		}
		
		$this->setRedirect($url);
		return true;
		
	}
	
   /**
	* Method to remove an existing record.
	*
	*/
	public function remove()
	{
		$id = JFactory::getApplication('administrator')
				->input->get('id', 0, 'INT');
		
		$url = 'index.php?option=com_pizza&view=size&format=ajax&task=remove';
		
		if (!$id) {
			$msg = JText::_('COM_PIZZA_ITEM_SIZE_ERROR_NO_ID');	
			$this->setRedirect($url, $msg, 'error');
			return false;
		}
		
		$model = $this->getModel();
		
		if (!$model->delete($id)) {
			$msg = JText::_('COM_PIZZA_ITEM_SIZE_ERROR_REMOVING_RECORD');
			$this->setRedirect($url, $msg, 'error');
			return false;
		}
		
		$this->setRedirect($url);
		return true;
	}
	
}
