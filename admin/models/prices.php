<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * Methods supporting toppings prices.
 *
 * @author Ismail Faizi
 */
class PizzaModelPrices extends JModel
{
	/**
	 * Get Item sizes
	 *
	 * @return	array List of item sizes
	 */
	public function getSizes()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('id, name');
		$query->from('#__pizza_sizes');
		$query->order('id');
		
		$db->setQuery($query);
		return $db->loadAssocList('id');
	}
	
   /**
	* Get topping types
	*
	* @return	array List of topping types
	*/
	public function getTypes()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
	
		// Select the required fields from the table.
		$query->select('id, name');
		$query->from('#__pizza_topping_types');
		$query->order('id');
	
		$db->setQuery($query);
		return $db->loadAssocList('id');
	}

   /**
	* Get Prices
	*
	* @return	array List of topping prices
	*/
	public function getPrices()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
	
		// Select the required fields from the table.
		$query->select('id, price, size_id, type_id');
		$query->from('#__pizza_topping_prices');
		$query->order('id');
	
		$db->setQuery($query);
		return $db->loadObjectList();
	}	
	
	public function save($priceId, $price, $typeId, $sizeId) 
	{
		try {
			$db = $this->getDbo();
			
			// Add a new record if price ID is not set
			if (!$priceId) {
				$query = "INSERT INTO `#__pizza_topping_prices` ";
				$query .= "(price, size_id, type_id) ";
				$query .= "VALUES ('$price', '$sizeId', '$typeId')";
				
				$db->setQuery($query);
				return $db->query();
			} 
			// otherwise, save the record
			else {
				$query = "UPDATE `#__pizza_topping_prices` SET ";
				$query .= "price='". str_replace(',', '.', $price) ."' ";
				$query .= "WHERE id=". (int) $priceId ." ";
				$query .= "AND size_id=". (int) $sizeId ." ";
				$query .= "AND type_id=". (int) $typeId;
				
				$db->setQuery($query);
				return $db->query();
			}		
		} catch (Exception $e) {
			$this->setError($e->getMessage());
		}
		
		return false;
	}
}
