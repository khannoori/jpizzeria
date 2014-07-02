<?php
defined('JPATH_BASE') or die;

/**
 * Form Field class for pizza component.
 * Supports a generic list of toppings to choose as item contents
 *
 * @author Ismail Faizi
 */
class JFormFieldItemcontents extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Itemcontents';

	/**
	 * Method to get the field input markup
	 *
	 * @return  string  The field input markup
	 */
	protected function getInput()
	{
		$this->multiple = true;
		
		// Build styles and add them to the document
		$styles = array();
		$styles[] = 'div#field-container {';
		$styles[] = '	width: 350px;';
		$styles[] = '	border: 1px solid #CCC;';
		$styles[] = '	padding: 5px 5px 5px 5px;';
		$styles[] = '	clear: both;';
		$styles[] = '}';
		$styles[] = 'div.topping {';
		$styles[] = '	cursor: move;';
		$styles[] = '	padding: 1px 5px 1px 5px;';
		$styles[] = '	margin: 2px 2px 1px 2px;';
		$styles[] = '	background-color: #F5F5F5;';
		$styles[] = '	max-width: 150px;';
		$styles[] = '	-webkit-border-radius: 5px;';
		$styles[] = '	-moz-border-radius: 5px;';		
		$styles[] = '}';
		$styles[] = '#toppings, #contents {';
		$styles[] = '	height: 200px;';
		$styles[] = '	min-width: 150px;';
		$styles[] = '	overflow: auto;';
		$styles[] = '	background-color: #146295;';
		$styles[] = '	-webkit-border-radius: 8px;';
		$styles[] = '	-moz-border-radius: 8px;';
		$styles[] = '}';
		JFactory::getDocument()->addStyleDeclaration(implode("\n", $styles));
		
		// Build scripts andd add them to the document
		$script = array();
		$script[] = "window.addEvent('domready', function() {";
		$script[] = "	$$('#toppings div').addEvent('mousedown', function(event){";
		$script[] = "		event.stop();";
		$script[] = "		var topping = this;";
		$script[] = "		var clone = topping.clone().setStyles(topping.getCoordinates()).setStyles({";
		$script[] = "			opacity: 0.6,";
		$script[] = "			position: 'absolute'";
		$script[] = "		}).inject(document.body);";
		$script[] = "		var drag = new Drag.Move(clone, {";
		$script[] = "			container: document.id('field-container'),";
		$script[] = "			droppables: document.id('contents'),";
		$script[] = "			onEnter: function(draggable, droppable) {";
		$script[] = "				droppable.tween('background-color', '#58A6D9');";
		$script[] = "			},";
		$script[] = "			onLeave: function(draggable, droppable) {";
		$script[] = "				droppable.tween('background-color', '#146295');";
		$script[] = "			},";
		$script[] = "			onDrop: function(draggable, droppable) {";
		$script[] = "				draggable.destroy();";
		$script[] = "				if (droppable != null) {";
		$script[] = "					var content = topping.clone();";
		$script[] = "					// Create the drag for the dropped element in order to";
		$script[] = "					// implement the delete operation";
		$script[] = "					content.addEvent('mousedown', function(e) {";
		$script[] = "						e.stop();";
		$script[] = "						var toDelete = this;";
		$script[] = "						var cln = toDelete.clone().setStyles(toDelete.getCoordinates()).setStyles({";
		$script[] = "							opacity: 0.6,";
		$script[] = "							position: 'absolute'";
		$script[] = "						}).inject(document.body);";
		$script[] = "						var delDrag = new Drag.Move(cln, {";
		$script[] = "							container: document.id('field-container'),";
		$script[] = "							droppables: document.id('toppings'),";
		$script[] = "							onDrop: function(draggable, droppable) {";
		$script[] = "								draggable.destroy();";
		$script[] = "								if (droppable != null) {";
		$script[] = "									$$('#sel-contents option').each(function(elem){";
		$script[] = "										if (elem.get('html') == toDelete.get('html'))";
		$script[] = "											elem.destroy();";
		$script[] = "									});";
		$script[] = "									toDelete.destroy();";
		$script[] = "								}";
		$script[] = "							}";
		$script[] = "						});";
		$script[] = "						delDrag.start(e);";
		$script[] = "					});";
		$script[] = "					content.inject(droppable, 'bottom');";
		$script[] = "					droppable.highlight('#58A6D9', '#146295');";
		$script[] = "					// Create the option element for the select";
		$script[] = "					var opt = new Element('option', {";
		$script[] = "						value: topping.get('id').substring(1),";
		$script[] = "					    html: topping.get('html')";
		$script[] = "					});";
		$script[] = "					opt.inject(document.id('sel-contents'));";
		$script[] = "					opt.setProperty('selected', 'selected');";
		$script[] = "				}";
		$script[] = "			},";
		$script[] = "			onCancel: function(dragging){";
		$script[] = "		        dragging.destroy();";
		$script[] = "		    }";
		$script[] = "		});";
		$script[] = "		drag.start(event);";
		$script[] = "	});";
		// If value is set add drag-n-drop in order to delete
		if (is_array($this->value) && !empty($this->value)) {
			$script[] = "	$$('#contents div').addEvent('mousedown', function(e) {";
			$script[] = "		e.stop();";
			$script[] = "		var toDelete = this;";
			$script[] = "		var cln = toDelete.clone().setStyles(toDelete.getCoordinates()).setStyles({";
			$script[] = "			opacity: 0.6,";
			$script[] = "			position: 'absolute'";
			$script[] = "		}).inject(document.body);";
			$script[] = "		var delDrag = new Drag.Move(cln, {";
			$script[] = "			container: document.id('field-container'),";
			$script[] = "			droppables: document.id('toppings'),";
			$script[] = "			onDrop: function(draggable, droppable) {";
			$script[] = "				draggable.destroy();";
			$script[] = "				if (droppable != null) {";
			$script[] = "					$$('#sel-contents option').each(function(elem){";
			$script[] = "						if (elem.get('html') == toDelete.get('html'))";
			$script[] = "							elem.destroy();";
			$script[] = "					});";
			$script[] = "					toDelete.destroy();";
			$script[] = "				}";
			$script[] = "			}";
			$script[] = "		});";
			$script[] = "		delDrag.start(e);";
			$script[] = "	});";
		}
		$script[] = "});";
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
		
		// Get the field options.
		$options = (array) $this->getOptions();

		// Build the HTML script
		$html = array();
		$html[] = '<div id="field-container">';
		$html[] = '   <div style="float:left">';
		$html[] = '      <div id="contents">';
		// If value is set display it
		if (is_array($this->value) && !empty($this->value)) {
			foreach ($this->value as $topping) {
				$html[] = $this->getOption($topping->id, $topping->name);
			}
		}
		$html[] = '      </div>';
		$html[] = '   </div>';
		$html[] = '   <div style="float:right">';
		$html[] = '      <div id="toppings">';
		// Display options
		foreach ($options as $option) {
			$html[] = $this->getOption($option->id, $option->name);
		}
		$html[] = '      </div>';
		$html[] = '   </div>';
		$html[] = '   <div style="clear: both;"></div>';
		// If value is set store it for edit
		$sel = '  <select style="display: none;" name="';
		$sel .= $this->getName($this->fieldname) .'" ';
		$sel .= 'id="sel-contents" multiple="multiple">';
		$html[] = $sel; 
		if (is_array($this->value) && !empty($this->value)) {
			foreach ($this->value as $topping) {
				$opt = '<option value="'. $topping->id .'" ';
				$opt .= 'selected="selected"';
				$opt .= '>'. $topping->name .'</option>';
				$html[] = $opt;
			}
		}
		$html[] = '  </select>';
		$html[] = '</div';

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
			$query->from('#__pizza_toppings');
			$query->order('id');
			$db->setQuery($query);
			$options = $db->loadObjectList();
		} catch (Exception $e) {
			JFactory::getApplication('administrator')
				->enqueueMessage($e->getMessage());
		}

		return $options;
	}
	
	protected function getOption($id, $value) {
		$h = '         <div class="topping" ';
		$h .= 'id="t'. $id .'">'. $value .'</div>';
		return $h;
	}
}
