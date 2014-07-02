<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Migrator controller class
 * 
 * @author Ismail Faizi
 */
class PizzaControllerMigrator extends JController
{

	private $_secret = 'oedstedMigrator';
	private $_dbObj = null;
	private $_customerUserIdMapping = array();
	
	public function migrate() {
		$input = JFactory::getApplication()->input;
		$cfg = JFactory::getConfig();
		
		$secret = $input->get('secret', '', 'STRING');
		$redirectURL = 'index.php?option=com_pizza';

		// redirect if secret is not given or it is wrong
		if ($secret != $cfg->get('secret')) {
			$this->setRedirect($redirectURL, 'Unkown Secret', 'error');
			return false;
		}
		
		try {
			$this->_dbObj = JFactory::getDbo();
			
			// get legacy users (customers)
			$query = $this->_dbObj->getQuery(true);
			$query->select('id');
			$query->select('dateAdded AS registerDate');
			$query->select('name');
			$query->select('address');
			$query->select('city');
			$query->select('phone');
			$query->select('email');
			$query->select('password');
			$query->select('IPaddress AS ip');
			$query->select('lastLogin AS lastvisitDate');
			$query->select('visits');
			$query->from('customers');
			$this->_dbObj->setQuery($query);
			$customers = $this->_dbObj->loadObjectList();
			// migrate to users table of joomla
			if ( !$this->migrateUsers($customers) ) {
				$this->setRedirect($redirectURL, 'Could not migrate users', 'error');
				return false;
			}
			
			// get legacy toppings
			$query = "SELECT name FROM toppings";
			$this->_dbObj->setQuery($query);
			$toppings = $this->_dbObj->loadAssocList(null, 'name');
			// migrate to toppings table of com_pizza
			if ( !$this->migrateToppings($toppings) ) {
				$this->setRedirect($redirectURL, 'Could not migrate toppings', 'error');
				return false;
			}
			
			// get legacy items
			$query = "SELECT * FROM brochure";
			$this->_dbObj->setQuery($query);
			$tbls = $this->_dbObj->loadObjectList();
			if (!is_array($tbls)) {
				$this->setRedirect($redirectURL, 'Empty brochure', 'error');
				return false;
			}
			// migrate a table at a time
			foreach ($tbls as $tbl) {
				if (!$this->migrateTable($tbl)) {
					$this->setRedirect($redirectURL, 
						'Could not migrate table: '. $tbl->id, 
						'error');
					return false;
				}
			}
			
			// get legacy orders
			$query = "SELECT * FROM orders";
			$this->_dbObj->setQuery($query);
			$orders = $this->_dbObj->loadObjectList();
			if (!is_array($orders)) {
				$this->setRedirect($redirectURL, 'No order', 'error');
				return false;
			}
			// migrate an order at a time
			foreach ($orders as $order) {
				if (!$this->migrateOrder($order)) {
					$this->setRedirect($redirectURL,
									'Could not migrate order: '. $order->id, 
									'error');
					return false;
				}
			}
			
		} catch (Exception $e) {
			$this->setRedirect($redirectURL, $e->getMessage(), 'error');
			return false;
		}
		
		$this->setRedirect($redirectURL, 'Migration OK!');
		return true;
	}
	
	private function migrateUsers($users) {
		if (!is_array($users)) return false;
		
		foreach ($users as $i => $user) {
			$q = "INSERT INTO #__users (`name`,`username`,`email`,`password`,";
			$q .= "`block`,`sendEmail`,`registerDate`,`lastvisitDate`) ";
			$q .= "VALUES (";
			// values
			$q .= "'". $user->name ."',";
			$q .= "'". $user->email ."',";
			$q .= "'". $user->email ."',";
			$q .= "'". $user->password ."',";
			$q .= "'0',";
			$q .= "'1',";
			$q .= "'". $user->registerDate ."',";
			$q .= "'". $user->lastvisitDate ."'";
					
			// close
			$q .= ")";
			$this->_dbObj->setQuery($q);
			if (!$this->_dbObj->query()) return false;
			
			// get the id of the just created user
			$newUser = $this->_dbObj->insertid();
			
			// creat a mapping for this user
			$this->_customerUserIdMapping[$user->id] = $newUser;
			
			// insert user profile data
			$q = "INSERT INTO #__user_profiles (`user_id`,`profile_key`,";
			$q .= "`profile_value`,`ordering`) VALUES ";
			$q .= "('$newUser','profile.address1','". $user->address ."','1'),";
			$q .= "('$newUser','profile.city','". $user->city ."','2'),";
			$q .= "('$newUser','profile.postal_code','7100','3'),";
			$q .= "('$newUser','profile.phone','". $user->phone ."','4'),";
			$q .= "('$newUser','legacy.ip','". $user->ip ."','5'),";
			$q .= "('$newUser','legacy.visits','". $user->visits ."','6');";
			$this->_dbObj->setQuery($q);
			if (!$this->_dbObj->query()) return false;
			
			// create a mapping for the user and the usergroup (registered)
			$q = "INSERT INTO #__user_usergroup_map (`user_id`,`group_id`) ";
			$q .= "VALUES ('$newUser','2')";
			$this->_dbObj->setQuery($q);
			if (!$this->_dbObj->query()) return false;
		}
		
		return true;
	}
	
	
	private function migrateToppings($toppings) {
		if (!is_array($toppings)) return false;

		$q = "INSERT INTO #__pizza_toppings (`name`,`extra`) VALUES ";
		
		foreach ($toppings as $i => $topping) {
			$q .= "('$topping','1'";
			$q .= ($i < count($toppings)-1) ? ")," : ")";
		}
		
		$this->_dbObj->setQuery($q);
		return $this->_dbObj->query();
	}
	
