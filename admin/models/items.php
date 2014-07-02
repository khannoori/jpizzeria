<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of item records.
 *
 * @author Ismail Faizi
 */
class PizzaModelItems extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'number', 'a.number',
				'name', 'a.name',
				'description', 'a.description',
				'image', 'a.image',
				'catid', 'a.catid', 'category_title',
				'state', 'a.state',
				'creation_time', 'a.creation_time',
				'modification_time', 'a.modification_time',
				'ordering', 'a.ordering',
				'featured', 'a.featured'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout')) {
			$this->context .= '.'.$layout;
		}

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
				
		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		// List state information.
		parent::populateState('a.state', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.category_id');
		//$id	.= ':'.$this->getState('filter.author_id');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.number, a.name, a.description, a.catid, a.state, a.image'.
				', a.creation_time, a.modification_time, a.ordering, a.featured'
			)
		);
		$query->from('#__pizza_items AS a');

		// Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
		
// 		// Implement View Level Access
// 		if (!$user->authorise('core.admin'))
// 		{
// 		    $groups	= implode(',', $user->getAuthorisedViewLevels());
// 			$query->where('a.access IN ('.$groups.')');
// 		}

		// Filter by a single or group of categories.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('a.catid = '.(int) $categoryId);
		}
		elseif (is_array($categoryId)) {
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.catid IN ('.$categoryId.')');
		}

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '') {
			$query->where('(a.state = 0 OR a.state = 1)');
		}
		
		// Filter by search in name.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.name LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering' || $orderCol == 'category_title') {
			$orderCol = 'category_title '.$orderDirn.', a.ordering';
		}
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));

		return $query;
	}
	
   /**
	* Method to get an array of data items.
	*
	* @return  mixed  An array of data items on success, false on failure.
	* @see JModelList::getItems()
	*/
	public function getItems()
	{
		$items =& parent::getItems();
		
		try {
			$db = $this->getDbo();
			
			// foreach item load its content and
			// its price(s), size(s) and rating
			for ($i=0; $i<count($items); $i++) 
			{
				// load items content
				$content = ItemsHelper::loadItemContent($items[$i]->id, $db);
				
				// load items price(s) and size(s) as [size=>price,...]
				$prices = ItemsHelper::loadItemPrices($items[$i]->id, $db);	

				$items[$i]->content = $content;
				$items[$i]->prices = $prices;
			}
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
				
		return $items;
	}
}
