<?php
/**
 * Test the function add_absence_profile_info() of the Absence Manager Plugin
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
 * Test the function add_absence_profile_info() of the Absence Manager Plugin
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class AddAbsenceProfileInfoTest extends TestCase
{

    /**
     * Test if add_absence_profile_info() adds nothing if no absence is found.
     *
     * @return void
     */
    public function testAddAbsenceProfileInfoAddsNothingIfNoAbsenceIsFound()
    {
        global $db;
        global $uid;
        global $awaybit;

        // Define the current time if not yet defined by MyBB
        defined('TIME_NOW') || define('TIME_NOW', time());

        // Test value
        $timestamp = TIME_NOW;
        $userId = 123;
        $absenceFromDatabase = null;

        // Prepare the global values
        $uid = $userId;
        $awaybit = null;

        // Configure the mock object
        $db->expects($this->atLeastOnce())
            ->method('simple_select')
            ->with(
                'userabsences',
                '*',
                $this->logicalAnd(
                    $this->stringContains('start <= ' . $timestamp),
                    $this->stringContains('end >= ' . $timestamp),
                    $this->stringContains('user_id = ' . $userId)
                )
            );
        $db->expects($this->once())
            ->method('fetch_array')
            ->willReturn($absenceFromDatabase);

        // Call the function
        add_absence_profile_info();

        // Test the assertions
        $this->assertNull($awaybit);
    }

    /**
     * Test if add_absence_profile_info() loads the
     * template 'member_profile_away'.
     *
     * @return void
     */
    public function testAddAbsenceProfileInfoLoadsTemplateIfAbsenceIsFound()
    {
        global $db;
        global $uid;
        global $awaybit;
        global $templates;
        global $mybb;

        // Test value
        $timestamp = (defined('TIME_NOW')) ? TIME_NOW : time();
        $userId = 123;
        $absenceFromDatabase = array(
            'user_id' => $userId,
            'start' => $timestamp - 50000,
            'end' => $timestamp + 50000,
            'reason' => 'Test reason',
        );

        // Prepare the global values
        $uid = $userId;
        $awaybit = null;
        $mybb = new stdClass();
        $mybb->settings = array('dateformat' => 'Y-m-d');

        // Configure the mock objects
        $db->expects($this->atLeastOnce())
            ->method('simple_select')
            ->with(
                'userabsences',
                '*',
                $this->logicalAnd(
                    $this->stringContains('start <= ' . $timestamp),
                    $this->stringContains('end >= ' . $timestamp),
                    $this->stringContains('user_id = ' . $userId)
                )
            );
        $db->expects($this->atLeastOnce())
            ->method('fetch_array')
            ->willReturn($absenceFromDatabase);
        $templates->expects($this->once())
            ->method('get')
            ->with('member_profile_away')
            ->willReturn('Away: ');

        // Call the function
        add_absence_profile_info();
    }

    /**
     * Test if add_absence_profile_info() loads and parses the
     * template 'member_profile_away'.
     *
     * @return void
     */
    public function testAddAbsenceProfileInfoParsesTemplateIfAbsenceIsFound()
    {
        global $db;
        global $uid;
        global $awaybit;
        global $templates;
        global $mybb;

        // Test value
        $timestamp = (defined('TIME_NOW')) ? TIME_NOW : time();
        $userId = 123;
        $absenceFromDatabase = array(
            'user_id' => $userId,
            'start' => $timestamp - 50000,
            'end' => $timestamp + 50000,
            'reason' => 'Test reason',
        );

        // Prepare the global values
        $uid = $userId;
        $awaybit = null;
        $mybb = new stdClass();
        $mybb->settings = array('dateformat' => 'Y-m-d');

        // Configure the mock objects
        $db->expects($this->atLeastOnce())
            ->method('simple_select')
            ->with(
                'userabsences',
                '*',
                $this->logicalAnd(
                    $this->stringContains('start <= ' . $timestamp),
                    $this->stringContains('end >= ' . $timestamp),
                    $this->stringContains('user_id = ' . $userId)
                )
            );
        $db->expects($this->atLeastOnce())
            ->method('fetch_array')
            ->willReturn($absenceFromDatabase);
        $templates->expects($this->once())
            ->method('get')
            ->with('member_profile_away')
            ->willReturn('Away: ({$awayreason})');

        // Call the function
        add_absence_profile_info();

        // Test the assertions
        $this->assertNotNull($awaybit);
        $this->assertSame('Away: (' . 'Test reason' . ')', $awaybit);
    }
}
