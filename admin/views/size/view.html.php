<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit a pizza item size
 *
 * @author Ismail Faizi
 */
class PizzaViewSize extends JView
{
	protected $form;
	protected $item;
	protected $sizes;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->sizes	= $this->get('Sizes');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Add the toolbar
		$this->addToolbar();
		
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);
		
		JToolBarHelper::title(JText::_('COM_PIZZA_SIZES_MANAGER'));
		
		JToolBarHelper::apply('size.apply');
		JToolBarHelper::save2new('size.save2new');
		JToolBarHelper::cancel('size.cancel');
	}
}
