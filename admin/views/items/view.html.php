<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of items.
 *
 * @author Ismail Faizi
 */
class PizzaViewItems extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 *
	 * @return	void
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

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
		$canDo	= PizzaHelper::getActions($this->state->get('filter.category_id'));
		$user	= JFactory::getUser();
		JToolBarHelper::title(JText::_('COM_PIZZA_ITEMS_TITLE'));

		if ($canDo->get('core.create') || 
			(count($user->getAuthorisedCategories('com_pizza', 'core.create'))) > 0 ) {
			JToolBarHelper::addNew('item.add');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
			JToolBarHelper::editList('item.edit');
		}

		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::divider();
			JToolBarHelper::publish('items.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('items.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::custom('items.featured', 'featured.png', 'featured_f2.png', 'JFEATURED', true);
		}

		if ($canDo->get('core.delete')) {
			JToolBarHelper::divider();			
			JToolBarHelper::deleteList('', 'items.delete');
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_pizza');
		}
	}
}
