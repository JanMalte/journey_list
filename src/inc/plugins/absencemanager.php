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

// Disallow direct access to this file for security reasons
if (!defined('IN_MYBB')) {
    // @codeCoverageIgnoreStart
    die(
        'Direct initialization of this file is not allowed.<br />'
        . '<br />Please make sure IN_MYBB is defined.'
    );
    // @codeCoverageIgnoreEnd
}

// Add the hooks for the plugin
$plugins->add_hook('member_profile_end', 'add_absence_profile_info');
$plugins->add_hook(
    'usercp_do_profile_end',
    'save_absence_by_native_away_setting'
);
$plugins->add_hook(
    'usercp_do_profile_end',
    'finish_absence_by_native_away_setting'
);

/*
 * ************************************
 * Manage plugin functions
 * ************************************
 */
if (!function_exists('absencemanager_info')) {

    /**
     * Get an array with the plugin information.
     *
     * @return array
     */
    function absencemanager_info()
    {
        /**
         * Array of information about the plugin.
         * name: The name of the plugin
         * description: Description of what the plugin does
         * website: The website the plugin is maintained at (Optional)
         * author: The name of the author of the plugin
         * authorsite: The URL to the website of the author (Optional)
         * version: The version number of the plugin
         * guid: Unique ID issued by the MyBB Mods site for version checking
         * compatibility: A CSV list of MyBB versions supported. Ex, "121,123", "12*". Wildcards supported.
         */

        return array(
            'name' => 'Absence Manager',
            'description' => 'Provides the possibility for users to add all their absences to a list of the absences of the board members. This is compatible with the built in away function.',
            'website' => 'http://www.malte-gerth.de',
            'author' => 'Malte Gerth',
            'authorsite' => 'http://www.malte-gerth.de',
            'version' => '3.0.0-dev',
            'guid' => '',
            'compatibility' => '18*'
        );
    }
}

if (!function_exists('absencemanager_is_installed')) {

    /**
     * Called on the plugin management page to establish if a plugin is already
     * installed or not.<br />
     * This should return TRUE if the plugin is installed (by checking tables,
     * fields etc) or FALSE if the plugin is not installed.
     *
     * @global mixed $db
     *
     * @return boolean
     */
    function absencemanager_is_installed()
    {
        global $db;

        return $db->table_exists('userabsences');
    }
}

if (!function_exists('absencemanager_install')) {

    /**
     * Called whenever a plugin is installed by clicking the "Install" button in the
     * plugin manager.<br />
     * If no install routine exists, the install button is not shown and it assumed
     * any work will be performed in the _activate() routine.
     *
     * @global mixed $db
     *
     * @return void
     */
    function absencemanager_install()
    {
        global $db;

        $createTableQuery = 'CREATE TABLE IF NOT EXISTS '
            . $db->table_prefix . 'userabsences ( '
            . 'id INT(10) NOT NULL auto_increment, '
            . 'user_id INT(10) NOT NULL, '
            . 'start INT(10) DEFAULT NULL, '
            . 'end INT(10) DEFAULT NULL, '
            . 'reason varchar(255) DEFAULT NULL, '
            . 'PRIMARY KEY (id)'
            . ' ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;';
        $db->write_query($createTableQuery);
    }
}

if (!function_exists('absencemanager_uninstall')) {

    /**
     * Called whenever a plugin is to be uninstalled. This should remove ALL traces
     * of the plugin from the installation (tables etc). If it does not exist,
     * uninstall button is not shown.
     *
     * @global mixed $db
     *
     * @return void
     */
    function absencemanager_uninstall()
    {
        global $db;

        $db->drop_table('userabsences');
    }
}

if (!function_exists('absencemanager_activate')) {

    /**
     * Called whenever a plugin is activated via the Admin CP.
     * This should essentially make a plugin "visible" by adding
     * templates/template changes, language changes etc.
     *
     * @global mixed $db
     * @global mixed $mybb
     *
     * @return void
     */
    function absencemanager_activate()
    {
        global $db, $mybb;

        require MYBB_ROOT . '/inc/adminfunctions_templates.php';

        // Add settings
        $absencemanagerSettingsGroup = array(
            'name' => 'absencemanager',
            'title' => 'Absence Manager',
            'description' => 'Settings for the Absence Manager plugin.',
        );
        $db->insert_query('settinggroups', $absencemanagerSettingsGroup);
        $settingsGroupId = $db->insert_id();

        $enablePluginSetting = array(
            'name' => 'absencemanager_enable',
            'title' => 'Enable/Disable',
            'description' => 'Do you want to enable the Absence Manager?',
            'optionscode' => 'yesno',
            'value' => '1',
            'disporder' => '1',
            'gid' => intval($settingsGroupId),
        );
        $db->insert_query('settings', $enablePluginSetting);
        
        $onlyMembersSetting = array(
            'name' => 'absencemanager_only_members',
            'title' => 'Only for members',
            'description' => 'Should the list only be visible for members?',
            'optionscode' => 'yesno',
            'value' => '1',
            'disporder' => '2',
            'gid' => intval($settingsGroupId)
        );
        $db->insert_query('settings', $onlyMembersSetting);

        $showOnIndexSetting = array(
            'name' => 'absencemanager_show_on_index',
            'title' => 'Show on index',
            'description' => 'Should the list be shown on the index page?',
            'optionscode' => 'yesno',
            'value' => '0',
            'disporder' => '3',
            'gid' => intval($settingsGroupId)
        );
        $db->insert_query('settings', $showOnIndexSetting);

        // Rebuild settings
        rebuild_settings();

        // Convert native away settings of users into absence items
        convert_native_awaysettings();
    }
}

