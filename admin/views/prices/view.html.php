<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to manage topping prices.
 *
 * @author Ismail Faizi
 */
class PizzaViewPrices extends JView
{
	protected $sizes;
	protected $types;
	protected $matrix;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->sizes	= $this->get('Sizes');
		$this->types	= $this->get('Types');
		if (!isset($this->matrix)) {
			$this->buildMatrix($this->get('Prices'));
		}

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		parent::display($tpl);
	}
	
	private function buildMatrix($prices) {
		$this->matrix = array();
		
		// Initialize the matrix
		foreach ($this->types as $i => $types) {
			foreach ($this->sizes as $j => $sizes) {
				$this->matrix[$i][$j] = null;		
			}
		}
		
		// return if prices is not an array
		if (!is_array($prices)) {
			return;
		}
		
		foreach ($prices as $price) {
			$this->matrix[$price->type_id][$price->size_id] = $price;
		}
	}
}
