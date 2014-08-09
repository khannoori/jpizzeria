<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * Pizza Model
 */
class PizzaModelPizza extends JModelItem
{
	
	/**
	 * @var string data
	 */
	protected $data;
 
	/**
	* Get the data
	*
	* @return string The data for the view to display
	*/
	public function getData()
	{
		if (!isset($this->data))
		{
			try {
				// get the data from DB
				$db = $this->getDbo();
				// get item base data
				$query = $db->getQuery(true);
				$query->select('id, number, name, description, image')
					->from('#__pizza_items')
					->where('featured=1 AND state=1')
					->order('id');
				$db->setQuery($query);
				$this->data = $db->loadObjectList();
	
				if (!is_array($this->data))
				return false;
	
				// foreach item load its content and
				// its price(s), size(s) and rating
				for ($i=0; $i<count($this->data); $i++) {
					// load items content
					$content = ItemsHelper::loadItemContent($this->data[$i]->id, $db);
						
					// load items price(s) and size(s) as [size=>price,...]
					$prices =  ItemsHelper::loadItemPrices($this->data[$i]->id, $db);
						
					// load items rating
					$rating = ItemsHelper::loadItemRating($this->data[$i]->id, $db);
					
					// load items reviews
					$reviews = ItemsHelper::loadItemReviews($this->data[$i]->id, $db);
					
					//TODO remove below
					// get errors (if any)
// 					if (!($rating === false && 
// 							$prices === false && 
// 							$rating === false &&
// 							$content === false)) 
// 					{
// 						$this->setError(ItemsHelper::getErrors());
// 						return false;
// 					}
					
					// everything is ok, assign the data
					$this->data[$i]->content = $content;
					$this->data[$i]->prices = $prices;
					$this->data[$i]->rating = $rating;
					$this->data[$i]->reviews = $reviews;
				}
			} catch (Exception $e) {
				$this->setError($e->getMessage());
				return false;
			}
		}
		return $this->data;
	}
}