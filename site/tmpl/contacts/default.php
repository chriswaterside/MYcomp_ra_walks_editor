<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Ra_walks_editor
 * @author     Chris Vaughan  <ruby.tuesday@ramblers-webs.org.uk>
 * @copyright  2024 ruby.tuesday@ramblers-webs.org.uk
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Session\Session;
use \Joomla\CMS\User\UserFactoryInterface;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user = Factory::getApplication()->getIdentity();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_ra_walks_editor') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'contactform.xml');
$canEdit = $user->authorise('core.edit', 'com_ra_walks_editor') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'contactform.xml');
$canCheckin = $user->authorise('core.manage', 'com_ra_walks_editor');
$canChange = $user->authorise('core.edit.state', 'com_ra_walks_editor');
$canDelete = $user->authorise('core.delete', 'com_ra_walks_editor');

// Import CSS
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_ra_walks_editor.list');
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">
          <?php
          if (!empty($this->filterForm)) {
              echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
          }
          ?>
    <div class="table-responsive">
        <table class="table table-striped" id="contactList">
            <thead>
                <tr>
                    <th class=''>
                        <?php echo HTMLHelper::_('grid.sort', 'COM_RA_WALKS_EDITOR_CONTACTS_ID', 'a.id', $listDirn, $listOrder); ?>
                    </th>
                    <th >
                        <?php echo HTMLHelper::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
                    </th>
                    <th class=''>
                        <?php echo HTMLHelper::_('grid.sort', 'COM_RA_WALKS_EDITOR_CONTACTS_CONTACTNAME', 'a.contactname', $listDirn, $listOrder); ?>
                    </th>



                    <th class=''>
                        <?php echo HTMLHelper::_('grid.sort', 'COM_RA_WALKS_EDITOR_CONTACTS_DISPLAYNAME', 'a.displayname', $listDirn, $listOrder); ?>
                    </th>



                    <th class=''>
                        <?php echo HTMLHelper::_('grid.sort', 'COM_RA_WALKS_EDITOR_CONTACTS_EMAIL', 'a.email', $listDirn, $listOrder); ?>
                    </th>

                    <th class=''>
                        <?php echo HTMLHelper::_('grid.sort', 'COM_RA_WALKS_EDITOR_CONTACTS_TELEPHONE1', 'a.telephone1', $listDirn, $listOrder); ?>
                    </th>

                    <th class=''>
                        <?php echo HTMLHelper::_('grid.sort', 'COM_RA_WALKS_EDITOR_CONTACTS_TELEPHONE2', 'a.telephone2', $listDirn, $listOrder); ?>
                    </th>

                    <?php if ($canEdit || $canDelete): ?>
                        <th class="center">
                            <?php echo Text::_('COM_RA_WALKS_EDITOR_CONTACTS_ACTIONS'); ?>
                        </th>
                    <?php endif; ?>

                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
                        <div class="pagination">
                            <?php echo $this->pagination->getPagesLinks(); ?>
                        </div>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($this->items as $i => $item) : ?>
                    <?php $canEdit = $user->authorise('core.edit', 'com_ra_walks_editor'); ?>
                    <?php if (!$canEdit && $user->authorise('core.edit.own', 'com_ra_walks_editor')): ?>
                        <?php $canEdit = Factory::getApplication()->getIdentity()->id == $item->created_by; ?>
                    <?php endif; ?>

                    <tr class="row<?php echo $i % 2; ?>">
                        <td>
                            <?php echo $item->id; ?>
                        </td>
                        <td>
                            <?php $class = ($canChange) ? 'active' : 'disabled'; ?>
                            <a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? Route::_('index.php?option=com_ra_walks_editor&task=contact.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
                                <?php if ($item->state == 1): ?>
                                    <i class="icon-publish"></i>
                                <?php else: ?>
                                    <i class="icon-unpublish"></i>
                                <?php endif; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $item->contactname; ?>
                        </td>

                        <td>
                            <?php $canCheckin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_ra_walks_editor.' . $item->id) || $item->checked_out == Factory::getApplication()->getIdentity()->id; ?>
                            <?php if ($canCheckin && $item->checked_out > 0) : ?>
                                <a href="<?php echo Route::_('index.php?option=com_ra_walks_editor&task=contact.checkin&id=' . $item->id . '&' . Session::getFormToken() . '=1'); ?>">
                                    <?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'contact.', false); ?></a>
                            <?php endif; ?>
                            <?php echo $this->escape($item->displayname); ?>
                        </td>

                        <td>
                            <?php echo obscureInfo($item->email, $canEdit); ?>
                        </td>
                        <td>
                            <?php echo obscureInfo($item->telephone1, $canEdit); ?>
                        </td>
                        <td>
                            <?php echo obscureInfo($item->telephone2, $canEdit); ?>
                        </td>
                        <?php if ($canEdit || $canDelete): ?>
                            <td class="center">
                                <?php $canCheckin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_ra_walks_editor.' . $item->id) || $item->checked_out == Factory::getApplication()->getIdentity()->id; ?>

                                <?php if ($canEdit && $item->checked_out == 0): ?>
                                    <a href="<?php echo Route::_('index.php?option=com_ra_walks_editor&task=contact.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
                                <?php endif; ?>
                                <?php if ($canDelete): ?>
                                    <a href="<?php echo Route::_('index.php?option=com_ra_walks_editor&task=contactform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($canCreate) : ?>
        <a href="<?php echo Route::_('index.php?option=com_ra_walks_editor&task=contactform.edit&id=0', false, 0); ?>"
           class="btn btn-success btn-small"><i
                class="icon-plus"></i>
            <?php echo Text::_('COM_RA_WALKS_EDITOR_ADD_ITEM'); ?></a>
    <?php endif; ?>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value=""/>
    <input type="hidden" name="filter_order_Dir" value=""/>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php
if ($canDelete) {
    $wa->addInlineScript("
			jQuery(document).ready(function () {
				jQuery('.delete-button').click(deleteItem);
			});

			function deleteItem() {

				if (!confirm(\"" . Text::_('COM_RA_WALKS_EDITOR_DELETE_MESSAGE') . "\")) {
					return false;
				}
			}
		", [], [], ["jquery"]);
}
?>
<?php

function obscureInfo($text, $canedit) {
    if ($canedit) {
        return $text;
    } else {
        $no = strlen($text);
        If ($no < 5) {
            return str_repeat("*", $no);
        }
        $out = $text;
        for ($i = 0; $i <= $no - 5; $i++) {
            $char = substr($out, $i, 1);
            if ($char !== "@" and $char !== " ") {
                $out = substr_replace($out, "*", $i, 1);
            }
        }

        return $out;
    }
}
?>