<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Item controller class
 * 
 * @author Ismail Faizi
 */
class PizzaControllerItem extends JControllerForm
{
	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param	array	An array of input data.
	 *
	 * @return	boolean
	 * @see	JControllerForm::allowAdd()
	 */
	protected function allowAdd($data = array())
	{
		// Initialise variables.
		$user		= JFactory::getUser();
		$categoryId	= JArrayHelper::getValue($data, 'catid', JRequest::getInt('filter_category_id'), 'int');
		$allow		= null;

		if ($categoryId) {
			// If the category has been passed in the data or URL check it.
			$allow	= $user->authorise('core.create', 'com_pizza.category.'.$categoryId);
		}

		if ($allow === null) {
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd();
		}
		else {
			return $allow;
		}
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @see JControllerForm::allowEdit()
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Initialise variables.
		$recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
		$user		= JFactory::getUser();
		$userId		= $user->get('id');

		// Check general edit permission first.
		if ($user->authorise('core.edit', 'com_pizza.item.'.$recordId)) {
			return true;
		}

		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', 'com_pizza.item.'.$recordId)) {
			// Now test the owner is the user.
			$ownerId	= (int) isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId) {
				// Need to do a lookup from the model.
				$record		= $this->getModel()->getItem($recordId);

				if (empty($record)) {
					return false;
				}

				$ownerId = $record->created_by;
			}

			// If the owner matches 'me' then do the test.
			if ($ownerId == $userId) {
				return true;
			}
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	/**
	 * Method to run batch operations.
	 *
	 * @return	void
	 * @see JControllerForm::batch()
	 */
	public function batch($model)
	{
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model	= $this->getModel('Item', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_pizza&view=items'.$this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	public function update() {
		$input = JFactory::getApplication()->input;
		$itemId = $input->get('itemId', 0, 'INT');
	
		if (!$itemId) {
			$this->setMessage(JText::_('COM_PIZZA_ERROR_ITEM_ID'));
			$this->setRedirect("index.php?com_pizza&view=item&format=ajax");
			return false;
		}
		
		$price = $input->get('price', '', 'STRING');
		$size = $input->get('size', '', 'STRING');
	
		$model = $this->getModel();
		if (!$model->update($itemId, $size, $price)) {
			$errors = $model->getErrors();
			$error = implode("<br />", array_reverse($errors));
			$this->setMessage($error, 'error');
		}
	
		$url = JURI::base();
		$url .= "index.php?option=com_pizza";
		$url .= "&view=item";
		$url .= "&format=ajax";
		$url .= "&task=update";
		$url .= "&itemId=". $itemId;
		$this->setRedirect($url);
	}
}
