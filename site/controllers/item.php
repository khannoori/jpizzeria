<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controller');
 
/**
 * Item Controller
 */
class PizzaControllerItem extends JController
{
	/**
	* Proxy for getModel.
	* @since	1.6
	*/
	public function getModel($name = 'Item', $prefix = 'PizzaModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function rate() {
		$input = JFactory::getApplication()->input;
		$itemId = $input->get('itemId', 0, 'INT');
		$rating = $input->get('rating', 0, 'INT');
		$user = JFactory::getUser()->id;
				
		$model = $this->getModel();
		$model->saveRating($itemId, $rating, $user);
		
		$url = JURI::base();
		$url .= "index.php?option=com_pizza";
		$url .= "&view=items";
		$url .= "&format=ajax";
		$this->setRedirect($url);
	}
}