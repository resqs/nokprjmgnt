<?php
/**
* @version	$Id$
* @package	Joomla
* @subpackage	NoK-PrjMgnt
* @copyright	Copyright (c) 2017 Norbert Kümin. All rights reserved.
* @license	http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE
* @author	Norbert Kuemin
* @authorEmail	momo_102@bluemail.ch
*/
defined('_JEXEC') or die; // no direct access
// Check for request forgeries.
JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
// Initialise variables.
$app = JFactory::getApplication();
$model = $this->getModel();
// Get the data from the form POST
$data = JRequest::getVar('jform', array(), 'post', 'array');
$uri = JFactory::getURI();
$id = $uri->getVar('id');
if (!$id) {
	// Now insert the loaded data to the database via a function in the model
	$status = $model->storeData($data);
} else {
	// Now update the loaded data to the database via a function in the model
	$status = $model->storeData($data, $id);
}
// Check if ok and display appropriate message.  This can also have a redirect if desired.
if ($status !== false) {
	echo JText::_("COM_NOKPRJMGNT_DATA_SAVED");
} else {
	echo JText::_("COM_NOKPRJMGNT_DATA_NOT_SAVED");
}
?>