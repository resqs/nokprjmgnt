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

class NoKPrjMgntControllerProjects extends JControllerAdmin {
	public function getModel($name = 'Project', $prefix = 'NoKPrjMgntModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function export() {
		JLoader::register('ExImportHelper', __DIR__.'/../helpers/eximport.php', true);
		ExImportHelper::export();
	}

	public function import() {
		$view = $this->getView('Projects', 'html');
		$view->setLayout('import');
		$view->display();
	}

	public function import_do() {
		// Get the input
		$input = JFactory::getApplication()->input;
		$file = $input->files->get('importfile');
		$content = '';
		if (isset($file['tmp_name'])) {
			$content = file_get_contents($file['tmp_name']);
			unlink($file['tmp_name']);
		}
		JLoader::register('ExImportHelper', __DIR__.'/../helpers/eximport.php', true);
		$data  = ExImportHelper::import($content);
		$this->setRedirect(JRoute::_('index.php?option='.$this->option, false));
	}

	public function import_cancel() {
		$this->setRedirect(JRoute::_('index.php?option='.$this->option, false));
	}

	protected function postDeleteHook(JModelLegacy $model, $ids = null) {
	}

	public function delete() {
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		//TODO: Delete tasks for project
		$model = $this->getModel('Project');
		$model->delete($cid);
		$this->setRedirect(JRoute::_('index.php?option='.$this->option, false));
	}
}
?>
