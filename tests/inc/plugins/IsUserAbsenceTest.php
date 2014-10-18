<?php
/**
 * Test the function is_user_absence of the Absence Manager Plugin
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
 * Test the function is_user_absence of the Absence Manager Plugin
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class IsUserAbsenceTest extends TestCase
{

    /**
     * Test if is_user_absence() returns false if no entry is found.
     *
     * @return void
     */
    public function testIsUserAbsenceFalse()
    {
        global $db;

        // Test value
        $timestamp = (defined('TIME_NOW')) ? TIME_NOW : time();
        $userId = 123;
        $absenceFromDatabase = null;

        // Configure the mock object
        $db->expects($this->once())
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
        $this->assertFalse(is_user_absence($userId, $timestamp));
    }

    /**
     * Test if is_user_absence() returns false if no entry is found.
     *
     * @return void
     */
    public function testIsUserAbsenceFalseWithNoTimestamp()
    {
        global $db;

        // Test value
        if (!defined('TIME_NOW')) define('TIME_NOW', time());
        $userId = 123;
        $absenceFromDatabase = null;

        // Configure the mock object
        $db->expects($this->once())
            ->method('simple_select')
            ->with(
                'userabsences',
                '*',
                $this->logicalAnd(
                    $this->stringContains('start <= ' . TIME_NOW),
                    $this->stringContains('end >= ' . TIME_NOW),
                    $this->stringContains('user_id = ' . $userId)
                )
            );
        $db->expects($this->once())
            ->method('fetch_array')
            ->willReturn($absenceFromDatabase);

        // Call the function
        $this->assertFalse(is_user_absence($userId));
    }

    /**
     * Test if is_user_absence() returns true if an absence is found.
     *
     * @return void
     */
    public function testIsUserAbsenceTrue()
    {
        global $db;

        // Test value
        $timestamp = (defined('TIME_NOW')) ? TIME_NOW : time();
        $userId = 123;
        $absenceFromDatabase = array(
            'user_id' => $userId,
            'start' => $timestamp - 50000,
            'end' => $timestamp + 50000,
            'reason' => 'Test reason',
        );

        // Configure the mock object
        $db->expects($this->once())
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
        $this->assertTrue(is_user_absence($userId, $timestamp));
    }

    /**
     * Test if is_user_absence() returns true if an absence is found.
     *
     * @return void
     */
    public function testIsUserAbsenceTrueWithNoTimestamp()
    {
        global $db;

        // Test value
        if (!defined('TIME_NOW')) define('TIME_NOW', time());
        $userId = 123;
        $absenceFromDatabase = array(
            'user_id' => $userId,
            'start' => TIME_NOW - 50000,
            'end' => TIME_NOW + 50000,
            'reason' => 'Test reason',
        );

        // Configure the mock object
        $db->expects($this->once())
            ->method('simple_select')
            ->with(
                'userabsences',
                '*',
                $this->logicalAnd(
                    $this->stringContains('start <= ' . TIME_NOW),
                    $this->stringContains('end >= ' . TIME_NOW),
                    $this->stringContains('user_id = ' . $userId)
                )
            );
        $db->expects($this->once())
            ->method('fetch_array')
            ->willReturn($absenceFromDatabase);

        // Call the function
        $this->assertTrue(is_user_absence($userId));
    }
}
