<?php
/**
 * @version     1.0.0
 * @package     {com_name}
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Fritsch Services <david@makethewebwork.us> - http://www.makethewebwork.us
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('radio');

/**
 * Supports an HTML select list of categories
 */
class JFormFieldDependency extends JFormFieldRadio
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'dependency';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		
		if ($this->element['dependency']) {
			$extension = (string) $this->element['dependency'];
			
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('enabled');
			$query->from('#__extensions');
			$query->where($query->qn('type') . ' = ' . $db->quote('component'));
			$query->where($query->qn('element') . ' = ' . $db->quote($extension));
			$db->setQuery($query);
			$result = $db->loadResult();
			
			if ( !$result ) {
				$disable_all = true;
			} else {
				$disable_all = false;
			}
		}

		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="radio ' . (string) $this->element['class'] . '"' : ' class="radio"';

		// Start the radio field output.
		$html[] = '<fieldset id="' . $this->id . '"' . $class . '>';

		// Get the field options.
		$options = $this->getOptions();

		// Build the radio field output.
		foreach ($options as $i => $option)
		{

			// Initialize some option attributes.
			$checked = ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
			$class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
			$disabled = (!empty($option->disable) || $disable_all) ? ' disabled="disabled"' : '';

			// Initialize some JavaScript option attributes.
			$onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

			$html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
				. htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '/>';

			$html[] = '<label for="' . $this->id . $i . '"' . $class . '>'
				. JText::alt($option->text, preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)) . '</label>';
		}

		// End the radio field output.
		$html[] = '</fieldset>';

		return implode($html);
	}
}