if (!function_exists('absencemanager_deactivate')) {

    /**
     * Called whenever a plugin is deactivated. This should essentially "hide"
     * the plugin from view by removing templates/template changes etc.
     *
     * It should not, however, remove any information such as tables, fields
     * etc - that should be handled by an _uninstall routine. When a plugin is
     * uninstalled, this routine will also be called before _uninstall() if
     * the plugin is active.
     *
     * @global mixed $db
     * @global mixed $mybb
     *
     * @return void
     */
    function absencemanager_deactivate()
    {
        global $db, $mybb;

        require MYBB_ROOT . '/inc/adminfunctions_templates.php';

        // Remove plugin settings
        $query = $db->simple_select(
            'settinggroups',
            'gid',
            'name="absencemanager"'
        );
        $settingsGroupId = (int) $db->fetch_field($query, 'gid');
        $db->delete_query('settinggroups', 'gid=' . $settingsGroupId);
        $db->delete_query('settings', 'gid=' . $settingsGroupId);

        // Rebuild settings
        rebuild_settings();
    }
}

if (!function_exists('convert_native_awaysettings')) {

    /**
     * Convert the native away settings into absence items.
     *
     * @global mixed $db
     *
     * @return void
     */
    function convert_native_awaysettings()
    {
        global $db;

        // Find all native away settings
        $query = $db->simple_select(
            'users',
            'uid, awaydate, returndate, awayreason',
            'away != 0'
        );

        // Convert the away settings of each user
        while($user = $db->fetch_array($query))
        {
            // Find the current absence
            $absence = find_current_absence($user['uid'], $user['awaydate']);

            // Create a timestamp for the end of the absence
            $returnTimestamp = null;
            if (!empty($user['returndate'])) {
                $parsedString = explode('-', $user['returndate']);
                $returnTimestamp = gmmktime(
                    0,
                    0,
                    0,
                    $parsedString[0],
                    $parsedString[1],
                    $parsedString[2]
                );
            }

            // Add a new absence for the user
            if (empty($absence)) {
                add_new_absence(
                    $user['uid'],
                    $user['awaydate'],
                    $returnTimestamp,
                    $user['awayreason']
                );
            } else {
                update_absence(
                    $absence['id'],
                    $user['awaydate'],
                    $returnTimestamp,
                    $user['awayreason']
                );
            }
        }
    }
}

/*
 * ************************************
 * Plugin core functions
 * ************************************
 */

if (!function_exists('is_user_absence')) {

    /**
     * Check if a user is absence.
     *
     * @global mixed $db
     *
     * @param integer $userId    ID of the user
     * @param integer $timestamp (Optional) UNIX timstamp; default: time()
     *
     * @return boolean
     */
    function is_user_absence($userId, $timestamp = null)
    {
        if (null == $timestamp) {
            $timestamp = (defined('TIME_NOW')) ? TIME_NOW : time();
        }

        $absence = find_current_absence($userId, $timestamp);

        return (empty($absence)) ? false : true;
    }
}

if (!function_exists('find_current_absence')) {

    /**
     * Find the current absence.
     *
     * @global mixed $db
     *
     * @param integer $userId    ID of the user
     * @param integer $timestamp (Optional) UNIX timstamp; default: time()
     *
     * @return array|null
     */
    function find_current_absence($userId, $timestamp = null)
    {
        global $db;

        if (null == $timestamp) {
            $timestamp = (defined('TIME_NOW')) ? TIME_NOW : time();
        }

        $query = $db->simple_select(
            'userabsences', '*',
            'user_id = ' . (int) $userId . ' AND '
            . ' end >= ' . (int) $timestamp . ' AND '
            . ' start <= ' . (int) $timestamp . ' ', array('limit' => 1)
        );

        return $db->fetch_array($query);
    }
}

