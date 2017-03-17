<?php
/**
* @version	$Id$
* @package	Joomla
* @subpackage	NoK-PrjMgnt
* @copyright	Copyright (c) 2017 Norbert K�min. All rights reserved.
* @license	http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE
* @author	Norbert Kuemin
* @authorEmail	momo_102@bluemail.ch
*/
defined('_JEXEC') or die; // no direct access
$uriEdit = new JURI(JURI::Root().'/index.php');
$uriEdit->setVar('layout','form');
$uriEdit->setVar('Itemid','');
$uriEdit->setVar('view','project');
$uriEdit->setVar('option','com_nokprjmgnt');
$uriEdit->setVar('id',$this->item->id);
$uriDelete = new JURI(JURI::Root().'/index.php');
$uriDelete->setVar('layout','delete');
$uriDelete->setVar('Itemid','');
$uriDelete->setVar('view','project');
$uriDelete->setVar('option','com_nokprjmgnt');
$uriDelete->setVar('id',$this->item->id);
$deleteConfirmMsg = JText::_("COM_NOKPRJMGNT_PROJECT_CONFIRM_DELETE");
$uriProject = new JURI(JURI::Root().'/index.php');
$uriProject->setVar('layout','detail');
$uriProject->setVar('Itemid','');
$uriProject->setVar('view','project');
$uriProject->setVar('option','com_nokprjmgnt');
$uriProject->setVar('id',$this->item->project_id);
?>
<h1><?php echo JText::_("COM_NOKPRJMGNT_TASK_LABEL").': '.$this->item->title; ?></h1>
<p>
	<?php echo JText::_("COM_NOKPRJMGNT_PROJECT_FIELD_TITLE_LABEL").':<a href="'.$uriProject->toString().'">'.$this->item->project_title.'</a>'; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_TASK_FIELD_STATUS_LABEL").':'.$this->item->status; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_TASK_FIELD_PRIORITY_LABEL").':'.$this->item->priority; ?>
	<?php echo JText::_("COM_NOKPRJMGNT_TASK_FIELD_DUE_DATE_LABEL").':'.$this->item->duedate; ?>
	<?php if ($this->canDo->get('core.edit')): ?>
		<a style="text-decoration: none;" href="<?php echo $uriEdit->toString(); ?>"><span class="icon-edit"></span></a>
		<a style="text-decoration: none;" href="<?php echo $uriDelete->toString(); ?>" onClick="return confirm('<?php echo $deleteConfirmMsg; ?>');"><span class="icon-trash"></span></a>
	<?php endif; ?>
</p>
<?php echo $this->item->description; ?>