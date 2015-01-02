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

define('IN_MYBB', 1);
define('THIS_SCRIPT', 'absence.php');

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

// Count all items
$countQuery = $db->simple_select('userabsences', 'COUNT(id) AS items');
$itemCount = $db->fetch_field($countQuery, 'items');

// Multipage settings
$itemsPerPage = 15;
$currentPage = $mybb->get_input('page', 1);

// Generate multipage navigation
$multipage = multipage(
    $itemCount, $itemsPerPage, $currentPage, THIS_SCRIPT . '?page={page}', false
);

// Query all items for the current page
$offset = $itemsPerPage * max(0, $currentPage - 1);
$query = $db->query("
    SELECT a.*, u.*
    FROM " . TABLE_PREFIX . "userabsences a
    LEFT JOIN " . TABLE_PREFIX . "users u ON (u.uid=a.user_id)
    WHERE 1=1
    ORDER BY start ASC
    LIMIT {$itemsPerPage} OFFSET {$offset}
");

// Generate the table rows for each absence item
$absence_rows = '';
while ($absence = $db->fetch_array($query)) {
    $user = get_user($absence['user_id']);

    // Build the profil link
    $user['username_formatted'] = format_name($user['username'], $user['usergroup'], $user['displaygroup']);
    $absence['profilelink'] = build_profile_link($user['username_formatted'], $user['uid']);
    // $post is needed for the postbit_avatar template
    $post = array(
        'profilelink_plain' => $mybb->settings['bburl'] . '/' . get_profile_link($user['uid']),
    );

    // Determine the status to show for the user (Online/Offline/Away)
    $timecut = TIME_NOW - $mybb->settings['wolcutoff'];
    if ($user['lastactive'] > $timecut && ($user['invisible'] != 1 || $mybb->usergroup['canviewwolinvis'] == 1) && $user['lastvisit'] != $user['lastactive']) {
        eval("\$absence['onlinestatus'] = \"" . $templates->get("postbit_online") . "\";");
    } else {
        if ($absence['away'] == 1 && $mybb->settings['allowaway'] != 0) {
            eval("\$absence['onlinestatus'] = \"" . $templates->get("postbit_away") . "\";");
        } else {
            eval("\$absence['onlinestatus'] = \"" . $templates->get("postbit_offline") . "\";");
        }
    }

    // Build the user avatar
    $absence['useravatar'] = '';
    if (isset($mybb->user['showavatars']) && $mybb->user['showavatars'] != 0 || $mybb->user['uid'] == 0) {
        $useravatar = format_avatar(htmlspecialchars_uni($user['avatar']), $user['avatardimensions'], $mybb->settings['postmaxavatarsize']);
        eval("\$absence['useravatar'] = \"" . $templates->get("postbit_avatar") . "\";");
    }
    if ($mybb->settings['absencemanager_show_avatars'] == 0) {
        $absence['useravatar'] = '';
    }

    // Format the dates
    $absence['start_plain'] = $absence['start'];
    $absence['start'] = my_date($mybb->settings['dateformat'], $absence['start']);
    $absence['end_plain'] = $absence['end'];
    $absence['end'] = my_date($mybb->settings['dateformat'], $absence['end']);
    
    eval("\$absence_rows .= \"" . $templates->get("absencemanager_table_row") . "\";");
}
eval("\$absence_table = \"" . $templates->get("absencemanager_table") . "\";");

// Evaluate and output the page
eval("\$absencemanager_page = \"" . $templates->get("absencemanager_page") . "\";");
output_page($absencemanager_page);
