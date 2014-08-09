<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit an item.
 *
 * @author Ismail Faizi
 */
class PizzaViewItem extends JView
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->canDo	= PizzaHelper::getActions($this->state->get('filter.category_id'));

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
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$canDo		= PizzaHelper::getActions($this->state->get('filter.category_id'), $this->item->id);
		
		JToolBarHelper::title(
			JText::_(
				'COM_PIZZA_ITEM_'.($isNew ? 'ADD_ITEM' : 'EDIT_ITEM')
			)
		);
		
		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('com_pizza', 'core.create')) > 0)) {
			JToolBarHelper::apply('item.apply');
			JToolBarHelper::save('item.save');
			JToolBarHelper::save2new('item.save2new');
			JToolBarHelper::cancel('item.cancel');
		}
		else {
			// Since it's an existing record, check the edit permission, or 
			// fall back to edit own if the owner.
			if ($canDo->get('core.edit') || $canDo->get('core.edit.own')) {
				JToolBarHelper::apply('item.apply');
				JToolBarHelper::save('item.save');

				// We can save this record, but check the create permission 
				// to see if we can return to make a new one.
				if ($canDo->get('core.create')) {
					JToolBarHelper::save2new('item.save2new');
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create')) {
				JToolBarHelper::save2copy('item.save2copy');
			}

			JToolBarHelper::cancel('item.cancel', 'JTOOLBAR_CLOSE');
		}

// 		JToolBarHelper::divider();
// 		JToolBarHelper::help('JHELP_CONTENT_ARTICLE_MANAGER_EDIT');
	}
}
