<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/pizza.php';

/**
 * Item Model for a pizza Item.
 *
 * @author Ismail Faizi
 */
class PizzaModelItem extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 */
	protected $text_prefix = 'COM_PIZZA';

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	JTable	A database object
	*/
	public function getTable($type = 'Items', $prefix = 'PizzaTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			// Get and attach the item's contents and price(s)
			$item->content = ItemsHelper::loadItemContentAsObjectList($item->id, $this->getDbo());
			$item->prices = ItemsHelper::loadItemPrices($item->id, $this->getDbo());
		}

		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_pizza.item', 'item', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Determine correct permissions to check.
		if ($id = (int) $this->getState('item.id')) {
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
			// Existing record. Can only edit own items in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit.own');
		}
		else {
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		// Modify the form based on Edit State access controls.
		if (!$this->canEditState((object) $data)) {
			// Disable fields for display.
			$form->setFieldAttribute('featured', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an item you can edit.
			$form->setFieldAttribute('featured', 'filter', 'unset');
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_pizza.edit.item.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('item.id') == 0) {
				$app = JFactory::getApplication();
				$data->set('catid', 
					JRequest::getInt('catid', 
					$app->getUserState('com_pizza.items.filter.category_id')));
			}
		}

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 *
	 * @return	boolean	True on success.
	 */
	public function save($data)
	{
		// Alter the title for save as copy
		if (JRequest::getVar('task') == 'save2copy') {
			list($title,$alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
			$data['title']	= $title;
			$data['alias']	= $alias;
		}

		if (parent::save($data)) {
			if (isset($data['featured'])) {
				$this->featured($this->getState($this->getName().'.id'), $data['featured']);
			} 

			// Get the primary key
			$pk = (int) $this->getState($this->getName() .'.id');
			$db = $this->getDbo();
			
			// Save content of the item
			if (isset($data['content']) && !empty($data['content'])) {
				try {		
					// Remove all the tuples where <item-id, topping-id>
					// from item toppings table
					$db->setQuery('DELETE FROM #__pizza_contents WHERE item_id='. $pk);
					$db->query();
					
					// Add the new content
					$query = $db->getQuery(true);
					$query->insert('#__pizza_contents (item_id,topping_id)');
					$values = array();
					foreach ($data['content'] as $c) {
						$values[] = "'". $pk ."','". $c ."'";
					}
					$query->values($values);
					$db->setQuery($query);
					$db->query();
				} catch (Exception $e) {
					JFactory::getApplication('administrator')
						->enqueueMessage($e->getMessage(), 'error');
					return false;
				}
			}
			
			$prices = JFactory::getApplication('administrator')->input
				->get('prices', array(), 'ARRAY');
			
			// Save price(s) of the item
			if (!empty($prices)) {
				try {
					// Remove all the prices for this item
					$db->setQuery('DELETE FROM #__pizza_item_prices WHERE item_id='. $pk);
					$db->query();
					
					// Add the new prices
					$query = $db->getQuery(true);
					$query->insert('#__pizza_item_prices (price,item_id,size_id)');
					$values = array();
					foreach ($prices as $size => $price) {
						if (!empty($price)) {
							if (!($sizeObj = ItemsHelper::getSizeId($size))) {
								throw new Exception("Size not recognized!", 
										'error', '');
							}
							$price = floatval(str_ireplace(',', '.', $price));
							$values[] = "'". $price ."','". $pk ."','". $sizeObj->id ."'";
						}
					}
					$query->values($values);
					$db->setQuery($query);
					$db->query();
				} catch (Exception $e) {
					JFactory::getApplication('administrator')
						->enqueueMessage($e->getMessage(), 'error');
					return false;
				}
			}			
			return true;
		}

		return false;
	}

	/**
	 * Method to toggle the featured setting of items.
	 *
	 * @param	array	The ids of the items to toggle.
	 * @param	int		The value to toggle to.
	 *
	 * @return	boolean	True on success.
	 */
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks)) {
			$this->setError(JText::_('COM_PIZZA_NO_ITEM_SELECTED'));
			return false;
		}

		try {
			$db = $this->getDbo();

			$db->setQuery(
				'UPDATE #__pizza_items AS a' .
				' SET a.featured = '.(int) $value.
				' WHERE a.id IN ('.implode(',', $pks).')'
			);
			if (!$db->query()) {
				throw new Exception($db->getErrorMsg());
			}
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}

		$this->cleanCache();

		return true;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 *
	 * @return	array	An array of conditions to add to add to ordering queries.
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = '.(int) $table->catid;
		return $condition;
	}

	/**
	 * Custom clean the cache of com_pizza
	 *
	 * @return void
	 */
	protected function cleanCache()
	{
		parent::cleanCache('com_pizza');
	}
	
	public function update($itemId, $size, $price) {
		// check that we have a value
		if (empty($size) || empty($price)) {
			return false;	
		}
		
		try {
			$db = $this->getDbo();
			
			$query = "UPDATE `#__pizza_item_prices` AS p ";
			$query .= "LEFT JOIN `#__pizza_sizes` AS s ON s.id=p.size_id ";
			$query .= "SET p.`price`='$price'";
			$query .= "WHERE p.item_id='$itemId' AND s.name='$size'";
			
			$db->setQuery($query);
			return $db->query();
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
	}
	
	/**
	* Method to delete one or more records.
	*
	* @param   array  &$pks  An array of record primary keys.
	*
	* @return  boolean  True if successful, false if an error occurs.
	*/
	public function delete(&$pks)
	{
		if (parent::delete($pks)) {
			$db = $this->getDbo();
			
			try {
				// Delete item(s) contents
				$query = "DELETE FROM #__pizza_contents ";
				$query .= "WHERE item_id IN (". implode(',', $pks) .")";
				$db->setQuery($query);
				$db->query();
					
				// Delete item(s) prices
				$query = "DELETE FROM #__pizza_item_prices ";
				$query .= "WHERE item_id IN (". implode(',', $pks) .")";
				$db->setQuery($query);
				$db->query();
				
				return true;
			} catch (Exception $e) {
				$this->setError($e->getMessage());
				return false;
			}			
		}
		
		return false;
	}	
}
