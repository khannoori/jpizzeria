<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * Opinions Model
 */
class PizzaModelCncs extends JModelItem
{	
	/**
	 * @var string data
	 */
	protected $data;

   /**
	* Get the complaints and compliments
	*
	* @return string The complaints and compliments of shop to display
	*/
	public function getData()
	{
		if (!isset($this->data))
		{	
			try {
				// get the data from DB
				$db = $this->getDbo();
				$query = $db->getQuery(true);
				$query->select('R.*, U.name')
					->from('#__pizza_reviews AS R')
					->innerJoin('#__users AS U ON U.id=R.customer_id')
					->where('R.item_id IS NULL')
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
	
	public function getCustomerCncs($customerId) {
		try {
			// get the data from DB
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->select('R.*')
				->from('#__pizza_reviews AS R')
				->where('R.customer_id = '. $customerId)
				->order('R.creation_date');
			$db->setQuery($query);
			return $db->loadObjectList();
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
	}
}