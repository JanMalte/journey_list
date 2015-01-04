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
class FinishAbsenceByNativeSettingTrueTest extends TestCase
{

    public static $userId = 255;
    public static $absenceId = 123;
    public static $absenceStart = 1413438982;
    public static $absenceReason = 'Already existing absence';

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
        $mybb->user = array('uid' => self::$userId);
        $away = array('away' => 0);
        $absenceFromDatabase = array(
            'id' => self::$absenceId,
            'user_id' => self::$userId,
            'start' => self::$absenceStart,
            'reason' => self::$absenceReason,
        );
        $timestamp = (defined('TIME_NOW')) ? TIME_NOW : time();

        // Configure the mock object
        $db->expects($this->any())
            ->method('escape_string')
            ->will($this->returnArgument(0));
        $db->expects($this->once())
            ->method('simple_select')
            ->with(
                'userabsences',
                '*',
                $this->logicalAnd(
                    $this->stringContains('start <= ' . $timestamp),
                    $this->stringContains('end >= ' . $timestamp),
                    $this->stringContains('user_id = ' . self::$userId)
                )
            );
        $db->expects($this->once())
            ->method('fetch_array')
            ->willReturn($absenceFromDatabase);
        $db->expects($this->never())
            ->method('insert_query');
        $db->expects($this->once())
            ->method('update_query')
            ->with(
                'userabsences',
                array(
                    'start' => self::$absenceStart,
                    'end' => $timestamp,
                    'reason' => self::$absenceReason,
                ),
                'id = ' . self::$absenceId
            );

        // Call the function
        finish_absence_by_native_away_setting();
    }
}