if (!function_exists('add_new_absence')) {

    /**
     * Add a new absence.
     *
     * @global mixed $db
     *
     * @param integer $userId ID of the user
     * @param integer $start  UNIX timestamp
     * @param integer $end    UNIX timestamp
     * @param string  $reason Notice to display as absence reason
     *
     * @return integer The insert ID if available
     */
    function add_new_absence($userId, $start, $end = null, $reason = '')
    {
        global $db;

        $values = array(
            'user_id' => (int) $userId,
            'start' => (int) $start,
            'end' => (int) $end,
            'reason' => (string) $db->escape_string($reason),
        );

        return $db->insert_query('userabsences', $values);
    }
}

if (!function_exists('update_absence')) {

    /**
     * Update an existing absence.
     *
     * @global mixed $db
     *
     * @param integer $absenceId ID of the absence entry
     * @param integer $start     UNIX timestamp
     * @param integer $end       UNIX timestamp
     * @param string  $reason    Notice to display as absence reason
     *
     * @return void
     */
    function update_absence($absenceId, $start, $end = null, $reason = '')
    {
        global $db;

        $values = array(
            'start' => (int) $start,
            'end' => (null == $end) ? null : (int) $end,
            'reason' => (string) $reason,
        );

        $db->update_query('userabsences', $values, 'id = ' . (int) $absenceId);
    }
}

/*
 * ************************************
 * Plugin hooks
 * ************************************
 */

if (!function_exists('save_absence_by_native_away_setting')) {

    /**
     * Create or update an absence entry by the native away profile settings.
     *
     * @global mixed $mybb
     * @global mixed $away
     *
     * @return void
     */
    function save_absence_by_native_away_setting()
    {
        global $mybb;
        global $away;

        // If native away profile setting is true, add a new absence
        // for the user or update the existing one.
        if (!isset($away['away']) || 1 != $away['away']) {
            return;
        }

        // Define the current time if not yet defined by MyBB
        defined('TIME_NOW') || define('TIME_NOW', time());

        // Find the current absence
        $absence = find_current_absence($mybb->user['uid'], TIME_NOW);

        // Create a timestamp for the end of the absence
        $returnTimestamp = null;
        if (!empty($mybb->input['awayday'])) {
            $return_month = (int) substr($mybb->get_input('awaymonth'), 0, 2);
            $return_day = (int) substr($mybb->get_input('awayday'), 0, 2);
            $return_year = min((int) $mybb->get_input('awayyear'), 9999);
            $returnTimestamp = gmmktime(0, 0, 0, $return_month, $return_day, $return_year);
        }

        // Add a new absence for the user
        if (empty($absence)) {
            add_new_absence(
                $mybb->user['uid'],
                TIME_NOW,
                $returnTimestamp,
                $away['awayreason']
            );
        } else {
            update_absence(
                $absence['id'],
                $absence['start'],
                $returnTimestamp,
                $away['awayreason']
            );
        }
    }
}

if (!function_exists('finish_absence_by_native_away_setting')) {

    /**
     * Finish an existing absence if the user selected not be be away in
     * the native profile settings.
     *
     * @global mixed $mybb
     * @global mixed $away
     *
     * @return void
     */
    function finish_absence_by_native_away_setting()
    {
        global $mybb;
        global $away;

        // Define the current time if not yet defined by MyBB
        defined('TIME_NOW') || define('TIME_NOW', time());

        // Find the current absence
        $absence = null;
        if (0 == $away['away']) {
            $absence = find_current_absence($mybb->user['uid'], TIME_NOW);
        }

        // Finish an existing absence if the user selected not be be away in
        // the native profile settings
        if (!empty($absence)) {
            update_absence(
                $absence['id'], $absence['start'], TIME_NOW, $absence['reason']
            );
        }
    }
}

if (!function_exists('add_absence_profile_info')) {

    /**
     * Add the absence info to the profile using the $awaybit placeholder.
     *
     * @global mixed   $mybb
     * @global string  $awaybit
     * @global mixed   $templates
     * @global mixed   $lang
     * @global integer $uid
     *
     * @return void
     */
    function add_absence_profile_info()
    {
        global $mybb;
        global $awaybit;
        global $templates;
        global $lang;
        global $uid;

        // Define the current time if not yet defined by MyBB
        defined('TIME_NOW') || define('TIME_NOW', time());

        // Only add the away info if the user is absent
        if (is_user_absence($uid, TIME_NOW)) {
            // Find the current absence
            $absence = find_current_absence($uid, TIME_NOW);

            // Get the absence info
            $awayreason = $absence['reason'];
            $awaydate = my_date($mybb->settings['dateformat'], $absence['start']);
            $returndate = my_date($mybb->settings['dateformat'], $absence['end']);

            eval("\$awaybit = \"" . $templates->get("member_profile_away") . "\";");
        }
    }
}
