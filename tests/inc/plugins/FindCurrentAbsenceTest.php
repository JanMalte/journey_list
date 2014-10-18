<?php
/**
 * Test the function find_current_absence() of the Absence Manager Plugin
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
 * Test the function find_current_absence() of the Absence Manager Plugin
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class FindCurrentAbsenceTest extends TestCase
{

    /**
     * Test if find_current_absence() returns null if no entry is found.
     *
     * @return void
     */
    public function testFindCurrentAbsenceFalse()
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
        $this->assertSame($absenceFromDatabase, find_current_absence($userId));
    }

    /**
     * Test if find_current_absence() returns null  if no entry is found
     * and no timestamp is given.
     *
     * @return void
     */
    public function testFindCurrentAbsenceFalseWithNoTimestamp()
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
        $this->assertSame($absenceFromDatabase, find_current_absence($userId));
    }

    /**
     * Test if find_current_absence() returns the absence.
     *
     * @return void
     */
    public function testFindCurrentAbsenceTrue()
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
        $this->assertSame($absenceFromDatabase, find_current_absence($userId));
    }

    /**
     * Test if find_current_absence() returns the absence if no timestamp
     * is given.
     *
     * @return void
     */
    public function testFindCurrentAbsenceTrueWithNoTimestamp()
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
        $this->assertSame($absenceFromDatabase, find_current_absence($userId));
    }
}
