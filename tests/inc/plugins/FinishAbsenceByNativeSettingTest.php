<?php
/**
 * Test the finish_absence_by_native_away_setting() function of the
 * Absence Manager
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
 * Test the finish_absence_by_native_away_setting() function of the
 * Absence Manager
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class FinishAbsenceByNativeSettingTest extends TestCase
{

    /**
     * Test if finish_absence_by_native_away_setting() calls the corresponding
     * query to end the current absence.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testFinishAbsenceByNativeAwaySettingCallsUpdateQuery()
    {
        global $db, $away, $mybb;

        // Test values
        $mybb = new \stdClass();
        $mybb->user = array('uid' => 123);
        $away = array('away' => 0);

        // Configure the mock object
        $db->expects($this->never())
            ->method('insert_query');
        $db->expects($this->never())
            ->method('update_query');

        // Call the function
        finish_absence_by_native_away_setting();
    }

    /**
     * Test if finish_absence_by_native_away_setting() calls no update or
     * insert query, if the away setting is set.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testFinishAbsenceByNativeAwaySettingCallsNothingIfAwayIsSet()
    {
        global $db, $away;

        // Test values
        $away = array('away' => 1);

        // Configure the mock object
        $db->expects($this->never())
            ->method('insert_query');
        $db->expects($this->never())
            ->method('update_query');

        // Call the function
        finish_absence_by_native_away_setting();
    }
}
