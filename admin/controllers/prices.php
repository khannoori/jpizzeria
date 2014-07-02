<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Prices controller class
 * 
 * @author Ismail Faizi
 */
class PizzaControllerPrices extends JControllerAdmin
{
	/**
	* Proxy for getModel.
	*
	* @param	string	$name	The name of the model.
	* @param	string	$prefix	The prefix for the PHP class name.
	*
	* @return	JModel
	*/
	public function getModel($name = 'Prices', $prefix = 'PizzaModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
	
		return $model;
	}

	public function save() {
		$input = JFactory::getApplication('administrator')->input;
		
		// Get the data
		$priceId = $input->get('priceId', 0, 'INT');
		$price = $input->get('price', '', 'STRING');
		$typeId = $input->get('typeId', 0, 'INT');
		$sizeId = $input->get('sizeId', 0, 'INT');
		
		// Initialize variables
		$url = 'index.php?option=com_pizza&view=prices&format=ajax';
		
		// Redirect if no type or size id is given
		if (!($typeId && $sizeId)) {
			$msg = JText::_('COM_PIZZA_TOPPING_PRICES_NO_TYPE_SIZE_IDS');
			$this->setRedirect($url, $msg, 'error');
		}
		
		$model = $this->getModel();
		
		if (!$model->save($priceId, $price, $typeId, $sizeId)) {
			$msg = JText::_('COM_PIZZA_TOPPING_PRICES_ERROR_SAVING');
			$this->setRedirect($url, $msg, 'error');
		}
		
		$this->setRedirect($url);
	}
}
