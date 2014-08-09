<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controller');
 
/**
 * Opinion Controller
 */
class PizzaControllerCnc extends JController
{
	
	/**
	* Proxy for getModel.
	* @since	1.6
	*/
	public function getModel($name = 'Cncs', $prefix = 'PizzaModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function save() {
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// get form data
		$input = JFactory::getApplication()->input;
		$data = $input->getArray(array(
			'review' => 'STRING',
			'customer_id' => 'INT'
		));
		$data['creation_date'] = date(JFactory::getDbo()->getDateFormat());
		
		$this->_save($data);
		
		$url = JURI::base() .'?option=com_pizza&view=cncs';
		$this->setRedirect($url);
	}
	
	public function edit() {
		// get form data
		$input = JFactory::getApplication()->input;
		$data = $input->getArray(array(
			'id' => 'INT',
			'review' => 'STRING',
			'customer_id' => 'INT'
		));
	
		$this->_save($data);
		
		$url = JURI::base() .'?option=com_pizza&view=owncncs';
		$this->setRedirect($url);
	}
	
	public function delete() {
		// get form data
		$input = JFactory::getApplication()->input;
		$id = $input->get('id', 0, 'INT');
		
		$model = $this->getModel('review');
		
		if (!$model->delete($id)) {
			$this->setMessage(JText::_('COM_PIZZA_ERROR_DELETING_REVIEW'), 'error');
		} else {
			$this->setMessage(JText::_('COM_PIZZA_DELETING_REVIEW_OK'));
		}
		
		$url = JURI::base() .'?option=com_pizza&view=owncncs';
		$this->setRedirect($url);
	}
	
	private function _save($data) {
		$model = $this->getModel('review');
		
		if (!$model->save($data)) {
			$this->setMessage(JText::_('COM_PIZZA_ERROR_SAVING_REVIEW'), 'error');
		} else {
			$this->setMessage(JText::_('COM_PIZZA_SAVING_REVIEW_OK'));
		}
	}
}