<?php
defined('JPATH_BASE') or die;

/**
 * Form Field class for pizza component.
 * Supports a generic list of item prices for defined item sizes
 *
 * @author Ismail Faizi
 */
class JFormFieldItemprices extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Itemprices';

	/**
	 * Method to get the field input markup
	 *
	 * @return  string  The field input markup
	 */
	protected function getInput()
	{			
		$this->multiple = true;
		
		// Get the field options.
		$options = (array) $this->getOptions();
	
		// Build the HTML script
		$html = array();
		$html[] = '<div style="clear:both; width: 150px;">';
		// Display price options
		foreach ($options as $size) {
			$value = 0;
			// If value is set display it
			if (is_array($this->value) && !empty($this->value)) {
				if (isset($this->value[$size->name])) {
					$value = $this->value[$size->name]['price'];
				}
			}
			$fieldPrams = array(
				'type'		=> "text",
				'id'		=> "prices",
				'name'		=> $this->fieldname ."[". $size->name ."]",
				'size'		=> "5",
				'maxlength'	=> "6",
				'value'		=> $value ? $value : '',
				'style'		=> "margin: 0 5px 0 0;"
			);
			// render field
			$field = '   <input';
			foreach ($fieldPrams as $k => $v) {
				$field .= ' '. $k .'="'. $v .'"';	
			}
			$field .= '>';
			$html[] = '	<div style="margin-bottom: 3px">';
			$html[] = '	 <div style="float: left">'. $size->name .'</div>';
			$html[] = '	 <div style="padding: 0; float: right">';
			$html[] = $field.' DKK';
			$html[] = '  </div>';
			$html[] = '	 <div style="clear: both"></div>';
			$html[] = ' </div>';
		}
		$html[] = '</div>';

		return implode("\n",$html);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		try {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('id, name');
			$query->from('#__pizza_sizes');
			$query->order('id');
			$db->setQuery($query);
			$options = $db->loadObjectList();
		} catch (Exception $e) {
			JFactory::getApplication('administrator')
				->enqueueMessage($e->getMessage());
		}

		return $options;
	}
}
