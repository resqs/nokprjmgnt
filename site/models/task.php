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
class NoKPrjMgntModelTask extends JModelForm {
	/**
	 * @since   1.6
	 */
	private $pk = '0';
	private $useAlias= true;
	protected $view_item = 'task';
	protected $_item = null;
	protected $_membershipItems = null;
	protected $_model = 'task';
	protected $_component = 'com_nokprjmgnt';
	protected $_context = 'com_nokprjmgnt.task';
	protected $_taskItems = null;

	private function getFields() {
		return array (
			"id" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_ID_LABEL',true),'`t`.`id`'),
			"title" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_TITLE_LABEL',true),'`t`.`title`'),
			"project_id" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_ID_LABEL',true),'`p`.`id`'),
			"project_title" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_TITLE_LABEL',true),'`p`.`title`'),
			"project_access" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_ACCESS_LABEL',true),'`p`.`access`'),
			"description" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_DESCRIPTION_LABEL',true),'`t`.`description`'),
			"category_title" => array(JText::_('COM_NOKPRJMGNT_PROJECT_FIELD_CATEGORY_LABEL',true),'`c`.`title`'),
			"priority" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_PRIORITY_LABEL',true),'`t`.`priority`'),
			"duedate" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_DUE_DATE_LABEL',true),'`t`.`duedate`'),
			"status" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_STATUS_LABEL',true),'`t`.`status`'),
			"progress" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_PROGRESS_LABEL',true),'`t`.`progress`'),
			"responsible_user_id" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_RESPONSIBLE_LABEL',true),'`t`.`responsible_user_id`'),
			"assign_user_ids" => array(JText::_('COM_NOKPRJMGNT_TASK_FIELD_ASSIGN_LABEL',true),'`t`.`assign_user_ids`'),
			"createdby" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_CREATEDBY_LABEL',true),'`p`.`createdby`'),
			"createddate" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_CREATEDDATE_LABEL',true),'`p`.`createddate`'),
			"modifiedby" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_MODIFIEDBY_LABEL',true),'`p`.`modifiedby`'),
			"modifieddate" => array(JText::_('COM_NOKPRJMGNT_COMMON_FIELD_MODIFIEDDATE_LABEL',true),'`p`.`modifieddate`')
		);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
	protected function populateState() {
		$app = JFactory::getApplication('site');
		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState($this->_model.'.id', $pk);
		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
		$user = JFactory::getUser();
		if ((!$user->authorise('core.edit.state', $this->_component)) &&  (!$user->authorise('core.edit', $this->_component))) {
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   type      The table type to instantiate
	 * @param   string    A prefix for the table class name. Optional.
	 * @param   array     Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object
	 */
	public function getTable($type = 'Task', $prefix = 'NoKPrjMgntTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the form object.
	 * The base form is loaded from XML and then an event is fired
	 *
	 * @param   array    $data      An optional array of data for the form to interrogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 * 
	 * @return  JForm  A JForm object on success, false on failure
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm($this->_context, $this->_model, array('control' => 'jform', 'load_data' => true));
		if (empty($form)) {
			return false;
		}
/*
		$pk = $this->getState('project.id');
		if (empty($pk)) $pk = $this->pk;
		$params = $this->getState('params');
		$project = $this->_item[$pk];
		if (is_object($project)) {
			$params->merge($project->params);
		}
*/
		return $form;
	}

	protected function loadFormData() {
		$data = (array) JFactory::getApplication()->getUserState($this->_context.'.data', array());
		$this->preprocessData($this->_context, $data);
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	/**
	 * Gets a contact
	 *
	 * @param   integer  $pk  Id for the contact
	 *
	 * @return mixed Object or null
	 */
	public function &getItem($pk = null) {
		if (empty($pk)) $pk = $this->getState($this->_model.'.id');
		if (empty($pk)) $pk = $this->pk;
		if ($this->_item === null) {
			$this->_item = array();
		}
		if (!isset($this->_item[$pk])) {
			try {
				$db = $this->getDbo();
				$query = $db->getQuery(true);
				// Select some fields from the hello table
				$fields = array();
				$allFields = $this->getFields();
				foreach ($allFields as $key => $field) {
					if ($this->useAlias) {
						array_push($fields,$field[1]." AS ".$key);
					} else {
						array_push($fields,$field[1]);
					}
				}
				$query->select($fields)
					->from($db->quoteName('#__nok_pm_tasks','t'))
					->join('LEFT', $db->quoteName('#__nok_pm_projects', 'p').' ON ('.$db->quoteName('t.project_id').'='.$db->quoteName('p.id').')')
					->join('LEFT', $db->quoteName('#__categories', 'c').' ON ('.$db->quoteName('p.catid').'='.$db->quoteName('c.id').')')
					->where('t.id = ' . (int) $pk);
				$db->setQuery($query);
				$data = $db->loadObject();
				if ($data) {
					$data->assign_user_ids = explode(',',$data->assign_user_ids);
				}
				$this->_item[$pk] = $data;
			} catch (Exception $e) {
				$this->setError($e);
				$this->_item[$pk] = false;
			}
		}
		return $this->_item[$pk];
	}

	public function getHeader($cols) {
		$fields = array();
		$allFields = $this->getFields();
		foreach ($cols as $col) {
			$field = $allFields[$col];
			array_push($fields,$field[0]);
		}
		return $fields;
	}

	public function translateFieldsToColumns($fields, $removePrefix=true) {
		$result = array();
		$allFields = $this->getFields();
		foreach($fields as $field) {
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

	public function setPk($pk) {
		$this->pk = $pk;
	}

	public function setUseAlias($useAlias) {
		$this->useAlias = $useAlias;
	}

	public function storeData($data, $projectId, $id='') {
		$state = (!empty($data['state'])) ? 1 : 0;
		$user = JFactory::getUser();

		if(empty($projectId)) {
			$projectId = $data['project_id'];
		}

		if(!empty($id)) {
			//Check the user can edit this item
			$authorised = $user->authorise('core.edit', 'com_nokprjmgnt.project.'.$projectId) || $authorised = $user->authorise('core.edit.own', 'com_nokprjmgnt.project.'.$projectId);
			if($user->authorise('core.edit.state', 'com_nokprjmgnt.project.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
				$data['state'] = 0;
			}
		} else {
			//Check the user can create new items in this section
			$authorised = $user->authorise('core.edit', 'com_nokprjmgnt.project.'.$projectId) || $authorised = $user->authorise('core.edit.own', 'com_nokprjmgnt.project.'.$projectId);
			if($user->authorise('core.edit.state', 'com_nokprjmgnt.project.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
				$data['state'] = 0;
			}
			unset($data['id']);
		}

		if ($authorised !== true) {
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		$data['project_id'] = $projectId;
		if (isset($data['assign_user_ids']) && is_array($data['assign_user_ids'])) {
			$data['assign_user_ids'] = implode(',',$data['assign_user_ids']);
		}
		$table = $this->getTable();
		if ($table->save($data) === true) {
			return $id;
		} else {
			return false;
		}

	}

	public function delete($id) {
		$user = JFactory::getUser();
		$authorised = $user->authorise('core.delete', $this->_context.'.'.$id) || $authorised = $user->authorise('core.delete.own', $this->_component.'.'.$id);
		if ($authorised !== true) {
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}
		$table = $this->getTable();
		return $table->delete($id);
	}
}
?>
