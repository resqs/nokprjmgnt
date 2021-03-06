<?php
/**
* @version	$Id$
* @package	Joomla
* @subpackage	NoK-PrjMgnt
* @copyright	Copyright (c) 2017 Norbert Kuemin. All rights reserved.
* @license	http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE
* @author	Norbert Kuemin
* @authorEmail	momo_102@bluemail.ch
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');
// Include dependancy of the component helper
jimport('joomla.application.component.helper');
class NoKPrjMgntModelProjects extends JModelList {
	/**
	 * @since   1.6
	 */
	private $pk = '0';
	private $useAlias= true;
	protected $view_item = 'projects';
	protected $_item = null;
	protected $_membershipItems = null;
	protected $_model = 'projects';
	protected $_component = 'com_nokprjmgnt';
	protected $_context = 'com_nokprjmgnt.projects';

	private function getFields() {
		$params = JComponentHelper::getParams($this->_component);
		return array (
			"id" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_ID_LABEL',true),'`p`.`id`','right'),
			"title" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_TITLE_LABEL',true),'`p`.`title`','left'),
			"description" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_DESCRIPTION_LABEL',true),'`p`.`description`',''),
			"category_title" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_CATEGORY_LABEL',true),'`c`.`title`','left'),
			"priority" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_PRIORITY_LABEL',true),'`p`.`priority`','right'),
			"duedate" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_DUE_DATE_LABEL',true),'`p`.`duedate`','left'),
			"status" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_STATUS_LABEL',true),'`p`.`status`','left'),
			"access" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_ACCESS_LABEL',true),'`p`.`access`','left'),
			"custom1" => array($params->get('custom1'),'`p`.`custom1`','left'),
			"custom2" => array($params->get('custom2'),'`p`.`custom2`','left'),
			"custom3" => array($params->get('custom3'),'`p`.`custom3`','left'),
			"custom4" => array($params->get('custom4'),'`p`.`custom4`','left'),
			"custom5" => array($params->get('custom5'),'`p`.`custom5`','left'),
			"createdby" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_CREATEDBY_LABEL',true),'`p`.`createdby`','left'),
			"createddate" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_CREATEDDATE_LABEL',true),'`p`.`createddate`','left'),
			"modifiedby" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_MODIFIEDBY_LABEL',true),'`p`.`modifiedby`','left'),
			"modifieddate" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_MODIFIEDDATE_LABEL',true),'`p`.`modifieddate`','left')
		);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication();
		$params = $app->getParams();
		$this->setState('params', $params);
		$this->setState('filter.published',1);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string    An SQL query
	 * @since   1.6
	 */
	protected function getListQuery() {
		$user = JFactory::getUser();
		// Create a new query object.           
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields from the hello table
		$allFields = $this->getFields();
		$fields = array();
		foreach (array_keys($allFields) as $key) {
			if (isset($allFields[$key]) && !empty($allFields[$key])) {
				$field = $allFields[$key];
				array_push($fields,$field[1].' AS '.$key);
			}
		}
		$query->select($fields)
			->from($db->quoteName('#__nok_pm_projects','p'))
			->join('LEFT', $db->quoteName('#__categories', 'c').' ON ('.$db->quoteName('p.catid').'='.$db->quoteName('c.id').')');
		// Get configurations
		$this->paramsComponent = $this->state->get('params');
		$app = JFactory::getApplication();
		$currentMenu = $app->getMenu()->getActive();
		if (is_object( $currentMenu )) {
			$this->paramsMenuEntry = $currentMenu->params;
		} else {
			return $query;
		}
		// Filter by search in name.
		$where = array();
		$statuslist = $this->paramsMenuEntry->get('status');
		if ((count($statuslist) > 0) && ((count($statuslist) > 1) || !empty($statuslist[0]))) {
			array_push($where,$db->quoteName('p.status').' IN ('.implode(',',$db->quote($statuslist)).')');
		}
		array_push($where, $db->quoteName('p.access').' IN ('.implode(',',$user->getAuthorisedViewLevels()).')');
		$catid = $this->paramsMenuEntry->get('catid');
		if ($catid != '0') {
			array_push($where,$db->quoteName('p.catid').' = '.$db->quote($catid));
		}
		if (count($where) > 0) {
			$query->where(implode(' AND ',$where));
		}
		// Add the list ordering clause.
		$sort = array();
		for ($i=1;$i<=4;$i++) {
			$fieldKeyCol = 'sort_column_'.$i;
			$fieldKeyDir = 'sort_direction_'.$i;
			$key = $this->paramsMenuEntry->get($fieldKeyCol);
			if (!empty($key)) {
				if (isset($allFields[$key]) && !empty($allFields[$key])) {
					$fieldname = $allFields[$key][1];
					array_push($sort, $fieldname.' '.$this->paramsMenuEntry->get($fieldKeyDir));
				}
			}
		}
		if (count($sort) > 0) {
			$query->order(implode(', ',$sort));
		}
//echo $query;
		return $query;
	}

	public function getHeader($cols) {
		$fields = array();
		$allFields = $this->getFields();
		foreach ($cols as $col) {
			if (isset($allFields[$col])) {
				$field = $allFields[$col];
				$fields[$col] = $field[0];
			} else {
				$fields[$col] = $col;
			}
		}
		return $fields;
	}

	public function getAlign($cols) {
		$fields = array();
		$allFields = $this->getFields();
		foreach ($cols as $col) {
			if (isset($allFields[$col])) {
				$field = $allFields[$col];
				$fields[$col] = $field[2];
			}
		}
		return $fields;
	}

	public function translateFieldsToColumns($searchFields, $removePrefix=true) {
		$result = array();
		$allFields = $this->getFields();
		foreach($searchFields as $field) {
			if (isset($allFields[$field]) && !empty($allFields[$field])) {
				if ($removePrefix) {
					$resultField = str_replace('`p`.', '' , $allFields[$field][1]);
					$resultField = str_replace('`c`.', '' , $resultField);
					$resultField = str_replace('`', '' , $resultField);
					array_push($result,$resultField);
				} else {
					array_push($result,$allFields[$field][1]);
				}
			}
		}
		return $result;
	}
}
?>
