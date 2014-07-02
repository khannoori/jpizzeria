<?php
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Pizza Items list controller class.
 *
 * @author Ismail Faizi
 */
class PizzaControllerItems extends JControllerAdmin
{
   /**
	* Constructor.
	*
	* @param	array	$config	An optional associative array of configuration settings.
	
	* @return	ContentControllerArticles
	* @see		JController
	*/
	public function __construct($config = array())
	{
		parent::__construct($config);
	
		$this->registerTask('unfeatured', 'featured');
	}
		
	/**
	 * Method to toggle the featured setting of a list of items.
	 *
	 * @return	void
	 */
	function featured()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$user	= JFactory::getUser();
		$ids	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('featured' => 1, 'unfeatured' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

// 		// Access checks.
// 		foreach ($ids as $i => $id)
// 		{
// 			if (!$user->authorise('core.edit.state', 'com_pizza.item.'.(int) $id)) {
// 				// Prune items that you can't change.
// 				unset($ids[$i]);
// 				JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
// 			}
// 		}

		if (empty($ids)) {
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else {
			// Get the model.
			$model = $this->getModel();

			// Publish the items.
			if (!$model->featured($ids, $value)) {
				JError::raiseWarning(500, $model->getError());
			}
		}

		$this->setRedirect('index.php?option=com_pizza&view=items');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param	string	$name	The name of the model.
	 * @param	string	$prefix	The prefix for the PHP class name.
	 *
	 * @return	JModel
	 */
	public function getModel($name = 'Item', $prefix = 'PizzaModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
}
