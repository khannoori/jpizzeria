<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * Item Model
 */
class PizzaModelItem extends JModelItem
{
	
   /**
	* Save item ratings
	*
	* @return string The items of the specified category for the view to display
	*/
	public function saveRating($id, $rating, $user)
	{		
		try {
			$db = $this->getDbo();
			
			// check whether customer has voted before
			$query = $db->getQuery(true);
			$query->select('vote')
				->from('#__pizza_ratings')
				->where('customer_id='. $user)
				->where('item_id='. $id);
			$db->setQuery($query);
			$vote = (int) $db->loadResult();
			
			if ($vote) {
				// update the vote if it is different
				if ($vote == $rating) return true;

				$query = $db->getQuery(true);
				$query->update('#__pizza_ratings')
					->set('vote='. $rating)
					->where('customer_id='. $customer)
					->where('item_id='. $id);
				$db->setQuery($query);
				$db->query();
			} else {
				// insert the vote of the customer for the given item
				$query = $db->getQuery(true);
				$query->insert('$__pizza_rating')
					->values("'".$customer."','".$id."'");
				$db->setQuery($query);
				$db->query();
			}
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
		return true; 
	}
}