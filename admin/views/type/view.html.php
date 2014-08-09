<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit a topping type
 *
 * @author Ismail Faizi
 */
class PizzaViewType extends JView
{
	protected $form;
	protected $item;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');

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
		$isNew	= ($this->item->id == 0);
		
		JToolBarHelper::title(
			JText::_(
				'COM_PIZZA_TYPE_'.($isNew ? 'ADD_ITEM' : 'EDIT_ITEM')
			)
		);
		
		JToolBarHelper::apply('type.apply');
		JToolBarHelper::save('type.save');
		JToolBarHelper::save2new('type.save2new');
		
		// If an existing item, can save to a copy.
		if (!$isNew) {
			JToolBarHelper::save2copy('type.save2copy');
		}
		
		if (empty($this->item->id))  {
			JToolBarHelper::cancel('type.cancel');
		}
		else {
			JToolBarHelper::cancel('type.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
