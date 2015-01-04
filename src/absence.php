<?php
/**
 * Absence Manager MyBB Plugin
 *
 * PHP version 5.3
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */

defined('IN_MYBB') || define('IN_MYBB', 1);
defined('THIS_SCRIPT') || define('THIS_SCRIPT', 'absence.php');

// Templates used by this page
$templatelist = "absencemanager_page,absencemanager_list,absencemanager_list_row";

require_once "./global.php";

// Load language used by this plugin
$lang->load("absencemanager");

// Check if plugin is enabled
if (!isset($mybb->settings['absencemanager_enable'])
    || $mybb->settings['absencemanager_enable'] == '0'
) {
    error(
        sprintf(
            $lang->absencemanager_disabled,
            '<a href="https://github.com/JanMalte/mybb_absence_manager">',
            '</a>'
        )
    );
}

// Navigation bar
add_breadcrumb($lang->absencemanager_breadcrumb);

// Check access
if ($mybb->settings['absencemanager_only_members'] && !$mybb->user['uid']) {
    error_no_permission();
}

$absence_table = build_absence_table();

// Evaluate and output the page
eval("\$absencemanager_page = \"" . $templates->get("absencemanager_page") . "\";");
output_page($absencemanager_page);
