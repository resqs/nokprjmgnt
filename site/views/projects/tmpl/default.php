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
$details = false;
$uriEdit = new JURI(JURI::Root().'/index.php');
$uriEdit->setVar('layout','form');
$uriEdit->setVar('Itemid','');
$uriEdit->setVar('view','project');
$uriEdit->setVar('option','com_nokprjmgnt');
$uriDelete = new JURI(JURI::Root().'/index.php');
$uriDelete->setVar('layout','delete');
$uriDelete->setVar('Itemid','');
$uriDelete->setVar('view','project');
$uriDelete->setVar('option','com_nokprjmgnt');
if ($this->paramsMenuEntry->get('detail_enable') != '0') {
	$details = true;
	$uriDetail = new JURI(JURI::Root().'/index.php');
	$uriDetail->setVar('layout','detail');
	$uriDetail->setVar('Itemid','');
	$uriDetail->setVar('view','project');
	$uriDetail->setVar('option','com_nokprjmgnt');
}
// Get columns
$cols = array();
for ($i=1;$i<=20;$i++) {
	$field = 'column_'.$i;
	$cols[] = $this->paramsMenuEntry->get($field);
}
$colcount = count($cols);
// Display
$border='border-style:solid; border-width:1px';
$width='';
if ($this->paramsMenuEntry->get('width') != '0') {
	$width='width="'.$this->paramsMenuEntry->get('width').'" ';
}
if ($this->paramsMenuEntry->get('table_center') == '1') echo "<center>\n";
if ($this->paramsMenuEntry->get('border_type') != '') {
	echo '<table '.$width.'border="0" cellspacing="0" cellpadding="0" style="'.$border.'">'."\n";
} else {
	echo '<table '.$width.'border="0" style="border-style:none; border-width:0px">'."\n";
}
$header = $this->getModel()->getHeader($cols);
echo '<tr>';
foreach($header as $strSingle) {
	if ($strSingle != '') {
		echo '<th align="left">';
		if ($this->paramsMenuEntry->get('show_header', '1') == '1') {
			echo $strSingle;
		}
		echo '</th>';
	}
}
echo '<th align="left">';
if ($this->componentCanDo->get('core.create')) {
	echo '<a style="text-decoration: none;" href="'.$uriEdit->toString().'"><span class="icon-new"></span></a>';
}
echo '</th>';
echo '</tr>'."\n";
$detailColumn = $this->paramsMenuEntry->get('detail_column_link');
//echo "<pre>".$detailColumn."</pre>";
if ($this->items) {
	switch ($this->paramsMenuEntry->get( "border_type")) {
		case "row":
			$borderStyle = " style=\"border-top-style:solid; border-width:1px\"";
			break;
		case "grid":
			$borderStyle = " style=\"".$border."\"";
			break;
		default:
			$borderStyle = "";
			break;
	}
	foreach($this->items as $item) {
		$itemCanDo = JHelperContent::getActions('com_nokprjmgnt','project',$item->id);
		$row = (array) $item;
		echo "<tr>\n";
		if ($details) {
			$uriDetail->setVar('id',$item->id);
		}
		for($j=0;$j<$colcount;$j++) {
			$field = $cols[$j];
			if (!empty($field)) {
				$data = $row[$field];
				echo "<td".$borderStyle.">";
				if ($details && (($detailColumn == "") || ($detailColumn == $field))) {
					echo "<a href=\"".$uriDetail->toString()."\">".$data."</a>";
				} else {
					echo $data;
				}
				echo "</td>";
			}
		}
		echo '<td>';
		if ($itemCanDo->get('core.edit')) {
			$uriEdit->setVar('id',$item->id);
			echo '<a style="text-decoration: none;" href="'.$uriEdit->toString().'"><span class="icon-edit"></span></a>';
		}
		echo '</td>';
		echo '<td>';
		if ($itemCanDo->get('core.delete')) {
			$uriDelete->setVar('id',$item->id);
			echo '<a style="text-decoration: none;" href="'.$uriDelete->toString().'"><span class="icon-trash"></span></a>';
		}
		echo '</td>';
		echo "</tr>\n";
	}
}
echo "</table>\n";
if ($this->paramsMenuEntry->get( "table_center") == "1") echo "</center>\n";
?>