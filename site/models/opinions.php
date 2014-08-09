<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * Opinions Model
 */
class PizzaModelOpinions extends JModelItem
{
   /**
	* @var int item id
	*/
	protected $itemId;
	
	/**
	 * @var string data
	 */
	protected $data;

   /**
	* Get the opinions
	*
	* @return string The opinions of the specified item for the view to display
	*/
	public function getData()
	{
		if (!isset($this->data))
		{
			$this->getItemId();
				
			try {
				// get the data from DB
				$db = $this->getDbo();
				$query = $db->getQuery(true);
				$query->select('R.*, U.name')
					->from('#__pizza_reviews AS R')
					->innerJoin('#__users AS U ON U.id=R.customer_id')
					->where('R.item_id='. $this->itemId)
					->order('R.creation_date');
				$db->setQuery($query);
				$this->data = $db->loadObjectList();
			} catch (Exception $e) {
				$this->setError($e->getMessage());
				return false;
			}
		}
		return $this->data;
	}
	
	public function getItemId() {
		if (!isset($this->itemId)) {
			$input = JFactory::getApplication()->input;
			$this->itemId = $input->get('itemId', 0, 'INT');
		}
		return $this->itemId;
	}
}