	private function migrateTable($table) {
		// first migrate size in the given table (if any)
		$sizeCount = (int) $table->formCount;
		$sizes = array('alm.'); 
		if ($sizeCount > 1) {
			$sizes = explode('|', $table->forms);
		}
		
		// validate the array
		if (count($sizes) != $sizeCount) return false;
		
		// migrate the sizes
		$migratedSizes = $this->migrateSizes($sizes);
		if (!$migratedSizes) return false;
		
		// now we migrate the items in the table
		$tblName = 'brochure_tbl_'. $table->id;
		$q = "SELECT * FROM `$tblName`";
		$this->_dbObj->setQuery($q);
		$items = $this->_dbObj->loadObjectList();
		if (!is_array($items)) return false;
		return $this->migrateItems($items, $migratedSizes, $table->id);
	}
	
	private function migrateSizes($sizes) {
		$result = array();
		
		// foreach size add it to the db if it is not already contained
		foreach ($sizes as $size) {
			$size = trim($size);
			$q = "SELECT id FROM #__pizza_sizes WHERE `name`='". $size ."'";
			$this->_dbObj->setQuery($q);
			if ($this->_dbObj->getNumRows($this->_dbObj->query())) {
				$result[$size] = $this->_dbObj->loadResult(); 
			} else {
				// insert the size and then get its id
				$q = "INSERT INTO #__pizza_sizes (name) VALUES ('$size')";
				$this->_dbObj->setQuery($q);
				$this->_dbObj->query();
				$result[$size] = $this->_dbObj->insertid();
			}
		}
		
		return $result;
	}
	
	private function migrateItems($items, $sizes, $catid) {
		// foreach item, first migrate its price(s), then
		// its contents and then create the item
		foreach ($items as $item) {
			// we first migrate the item
			$id = $this->migrateItem($item, $catid);
			if (!$id) return false;
			
			// we, then migrate item contents
			if (!$this->migrateItemContents($item, $id)) return false;
			
			// now we migrate item price(s)
			if (!$this->migrateItemPrices($item, $id, $sizes)) return false;
		}
		
		return true;
	}
	
	private function migrateItem($item, $catid) {
		// here we insert a new record into table #__pizza_items
		$q = "INSERT INTO #__pizza_items (`number`,`name`,";
		$q .= "`state`,`featured`,`ordering`,`catid`) VALUES ";
		$q .= "('". $item->nr ."','". $item->name ."','0','0',";
		$q .= "'". ($item->nr + 1) ."','$catid')";
		$this->_dbObj->setQuery($q);
		if (!$this->_dbObj->query()) return false;
		return $this->_dbObj->insertid();
	}
	
	private function migrateItemContents($item, $itemId) {
		// get item contents as array
		$contents = explode(',', $item->content);
		$toppings = array();
		
		foreach ($contents as $topping) {
			$toppingId = $this->getToppingId($topping);
			$q = "INSERT INTO #__pizza_contents (`item_id`,`topping_id`) ";
			$q .= "VALUES ('". $itemId ."','". $toppingId ."')";
			$this->_dbObj->setQuery($q);
			if (!$this->_dbObj->query()) return false;
		}
		
		return true;
	}
	
	private function migrateItemPrices($item, $itemId, $sizes) {
		// foreach price create a record in #__pizza_item_prices
		$i=1;
		foreach ($sizes as $size => $sizeId) {
			$fields = get_object_vars($item);
			$idx = "price_$i"; 
			$price = isset($fields[$idx]) ? $fields[$idx] : $fields['price'];
			
			// prepare the query
			$q = "INSERT INTO #__pizza_item_prices (`price`,`item_id`,`size_id`) ";
			$q .= "VALUES ('". $price ."','$itemId','". $sizeId ."')";
			$this->_dbObj->setQuery($q);
			if (!$this->_dbObj->query()) return false;
			
			$i++;
		}
		
		return true;
	}
	
	private function getToppingId($topping) {
		$topping =  mb_strtolower(trim($topping), 'UTF-8');
		$q = "SELECT id FROM #__pizza_toppings WHERE name='$topping'";
		$this->_dbObj->setQuery($q);
		if ($this->_dbObj->getNumRows($this->_dbObj->query())) {
			return $this->_dbObj->loadResult();
		} else {
			$q = "INSERT INTO #__pizza_toppings (`name`,`extra`) ";
			$q .= "VALUES ('$topping','0')";
			$this->_dbObj->setQuery($q);
			$this->_dbObj->query();
			return $this->_dbObj->insertid();
		}
	}
	
