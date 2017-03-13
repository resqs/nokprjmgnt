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

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
        <tr class="row<?php echo $i % 2; ?>">
                <td>
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td>
                        <?php echo $item->title; ?>
                </td>
                <td>
                        <?php echo $item->category; ?>
                </td>
                <td>
                        <?php echo $item->status; ?>
                </td>
                <td>
                        <?php echo $item->priority; ?>
                </td>
                <td>
                        <?php echo $item->duedate; ?>
                </td>
        </tr>
<?php endforeach; ?>