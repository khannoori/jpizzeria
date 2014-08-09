<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controller');
 
/**
 * Opinion Controller
 */
class PizzaControllerOpinion extends JController
{
	/**
	* Proxy for getModel.
	* @since	1.6
	*/
	public function getModel($name = 'Opinions', $prefix = 'PizzaModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function add() {
		// get form data
		$input = JFactory::getApplication()->input;
		$data = $input->getArray(array(
			'review' => 'STRING',
			'customer_id' => 'INT',
			'item_id' => 'INT'
		));	
		$data['creation_date'] = date(JFactory::getDbo()->getDateFormat());
		
		if (empty($data['review'])) {
			$url = 'index.php?option=com_pizza&view=opinions&layout=modal&';
			$url .= 'tmpl=component&itemId='. $data['item_id']; 
			$this->setRedirect(
				$url, 
				JText::_('COM_PIZZA_ERROR_NO_REVIEW'),
				'error'
			);
			return false;
		}
		
		$url = '';
		$model = $this->getModel('review');		
		if (!$model->save($data)) {
			$this->setMessage(JText::_('COM_PIZZA_ERROR_SAVING_REVIEW'), 'error');
			$url = 'index.php?option=com_pizza&view=opinions&layout=modal&';
			$url .= 'tmpl=component&itemId='. $data['item_id'];
		} else {
			$url = 'index.php?option=com_pizza&view=close&tmpl=component';
		}
		
		$this->setRedirect($url);
	}
}