	private function migrateOrder($order) {
		// check whether a mapping exists for the order's customer
		if (!isset($this->_customerUserIdMapping[$order->customerId])) {
			// we will not proceed with the migration since the order
			// does not belong to any customer
			return true;
		}
		
		$customerId = $this->_customerUserIdMapping[$order->customerId];
		$dueTime = date($this->_dbObj->getDateFormat(), 
						strtotime($order->dileveringTime));
		$statusMap = array(
			'temp'	=> 0, 
			'ok' 	=> 1, 
			'done' 	=> 2, 
			'error' => 3
		);
		$visibilityMap = array(
			'yes'	=> 1, 
			'no'	=> 0
		);
		$typeMap = array(
			'afhentning'	=> 1, 
			'udbringning'	=> 0
		);
		// prepare the query for inserting into #__pizza_orders
		$q = "INSERT INTO #__pizza_orders (`ordered_time`,`due_time`,`hits`,";
		$q .= "`status`,`visible`,`type`,`customer_id`) ";
		$q .= "VALUES ('". $order->dateOrdered ."',";
		$q .= "'". $dueTime ."',";
		$q .= "'". $order->reorderCount ."',";
		$q .= "'". $statusMap[$order->status] ."',";
		$q .= "'". $visibilityMap[$order->visible] ."',";
		$q .= "'". $typeMap[$order->dilevering] ."',";
		$q .= "'". $customerId ."')";
		// query the db
		$this->_dbObj->setQuery($q);
		if (!$this->_dbObj->query()) return false; 
		
		// get the id of the just inserted order
		$orderId = $this->_dbObj->insertid();
		
		return $this->migrateOrderItems($order->id, $orderId);
	}
	
	private function migrateOrderItems($legacyOrderId, $orderId) {
		// get legacy order items
		$q = "SELECT * FROM order_items WHERE orderId = '$legacyOrderId'";
		$this->_dbObj->setQuery($q);
		if ($this->_dbObj->getNumRows($this->_dbObj->query())) {
			$items = $this->_dbObj->loadObjectList();
			
			foreach ($items as $item) {
				if (!$this->migrateOrderItem($item, $orderId)) {
					return false;
				}
			}
		}
		
		return true;
	}
	
	private function migrateOrderItem($item, $orderId) {
		// get the id of the item price
		$q = $this->_dbObj->getQuery(true);
		$q->select('a.id');
		$q->from('#__pizza_item_prices AS a');

		// join over items
		$q->leftJoin('#__pizza_items AS b ON b.id=a.item_id');
		$q->where("b.number='". $item->nr ."'");
		
		// join over sizes (if a size is given)
		if (!empty($item->form)) {
			$q->leftJoin('#__pizza_sizes AS c ON c.id=a.size_id');
			$q->where("c.name='". trim($item->form) ."'");
		}
		
		$this->_dbObj->setQuery($q);
		$itemPriceId = $this->_dbObj->loadResult();	
		
		if (!$itemPriceId) {
			$app = JFactory::getApplication();
			$msg = 'no item price for item nr: '. $item->nr;
			$msg .= ' <b>item form: '. $item->form .'</b>';
			$msg .= ' <b>legacy order id: '. $item->orderId .'</b>';
			$app->enqueueMessage($msg, 'error');	
			return true;
		}
		
		// add a record to #__pizza_order_line
		$q = "INSERT INTO #__pizza_order_line (`item_price_id`, `order_id`) ";
		$q .= "VALUES ('$itemPriceId','$orderId')";
		$this->_dbObj->setQuery($q);
		if (!$this->_dbObj->query()) return false;
		
		// get the id of the just created order line
		$orderLineId = $this->_dbObj->insertid();
		
		if (!$this->migrateOrderItemExtras($item->plus, $orderLineId)) 
			return false;
		
		return $this->migrateOrderItemMinus($item->minus, $orderLineId);
	}
	
	private function migrateOrderItemExtras($extras, $orderLineId) {
		$extras = unserialize(stripslashes($extras));
		
		if (!count($extras)) return true;
		
		foreach ($extras as $topping)  {
			$toppingId = $this->getToppingId($topping);
			
			if ($toppingId) {
				$q = "INSERT INTO #__pizza_order_line_extras ";
				$q .= "(`order_line_id`,`topping_id`) ";
				$q .= "VALUES ('$orderLineId','$toppingId')";
			} else {
				return false;
			}
		}
		
		return true;
 	}
	
	private function migrateOrderItemMinus($minus, $orderLineId) {
		$minus = unserialize(stripslashes($minus));
		
		if (!count($minus)) return true;
		
		foreach ($minus as $topping)  {
			$toppingId = $this->getToppingId($topping);
				
			if ($toppingId) {
				$q = "INSERT INTO #__pizza_order_line_minus ";
				$q .= "(`order_line_id`,`topping_id`) ";
				$q .= "VALUES ('$orderLineId','$toppingId')";
			} else {
				return false;
			}
		}
		
		return true;
	}	
}