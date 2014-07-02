<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit a topping
 *
 * @author Ismail Faizi
 */
class PizzaViewTopping extends JView
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
				'COM_PIZZA_TOPPING_'.($isNew ? 'ADD_ITEM' : 'EDIT_ITEM')
			)
		);
		
		JToolBarHelper::apply('topping.apply');
		JToolBarHelper::save('topping.save');
		JToolBarHelper::save2new('topping.save2new');
		
		// If an existing item, can save to a copy.
		if (!$isNew) {
			JToolBarHelper::save2copy('banner.save2copy');
		}
		
		if (empty($this->item->id))  {
			JToolBarHelper::cancel('topping.cancel');
		}
		else {
			JToolBarHelper::cancel('topping.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
