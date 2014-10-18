<?php
/**
 * Test the save_absence_by_native_away_setting() function of the Absence Manager
 *
 * PHP version 5.3
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */

/**
 * Test the save_absence_by_native_away_setting() function of the Absence Manager
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class SaveAbsenceByNativeAwaySettingTest extends TestCase
{

    /**
     * Test if save_absence_by_native_away_setting() does nothing, if no
     * away setting is given.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testSaveAbsenceByNativeAwaySettingDoesNothingIfNoAwayInfo()
    {
        global $db;
        global $away;

        // Prepare the global values
        $away = null;

        // Configure the mock object
        $db->expects($this->never())
            ->method('insert_query');
        $db->expects($this->never())
            ->method('update_query');

        // Call the function
        save_absence_by_native_away_setting();
    }

    /**
     * Test if save_absence_by_native_away_setting() does nothing, if the
     * away setting is removed/false.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testSaveAbsenceByNativeAwaySettingDoesNothingForEmptyAwayInfo()
    {
        global $db;
        global $away;

        // Prepare the global values
        $away = array();

        // Configure the mock object
        $db->expects($this->never())
            ->method('insert_query');
        $db->expects($this->never())
            ->method('update_query');

        // Call the function
        save_absence_by_native_away_setting();
    }

    /**
     * Test if save_absence_by_native_away_setting() does nothing, if the
     * away setting is removed/false.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testSaveAbsenceByNativeAwaySettingDoesNothingForInactiveAway()
    {
        global $db;
        global $away;

        // Prepare the global values
        $away = array(
            'away' => 0,
            'awayreason' => 'Some testing value',
        );

        // Configure the mock object
        $db->expects($this->never())
            ->method('insert_query');
        $db->expects($this->never())
            ->method('update_query');

        // Call the function
        save_absence_by_native_away_setting();
    }

    /**
     * Test if save_absence_by_native_away_setting() inserts a new absence
     * if the away setting is active with no end date and no current absence
     * is found.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testSaveAbsenceByNativeAwaySettingInsertsAbsenceNoEndDate()
    {
        global $db;
        global $away;
        global $mybb;

        // Test values
        $userId = 123;

        // Prepare the global values
        $mybb->user['uid'] = $userId;
        $away = array(
            'away' => 1,
            'awayreason' => 'Some testing value',
        );

        // Configure the mock object
        $db->expects($this->once())
            ->method('insert_query');
        $db->expects($this->never())
            ->method('update_query');

        // Call the function
        save_absence_by_native_away_setting();
    }

    /**
     * Test if save_absence_by_native_away_setting() inserts a new absence
     * if the away setting is active and no current absence is found.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testSaveAbsenceByNativeAwaySettingInsertsAbsence()
    {
        global $db;
        global $away;
        global $mybb;

        // Test values
        $userId = 123;
        $awaymonth = '10';
        $awayday = '05';
        $awayyear = '2014';

        // Prepare the global values
        $mybb->user['uid'] = $userId;
        $away = array(
            'away' => 1,
            'awayreason' => 'Some testing value',
        );

        // Configure the mock object
        $mybb->expects($this->any())
            ->method('get_input')
            ->willReturnMap(
                array(
                    array('awaymonth', $awaymonth),
                    array('awayday', $awayday),
                    array('awayyear', $awayyear),
                )
            );
        $db->expects($this->once())
            ->method('insert_query');
        $db->expects($this->never())
            ->method('update_query');

        // Call the function
        save_absence_by_native_away_setting();
    }

    /**
     * Test if save_absence_by_native_away_setting() updates the absence
     * if the away setting is active but no return date is set and a current
     * absence is found.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testSaveAbsenceByNativeAwaySettingUpdatesExistingAbsence()
    {
        global $db;
        global $away;
        global $mybb;

        // Define the current time if not yet defined by MyBB
        defined('TIME_NOW') || define('TIME_NOW', time());

        // Test value
        $timestamp = TIME_NOW;
        $userId = 123;
        $absenceId = 786;
        $absenceFromDatabase = array(
            'id' => $absenceId,
            'user_id' => $userId,
            'start' => $timestamp - 50000,
            'end' => $timestamp + 50000,
            'reason' => 'Test reason',
        );

        // Prepare the global values
        $mybb->user['uid'] = $userId;
        $away = array(
            'away' => 1,
            'awayreason' => 'Some testing value',
        );

        // Configure the mock object
        $db->expects($this->never())
            ->method('insert_query');
        $db->expects($this->once())
            ->method('update_query')
            ->with(
                'userabsences',
                array(
                    'start' => $absenceFromDatabase['start'],
                    'end' => null,
                    'reason' => 'Some testing value',
                ),
                'id = ' . (int) $absenceId
            );
        $db->expects($this->once())
            ->method('fetch_array')
            ->willReturn($absenceFromDatabase);

        // Call the function
        save_absence_by_native_away_setting();
    }

    /**
     * Test if save_absence_by_native_away_setting() updates the absence
     * if the away setting is active but a new return date is set and a current
     * absence is found.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testSaveAbsenceByNativeAwaySettingUpdatesExistingAbsenceWithNewDate()
    {
        global $db;
        global $away;
        global $mybb;

        // Define the current time if not yet defined by MyBB
        defined('TIME_NOW') || define('TIME_NOW', time());

        // Test value
        $timestamp = TIME_NOW;
        $userId = 123;
        $absenceId = 786;
        $absenceFromDatabase = array(
            'id' => $absenceId,
            'user_id' => $userId,
            'start' => $timestamp - 50000,
            'end' => $timestamp + 50000,
            'reason' => 'Test reason',
        );
        $awaymonth = date('m', $timestamp);
        $awayday = '19';
        $awayyear = date('y', $timestamp);

        // Configure the mock objects
        $mybb->expects($this->any())
            ->method('get_input')
            ->willReturnMap(
                array(
                    array('awaymonth', $awaymonth),
                    array('awayday', $awayday),
                    array('awayyear', $awayyear),
                )
            );
        $db->expects($this->never())
            ->method('insert_query');
        $db->expects($this->once())
            ->method('update_query')
            ->with(
                'userabsences',
                array(
                    'start' => $absenceFromDatabase['start'],
                    'end' => gmmktime(0, 0, 0, $awaymonth, $awayday, $awayyear),
                    'reason' => 'Some testing value',
                ),
                'id = ' . (int) $absenceId
            );
        $db->expects($this->once())
            ->method('fetch_array')
            ->willReturn($absenceFromDatabase);

        // Prepare the global values
        $mybb->user['uid'] = $userId;
        $mybb->input['awayday'] = '25';
        $mybb->input['awaymonth'] = '10';
        $mybb->input['awayyear'] = '2014';
        $away = array(
            'away' => 1,
            'awayreason' => 'Some testing value',
        );

        // Call the function
        save_absence_by_native_away_setting();
    }
}
