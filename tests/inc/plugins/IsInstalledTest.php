<?php
/**
 * Test the check if the Absence Manager is installed
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
 * Test the check if the Absence Manager is installed
 *
 * @author    Malte Gerth <mail@malte-gerth.de>
 * @copyright 2014 Malte Gerth (www.malte-gerth.de)
 * @license   MIT
 * @link      http://www.malte-gerth.de
 * @since     2014-10-14
 */
class IsInstalledTest extends TestCase
{

    /**
     * Test if the plugin is detected as installed if the table exists.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testIsInstalledReturnsTrueIfTableExists()
    {
        global $db;

        // Configure the mock object
        $db->expects($this->once())
            ->method('table_exists')
            ->with('userabsences')
            ->will($this->returnValue(true));

        // Call function to check if the plugin is installed
        $this->assertTrue(absencemanager_is_installed());
    }

    /**
     * Test if the plugin is detected as installed if the table exists.
     *
     * @global MockDatabaseClass $db
     *
     * @return void
     */
    public function testIsInstalledReturnsFalseIfTableDoesNotExists()
    {
        global $db;

        // Configure the mock object
        $db->expects($this->once())
            ->method('table_exists')
            ->with('userabsences')
            ->will($this->returnValue(false));

        // Call function to check if the plugin is installed
        $this->assertFalse(absencemanager_is_installed());
    }
}
