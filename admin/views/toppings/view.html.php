<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of toppings.
 *
 * @author Ismail Faizi
 */
class PizzaViewToppings extends JView
{
	protected $items;
	protected $pagination;

	/**
	 * Display the view
	 *
	 * @return	void
	 */
	public function display($tpl = null)
	{	
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

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
		JToolBarHelper::title(JText::_('COM_PIZZA_TOPPINGS_TITLE'));

		JToolBarHelper::addNew('topping.add');
		JToolBarHelper::editList('topping.edit');
		JToolBarHelper::deleteList('', 'toppings.delete');
		
		JToolBarHelper::divider();
		PizzaHelper::prices();
		
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_pizza');
	}
}
